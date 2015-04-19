<?php
if ( ! defined('ABSPATH') ) {
    die('You are not allowed to call this page directly.');
}

class FrmForm{

    /**
     * @return int|boolean id on success or false on failure
     */
    public static function create( $values ) {
        global $wpdb;

        $new_values = array(
            'form_key'      => FrmAppHelper::get_unique_key($values['form_key'], $wpdb->prefix .'frm_forms', 'form_key'),
            'name'          => $values['name'],
            'description'   => $values['description'],
            'status'        => isset($values['status']) ? $values['status'] : 'draft',
            'logged_in'     => isset($values['logged_in']) ? $values['logged_in'] : 0,
            'is_template'   => isset($values['is_template']) ? (int) $values['is_template'] : 0,
            'parent_form_id'=> isset($values['parent_form_id']) ? (int) $values['parent_form_id'] : 0,
            'editable'      => isset($values['editable']) ? (int) $values['editable'] : 0,
            'default_template' => isset($values['default_template']) ? (int) $values['default_template'] : 0,
            'created_at'    => isset($values['created_at']) ? $values['created_at'] : current_time('mysql', 1),
        );

        $options = array();

        $defaults = FrmFormsHelper::get_default_opts();
		foreach ( $defaults as $var => $default ) {
			$options[ $var ] = isset( $values['options'][ $var ] ) ? $values['options'][ $var ] : $default;
            unset( $var, $default );
        }

        $options['before_html'] = isset($values['options']['before_html']) ? $values['options']['before_html'] : FrmFormsHelper::get_default_html('before');
        $options['after_html'] = isset($values['options']['after_html']) ? $values['options']['after_html'] : FrmFormsHelper::get_default_html('after');
        $options['submit_html'] = isset($values['options']['submit_html']) ? $values['options']['submit_html'] : FrmFormsHelper::get_default_html('submit');

        $options = apply_filters('frm_form_options_before_update', $options, $values);
        $new_values['options'] = serialize($options);

        //if(isset($values['id']) && is_numeric($values['id']))
        //    $new_values['id'] = $values['id'];

        $wpdb->insert( $wpdb->prefix .'frm_forms', $new_values );

        $id = $wpdb->insert_id;
        return $id;
    }

    /**
     * @return int|boolean ID on success or false on failure
     */
    public static function duplicate( $id, $template = false, $copy_keys = false, $blog_id = false ) {
        global $wpdb;

        $values = self::getOne( $id, $blog_id );
        if ( ! $values ) {
            return false;
        }

        $new_key = $copy_keys ? $values->form_key : '';

        $new_values = array(
            'form_key'      => FrmAppHelper::get_unique_key($new_key, $wpdb->prefix .'frm_forms', 'form_key'),
            'name'          => $values->name,
            'description'   => $values->description,
            'status'        => $template ? 'published' : 'draft',
            'logged_in'     => $values->logged_in ? $values->logged_in : 0,
            'editable'      => $values->editable ? $values->editable : 0,
            'created_at'    => current_time('mysql', 1),
            'is_template'   => $template ? 1 : 0,
        );

        if ( $blog_id ) {
            $new_values['status'] = 'published';
            $new_options = maybe_unserialize($values->options);
            $new_options['email_to'] = get_option('admin_email');
            $new_options['copy'] = false;
            $new_values['options'] = $new_options;
        } else {
            $new_values['options'] = $values->options;
        }

        if ( is_array($new_values['options']) ) {
            $new_values['options'] = serialize($new_values['options']);
        }

        $query_results = $wpdb->insert( $wpdb->prefix .'frm_forms', $new_values );

        if ( $query_results ) {
            $form_id = $wpdb->insert_id;
            FrmField::duplicate($id, $form_id, $copy_keys, $blog_id);

            // update form settings after fields are created
            do_action('frm_after_duplicate_form', $form_id, $new_values, array( 'old_id' => $id));
            return $form_id;
        }

        return false;
    }

    public static function after_duplicate($form_id, $values) {
        $new_opts = $values['options'] = maybe_unserialize($values['options']);

        if ( isset($new_opts['success_msg']) ) {
            $new_opts['success_msg'] = FrmFieldsHelper::switch_field_ids($new_opts['success_msg']);
        }

        $new_opts = apply_filters('frm_after_duplicate_form_values', $new_opts, $form_id);

        if ( $new_opts != $values['options'] ) {
            global $wpdb;
            $wpdb->update($wpdb->prefix .'frm_forms', array( 'options' => maybe_serialize($new_opts)), array( 'id' => $form_id));
        }
    }

    /**
     * @return int|boolean
     */
    public static function update( $id, $values, $create_link = false ) {
        global $wpdb;

        if ( $create_link || isset($values['options']) || isset($values['item_meta']) || isset($values['field_options']) ) {
            $values['status'] = 'published';
        }

        if ( isset($values['form_key']) ) {
            $values['form_key'] = FrmAppHelper::get_unique_key($values['form_key'], $wpdb->prefix .'frm_forms', 'form_key', $id);
        }

        $form_fields = array( 'form_key', 'name', 'description', 'status' );

        $new_values = self::set_update_options( array(), $values);

        foreach ( $values as $value_key => $value ) {
            if ( in_array($value_key, $form_fields) ) {
                $new_values[$value_key] = $value;
            }
        }

        if ( isset( $values['new_status'] ) && ! empty( $values['new_status'] ) ) {
            $new_values['status'] = $values['new_status'];
        }

        if ( ! empty( $new_values ) ) {
            $query_results = $wpdb->update( $wpdb->prefix .'frm_forms', $new_values, array( 'id' => $id ) );
            if ( $query_results ) {
                wp_cache_delete( $id, 'frm_form');
            }
        } else {
            $query_results = true;
        }
        unset($new_values);

        $values = self::update_fields($id, $values);

        do_action('frm_update_form', $id, $values);
        do_action('frm_update_form_'. $id, $values);

        return $query_results;
    }

    /**
     * @return array
     */
    public static function set_update_options($new_values, $values) {
        if ( ! isset($values['options']) ) {
            return $new_values;
        }

        $options = array();

        $defaults = FrmFormsHelper::get_default_opts();
		foreach ( $defaults as $var => $default ) {
			$options[ $var ] = isset( $values['options'][ $var ] ) ? $values['options'][ $var ] : $default;
        }

        $options['custom_style'] = isset($values['options']['custom_style']) ? $values['options']['custom_style'] : 0;
        $options['before_html'] = isset($values['options']['before_html']) ? $values['options']['before_html'] : FrmFormsHelper::get_default_html('before');
        $options['after_html'] = isset($values['options']['after_html']) ? $values['options']['after_html'] : FrmFormsHelper::get_default_html('after');
        $options['submit_html'] = (isset($values['options']['submit_html']) && $values['options']['submit_html'] != '') ? $values['options']['submit_html'] : FrmFormsHelper::get_default_html('submit');

        $options = apply_filters('frm_form_options_before_update', $options, $values);
        $new_values['options'] = serialize($options);

        return $new_values;
    }


    /**
     * @return array
     */
    public static function update_fields($id, $values) {

        if ( ! isset($values['options']) && ! isset($values['item_meta']) && ! isset($values['field_options']) ) {
            return $values;
        }

        $all_fields = FrmField::get_all_for_form($id);
        if ( empty($all_fields) ) {
            return $values;
        }

        if ( ! isset($values['item_meta']) ) {
            $values['item_meta'] = array();
        }

        $field_array = array();
        $existing_keys = array_keys($values['item_meta']);
        foreach ( $all_fields as $fid ) {
            if ( ! in_array($fid->id, $existing_keys) && ( isset($values['frm_fields_submitted']) && in_array($fid->id, $values['frm_fields_submitted']) ) || isset($values['options']) ) {
                $values['item_meta'][$fid->id] = '';
            }
            $field_array[$fid->id] = $fid;
        }
        unset($all_fields);

        foreach ( $values['item_meta'] as $field_id => $default_value ) {
            if ( isset($field_array[$field_id]) ) {
                $field = $field_array[$field_id];
            } else {
                $field = FrmField::getOne($field_id);
            }

            if ( ! $field ) {
                continue;
            }

            if ( isset($values['options']) || isset($values['field_options']['custom_html_'. $field_id]) ) {
                //updating the settings page
                if ( isset($values['field_options']['custom_html_'. $field_id]) ) {
                    $field->field_options['custom_html'] = isset($values['field_options']['custom_html_'.$field_id]) ? $values['field_options']['custom_html_'.$field_id] : ( isset($field->field_options['custom_html']) ? $field->field_options['custom_html'] : FrmFieldsHelper::get_default_html($field->type));
                    $field->field_options = apply_filters('frm_update_form_field_options', $field->field_options, $field, $values);
                    FrmField::update($field_id, array( 'field_options' => $field->field_options));
                } else if ( $field->type == 'hidden' || $field->type == 'user_id' ) {
                    $prev_opts = $field->field_options;
                    $field->field_options = apply_filters('frm_update_form_field_options', $field->field_options, $field, $values);
                    if ( $prev_opts != $field->field_options ) {
                        FrmField::update($field_id, array( 'field_options' => $field->field_options));
                    }
                    unset($prev_opts);
                }
            }

            if ( ( isset($values['options']) || isset($values['field_options']['custom_html_'. $field_id]) ) && ! defined('WP_IMPORTING') ) {
                continue;
            }

            //updating the form
            foreach ( array( 'size', 'max', 'label', 'invalid', 'blank', 'classes') as $opt ) {
                $field->field_options[$opt] = isset($values['field_options'][$opt .'_'. $field_id]) ? trim($values['field_options'][$opt .'_'. $field_id]) : '';
            }

            $field->field_options['required_indicator'] = isset($values['field_options']['required_indicator_'. $field_id]) ? trim($values['field_options']['required_indicator_'. $field_id]) : '*';
            $field->field_options['separate_value'] = isset($values['field_options']['separate_value_'. $field_id]) ? trim($values['field_options']['separate_value_'. $field_id]) : 0;

            $field->field_options = apply_filters('frm_update_field_options', $field->field_options, $field, $values);
            $default_value = maybe_serialize($values['item_meta'][$field_id]);
            $field_key = isset($values['field_options']['field_key_'. $field_id]) ? $values['field_options']['field_key_'. $field_id] : $field->field_key;
            $required = isset($values['field_options']['required_'. $field_id]) ? $values['field_options']['required_'. $field_id] : false;
            $field_type = isset($values['field_options']['type_'. $field_id]) ? $values['field_options']['type_'. $field_id] : $field->type;
            $field_description = isset($values['field_options']['description_'. $field_id]) ? $values['field_options']['description_'. $field_id] : $field->description;

            FrmField::update($field_id, array(
                'field_key' => $field_key, 'type' => $field_type,
                'default_value' => $default_value, 'field_options' => $field->field_options,
                'description' => $field_description, 'required' => $required,
            ) );

            FrmField::delete_form_transient($field->form_id);
        }

        return $values;
    }

    /**
     * @param string $status
     * @return int|boolean
     */
    public static function set_status($id, $status) {
        if ( 'trash' == $status ) {
            return self::trash($id);
        }

        $statuses  = array( 'published', 'draft', 'trash');
        if ( ! in_array( $status, $statuses ) ) {
            return false;
        }

        global $wpdb;

        if ( is_array($id) ) {
			$where = array( 'id' => $id );
			FrmDb::get_where_clause_and_values( $where );
			array_unshift( $where['values'], $status );

			$query_results = $wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'frm_forms SET status = %s ' . $where['where'], $where['values'] ) );
        } else {
            $query_results = $wpdb->update( $wpdb->prefix .'frm_forms', array( 'status' => $status), array( 'id' => $id));
        }

        if ( $query_results ) {
            foreach ( (array) $id as $i ) {
                wp_cache_delete( $i, 'frm_form');
            }
        }

        return $query_results;
    }

    /**
     * @return int|boolean
     */
    public static function trash($id) {
        if ( ! EMPTY_TRASH_DAYS ) {
            return self::destroy( $id );
        }

        $form = self::getOne($id);
        if ( ! $form ) {
            return false;
        }

        $options = $form->options;
        $options['trash_time'] = time();

        global $wpdb;
        $query_results = $wpdb->update(
            $wpdb->prefix .'frm_forms',
            array( 'status' => 'trash', 'options' => serialize($options)),
            array( 'id' => $id)
        );

        if ( $query_results ) {
            wp_cache_delete( $id, 'frm_form');
        }

        return $query_results;
    }

    /**
     * @return int|boolean
     */
    public static function destroy( $id ){
        global $wpdb;

        $form = self::getOne($id);
        if ( ! $form ) {
            return false;
        }

        // Disconnect the entries from this form
        $entries = FrmDb::get_col( $wpdb->prefix .'frm_items', array( 'form_id' => $id) );
        foreach ( $entries as $entry_id ) {
            FrmEntry::destroy($entry_id);
            unset($entry_id);
        }

        // Disconnect the fields from this form
        $wpdb->query($wpdb->prepare('DELETE fi FROM '. $wpdb->prefix .'frm_fields AS fi LEFT JOIN '. $wpdb->prefix .'frm_forms fr ON (fi.form_id = fr.id) WHERE fi.form_id=%d OR parent_form_id=%d', $id, $id));

        $query_results = $wpdb->query($wpdb->prepare('DELETE FROM '. $wpdb->prefix .'frm_forms WHERE id=%d OR parent_form_id=%d', $id, $id));
        if ( $query_results ) {
            // Delete all form actions linked to this form
            $action_control = FrmFormActionsController::get_form_actions( 'email' );
            $action_control->destroy($id, 'all');

            do_action('frm_destroy_form', $id);
            do_action('frm_destroy_form_'. $id);
        }

        return $query_results;
    }

    /**
     * @return string form name
     */
    public static function &getName( $id ) {
        global $wpdb;

        $form = FrmAppHelper::check_cache($id, 'frm_form');
        if ( $form ) {
            $r = stripslashes($form->name);
            return $r;
        }

        $query_key = is_numeric( $id ) ? 'id' : 'form_key';
        $r = FrmDb::get_var( 'frm_forms', array( $query_key => $id ), 'name' );
        $r = stripslashes($r);

        return $r;
    }

    /**
     * @param string $key
     * @return int form id
     */
    public static function &getIdByKey( $key ){
        $id = FrmDb::get_var( 'frm_forms', array( 'form_key' => sanitize_title( $key ) ) );
        return $id;
    }

    /**
     * @param int $id
     * @return string form key
     */
    public static function &getKeyById($id){
        $id = (int) $id;
        $cache = FrmAppHelper::check_cache($id, 'frm_form');
        if ( $cache ) {
            return $cache->form_key;
        }

        $key = FrmDb::get_var( 'frm_forms', array( 'id' => $id ), 'form_key' );

        return $key;
    }

    /**
     * @return object form
     */
    public static function getOne( $id, $blog_id = false ) {
        global $wpdb;

        if ( $blog_id && is_multisite() ) {
            global $wpmuBaseTablePrefix;
            $prefix = $wpmuBaseTablePrefix ? $wpmuBaseTablePrefix . $blog_id .'_' : $wpdb->get_blog_prefix( $blog_id );

            $table_name = $prefix .'frm_forms';
        } else {
            $table_name = $wpdb->prefix .'frm_forms';
            $cache = wp_cache_get($id, 'frm_form');
            if ( $cache ) {
                if ( isset($cache->options) ) {
                    $cache->options = maybe_unserialize($cache->options);
                }

                return stripslashes_deep($cache);
            }
        }

        if ( is_numeric($id) ) {
            $where = array( 'id' => $id );
        } else {
            $where = array( 'form_key' => $id );
        }
        $results = FrmDb::get_row( $table_name, $where );

        if ( isset($results->options) ) {
            wp_cache_set($results->id, $results, 'frm_form');
            $results->options = maybe_unserialize($results->options);
        }
        return stripslashes_deep($results);
    }

    /**
     * @return array of objects
     */
    public static function getAll( $where = array(), $order_by = '', $limit = '' ){
        global $wpdb;

        if ( is_numeric($limit) ) {
            $limit = ' LIMIT '. $limit;
        }

        $query = 'SELECT * FROM ' . $wpdb->prefix .'frm_forms' . FrmAppHelper::prepend_and_or_where(' WHERE ', $where) . FrmAppHelper::esc_order($order_by) . FrmAppHelper::esc_limit($limit);

        if ( $limit == ' LIMIT 1' || $limit == 1 ) {
            if ( is_array($where) && ! empty($where) ) {
                $results = FrmDb::get_row($wpdb->prefix .'frm_forms', $where, '*', array( 'order_by' => $order_by) );
            } else {
				// the query has already been prepared if this is not an array
                $results = $wpdb->get_row($query);
            }

			if ( $results ) {
                wp_cache_set($results->id, $results, 'frm_form');
                $results->options = maybe_unserialize($results->options);
            }
        }else{
            if ( is_array($where) && ! empty($where) ) {
                $results = FrmDb::get_results( $wpdb->prefix .'frm_forms', $where, '*', compact('order_by', 'limit') );
            } else {
                $results = $wpdb->get_results($query);
            }

			if ( $results ) {
				foreach ( $results as $result ) {
					wp_cache_set( $result->id, $result, 'frm_form' );
					$result->options = maybe_unserialize( $result->options );
				}
            }
        }

        return stripslashes_deep($results);
    }

	/**
	 * Get all published forms
	 * @since 2.0
	 */
	public static function get_published_forms( $query = array(), $limit = 999, $inc_children = 'exclude' ) {
		$query['is_template'] = 0;
		$query['status'] = array( null, '', 'published' );
		if ( $inc_children == 'exclude' ) {
			$query['parent_form_id'] = array( null, 0 );
		}

		$forms = self::getAll( $query, 'name', $limit );
		return $forms;
	}

    /**
     * @return int count of forms
     */
    public static function &get_count( ) {
    	global $wpdb;

    	$cache_key = 'frm_form_counts';

    	$counts = wp_cache_get( $cache_key, 'counts' );
    	if ( false !== $counts ) {
    	    return $counts;
    	}

        $results = (array) FrmDb::get_results( 'frm_forms', array( 'or' => 1, 'parent_form_id' => null, 'parent_form_id <' => 0 ), 'status, is_template' );

    	$statuses = array( 'published', 'draft', 'template', 'trash');
    	$counts = array_fill_keys( $statuses, 0 );

    	foreach ( $results as $row ) {
            if ( 'trash' != $row->status ) {
    	        if ( $row->is_template ) {
					$counts['template']++;
    	        } else {
					$counts['published']++;
    	        }
    	    } else {
				$counts['trash']++;
        	}

    	    if ( 'draft' == $row->status ) {
				$counts['draft']++;
    	    }

    		unset($row);
    	}

    	$counts = (object) $counts;
    	wp_cache_set( $cache_key, $counts, 'counts' );

    	return $counts;
    }

    /**
     * @return array of errors
     */
    public static function validate( $values ){
        $errors = array();

        return apply_filters('frm_validate_form', $errors, $values);
    }

}
