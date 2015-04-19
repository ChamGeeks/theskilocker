<?php
if ( ! defined('ABSPATH') ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmFormsHelper {
    /**
     * If $form is numeric, get the form object
     * @param object|int $form
     */
    public static function maybe_get_form( &$form ) {
		if ( ! is_object( $form ) && ! is_array( $form ) && ! empty( $form ) ) {
            $form = FrmForm::getOne($form);
        }
    }

    public static function get_direct_link($key, $form = false ) {
        $target_url = esc_url(admin_url('admin-ajax.php') . '?action=frm_forms_preview&form='. $key);
        $target_url = apply_filters('frm_direct_link', $target_url, $key, $form);

        return $target_url;
    }

    public static function forms_dropdown( $field_name, $field_value = '', $args = array() ) {
        $defaults = array(
            'blank'     => true,
            'field_id'  => false,
            'onchange'  => false,
            'exclude'   => false,
            'class'     => '',
        );
        $args = wp_parse_args( $args, $defaults );

        if ( ! $args['field_id'] ) {
            $args['field_id'] = $field_name;
        }

		$query = array();
        if ( $args['exclude'] ) {
			$query['id !'] = $args['exclude'];
        }

        $where = apply_filters('frm_forms_dropdown', $query, $field_name);
		$forms = FrmForm::get_published_forms( $where );
        ?>
        <select name="<?php echo esc_attr( $field_name ); ?>" id="<?php echo esc_attr( $args['field_id'] ) ?>" <?php
		if ( $args['onchange'] ) {
			echo ' onchange="' . esc_attr( $args['onchange'] ) . '"';
		}
		if ( ! empty( $args['class'] ) ) {
			echo ' class="' . esc_attr( $args['class'] ) . '"';
		} ?>>
		<?php if ( $args['blank'] ) { ?>
			<option value=""><?php echo ( $args['blank'] == 1 ) ? ' ' : '- ' . esc_attr( $args['blank'] ) . ' -'; ?></option>
		<?php } ?>
		<?php foreach ( $forms as $form ) { ?>
			<option value="<?php echo esc_attr( $form->id ); ?>" <?php selected( $field_value, $form->id ); ?>><?php
				echo '' == $form->name ? __( '(no title)', 'formidable' ) : esc_attr( FrmAppHelper::truncate( $form->name, 33 ) );
			?></option>
		<?php } ?>
        </select>
        <?php
    }

    public static function form_switcher() {
		$where = apply_filters( 'frm_forms_dropdown', array(), '' );
		$forms = FrmForm::get_published_forms( $where );

        $args = array( 'id' => 0, 'form' => 0);
		if ( isset( $_GET['id'] ) && ! isset( $_GET['form'] ) ) {
			unset( $args['form'] );
		} else if ( isset( $_GET['form']) && ! isset( $_GET['id'] ) ) {
			unset( $args['id'] );
        }

        if ( FrmAppHelper::is_admin_page('formidable-entries') && isset($_GET['frm_action']) && in_array($_GET['frm_action'], array( 'edit', 'show', 'destroy_all')) ) {
            $args['frm_action'] = 'list';
            $args['form'] = 0;
		} else if ( FrmAppHelper::is_admin_page('formidable' ) && isset( $_GET['frm_action'] ) && in_array( $_GET['frm_action'], array( 'new', 'duplicate' ) ) ) {
            $args['frm_action'] = 'edit';
		} else if ( isset( $_GET['post'] ) ) {
            $args['form'] = 0;
            $base = admin_url('edit.php?post_type=frm_display');
        }

        ?>
		<li class="dropdown last" id="frm_bs_dropdown">
			<a href="#" id="frm-navbarDrop" class="frm-dropdown-toggle" data-toggle="dropdown"><?php _e( 'Switch Form', 'formidable' ) ?> <b class="caret"></b></a>
		    <ul class="frm-dropdown-menu frm-on-top" role="menu" aria-labelledby="frm-navbarDrop">
			<?php
			foreach ( $forms as $form ) {
				if ( isset( $args['id'] ) ) {
			        $args['id'] = $form->id;
				}
			    if ( isset( $args['form'] ) ) {
			        $args['form'] = $form->id;
				}
                ?>
				<li><a href="<?php echo isset($base) ? add_query_arg($args, $base) : add_query_arg($args); ?>" tabindex="-1"><?php echo empty($form->name) ? __( '(no title)') : FrmAppHelper::truncate($form->name, 33); ?></a></li>
			<?php
				unset( $form );
			} ?>
			</ul>
		</li>
        <?php
    }

    public static function get_sortable_classes($col, $sort_col, $sort_dir) {
        echo ($sort_col == $col) ? 'sorted' : 'sortable';
        echo ($sort_col == $col && $sort_dir == 'desc') ? ' asc' : ' desc';
    }

    /**
     * Used when a form is created
     */
    public static function setup_new_vars( $values = array() ) {
        global $wpdb;

        if ( ! empty( $values ) ) {
            $post_values = $values;
        } else {
            $values = array();
            $post_values = isset($_POST) ? $_POST : array();
        }

        foreach ( array( 'name' => '', 'description' => '') as $var => $default) {
			if ( ! isset( $values[ $var ] ) ) {
				$values[ $var ] = FrmAppHelper::get_param( $var, $default );
            }
        }

        $values['description'] = FrmAppHelper::use_wpautop($values['description']);

        foreach ( array( 'form_id' => '', 'logged_in' => '', 'editable' => '', 'default_template' => 0, 'is_template' => 0, 'status' => 'draft', 'parent_form_id' => 0) as $var => $default) {
            if ( ! isset( $values[ $var ] ) ) {
				$values[ $var ] = FrmAppHelper::get_param( $var, $default );
            }
        }

        if ( ! isset( $values['form_key'] ) ) {
            $values['form_key'] = ($post_values && isset($post_values['form_key'])) ? $post_values['form_key'] : FrmAppHelper::get_unique_key('', $wpdb->prefix .'frm_forms', 'form_key');
        }

        $values = self::fill_default_opts($values, false, $post_values);

        if ( $post_values && isset($post_values['options']['custom_style']) ) {
            $values['custom_style'] = $post_values['options']['custom_style'];
        } else {
            $frm_settings = FrmAppHelper::get_settings();
            $values['custom_style'] = ( $frm_settings->load_style != 'none' );
        }

        return apply_filters('frm_setup_new_form_vars', $values);
    }

    /**
     * Used when editing a form
     */
    public static function setup_edit_vars( $values, $record, $post_values = array() ) {
		if ( empty( $post_values ) ) {
			$post_values = stripslashes_deep( $_POST );
		}

        $values['form_key'] = isset($post_values['form_key']) ? $post_values['form_key'] : $record->form_key;
        $values['default_template'] = isset($post_values['default_template']) ? $post_values['default_template'] : $record->default_template;
        $values['is_template'] = isset($post_values['is_template']) ? $post_values['is_template'] : $record->is_template;
        $values['status'] = $record->status;

        $values = self::fill_default_opts($values, $record, $post_values);

        return apply_filters('frm_setup_edit_form_vars', $values);
    }

    public static function fill_default_opts($values, $record, $post_values) {

        $defaults = self::get_default_opts();
		foreach ( $defaults as $var => $default ) {
            if ( is_array($default) ) {
                if ( ! isset( $values[ $var ] ) ) {
					$values[ $var ] = ( $record && isset( $record->options[ $var ] ) ) ? $record->options[ $var ] : array();
                }

                foreach ( $default as $k => $v ) {
					$values[ $var ][ $k ] = ( $post_values && isset( $post_values[ $var ][ $k ] ) ) ? $post_values[ $var ][ $k ] : ( ( $record && isset( $record->options[ $var ] ) && isset( $record->options[ $var ][ $k ] ) ) ? $record->options[ $var ][ $k ] : $v);

                    if ( is_array( $v ) ) {
                        foreach ( $v as $k1 => $v1 ) {
							$values[ $var ][ $k ][ $k1 ] = ( $post_values && isset( $post_values[ $var ][ $k ][ $k1 ] ) ) ? $post_values[ $var ][ $k ][ $k1 ] : ( ( $record && isset( $record->options[ $var ] ) && isset( $record->options[ $var ][ $k ] ) && isset( $record->options[ $var ][ $k ][ $k1 ] ) ) ? $record->options[ $var ][ $k ][ $k1 ] : $v1 );
                            unset( $k1, $v1 );
                        }
                    }

                    unset($k, $v);
                }
            } else {
                $values[$var] = ($post_values && isset($post_values['options'][$var])) ? $post_values['options'][$var] : (($record && isset($record->options[$var])) ? $record->options[$var] : $default);
            }

            unset($var, $default);
        }

        return $values;
    }

    public static function get_default_opts() {
        $frm_settings = FrmAppHelper::get_settings();

        return array(
            'submit_value' => $frm_settings->submit_value, 'success_action' => 'message',
            'success_msg' => $frm_settings->success_msg, 'show_form' => 0, 'akismet' => '',
            'no_save' => 0, 'ajax_load' => 0, 'form_class' => '', 'custom_style' => 1,
            'before_html' => self::get_default_html('before'),
            'after_html' => '',
            'submit_html' => self::get_default_html('submit'),
        );
    }

    /**
     * @param string $loc
     */
    public static function get_default_html($loc) {
		if ( $loc == 'submit' ) {
            $sending = __( 'Sending', 'formidable' );
            $draft_link = self::get_draft_link();
            $img = '[frmurl]/images/ajax_loader.gif';
            $default_html = <<<SUBMIT_HTML
<div class="frm_submit">
[if back_button]<input type="button" value="[back_label]" name="frm_prev_page" formnovalidate="formnovalidate" class="frm_prev_page" [back_hook] />[/if back_button]
<input type="submit" value="[button_label]" [button_action] />
<img class="frm_ajax_loading" src="$img" alt="$sending"/>
$draft_link
</div>
SUBMIT_HTML;
		} else if ( $loc == 'before' ) {
            $default_html = <<<BEFORE_HTML
<legend class="frm_hidden">[form_name]</legend>
[if form_name]<h3>[form_name]</h3>[/if form_name]
[if form_description]<div class="frm_description">[form_description]</div>[/if form_description]
BEFORE_HTML;
		} else {
            $default_html = '';
        }

        return $default_html;
    }

    public static function get_draft_link() {
        $link = '[if save_draft]<a href="#" class="frm_save_draft" [draft_hook]>[draft_label]</a>[/if save_draft]';
        return $link;
    }

    public static function get_custom_submit($html, $form, $submit, $form_action, $values) {
        $button = self::replace_shortcodes($html, $form, $submit, $form_action, $values);
        if ( ! strpos($button, '[button_action]') ) {
            return;
        }

        $button_parts = explode('[button_action]', $button);
        echo $button_parts[0];
        //echo ' id="frm_submit_"';

        $classes = apply_filters('frm_submit_button_class', array(), $form);
        if ( ! empty($classes) ) {
            echo ' class="'. implode(' ', $classes) .'"';
        }

        do_action('frm_submit_button_action', $form, $form_action);
        echo $button_parts[1];
    }

    /**
     * Automatically add end section fields if they don't exist (2.0 migration)
     * @since 2.0
     *
     * @param boolean $reset_fields
     */
    public static function auto_add_end_section_fields( $form, $fields, &$reset_fields ) {
		if ( empty( $fields ) ) {
			return;
		}

		$end_section_values = apply_filters( 'frm_before_field_created', FrmFieldsHelper::setup_new_vars( 'end_divider', $form->id ) );
		$open = $prev_order = false;
		$add_order = 0;
        foreach ( $fields as $field ) {
			if ( $prev_order === $field->field_order ) {
				$add_order++;
			}

			if ( $add_order ) {
				$reset_fields = true;
				$field->field_order = $field->field_order + $add_order;
				FrmField::update( $field->id, array( 'field_order' => $field->field_order ) );
			}

            switch ( $field->type ) {
                case 'divider':
                    // create an end section if open
					self::maybe_create_end_section( $open, $reset_fields, $add_order, $end_section_values, $field, 'move' );

                    // mark it open for the next end section
                    $open = true;
                break;
                case 'break';
					self::maybe_create_end_section( $open, $reset_fields, $add_order, $end_section_values, $field, 'move' );
                break;
                case 'end_divider':
                    if ( ! $open ) {
                        // the section isn't open, so this is an extra field that needs to be removed
                        FrmField::destroy( $field->id );
                        $reset_fields = true;
                    }

                    // There is already an end section here, so there is no need to create one
                    $open = false;
            }
			$prev_order = $field->field_order;
        }

		self::maybe_create_end_section($open, $reset_fields, $add_order, $end_section_values, $field );
    }

	/**
	 * Create end section field if it doesn't exist. This is for migration from < 2.0
	 * Fix any ordering that may be messed up
	 */
	public static function maybe_create_end_section( &$open, &$reset_fields, &$add_order, $end_section_values, $field, $move = 'no' ) {
        if ( ! $open ) {
            return;
        }

		$end_section_values['field_order'] = $field->field_order + 1;

        FrmField::create( $end_section_values );

		if ( $move == 'move' ) {
			// bump the order of current field unless we're at the end of the form
			FrmField::update( $field->id, array( 'field_order' => $field->field_order + 2 ) );
		}

		$add_order += 2;
        $open = false;
        $reset_fields = true;
    }

    public static function replace_shortcodes( $html, $form, $title = false, $description = false, $values = array() ) {
        foreach ( array( 'form_name' => $title, 'form_description' => $description, 'entry_key' => true) as $code => $show) {
            if ( $code == 'form_name' ) {
                $replace_with = $form->name;
            } else if ( $code == 'form_description' ) {
                $replace_with = FrmAppHelper::use_wpautop($form->description);
            } else if ( $code == 'entry_key' && isset($_GET) && isset($_GET['entry']) ) {
                $replace_with = sanitize_text_field( $_GET['entry'] );
            } else {
                $replace_with = '';
            }

            FrmFieldsHelper::remove_inline_conditions( ( FrmAppHelper::is_true($show) && $replace_with != '' ), $code, $replace_with, $html );
        }

        //replace [form_key]
        $html = str_replace('[form_key]', $form->form_key, $html);

        //replace [frmurl]
        $html = str_replace('[frmurl]', FrmFieldsHelper::dynamic_default_values( 'frmurl' ), $html);

		if ( strpos( $html, '[button_label]' ) ) {
            add_filter('frm_submit_button', 'FrmFormsHelper::submit_button_label');
            $replace_with = apply_filters('frm_submit_button', $title, $form);
            $html = str_replace('[button_label]', $replace_with, $html);
        }

        $html = apply_filters('frm_form_replace_shortcodes', $html, $form, $values);

		if ( strpos( $html, '[if back_button]' ) ) {
			$html = preg_replace( '/(\[if\s+back_button\])(.*?)(\[\/if\s+back_button\])/mis', '', $html );
		}

		if ( strpos( $html, '[if save_draft]' ) ) {
			$html = preg_replace( '/(\[if\s+save_draft\])(.*?)(\[\/if\s+save_draft\])/mis', '', $html );
		}

        return $html;
    }

    public static function submit_button_label($submit) {
        if ( ! $submit || empty($submit) ) {
            $frm_settings = FrmAppHelper::get_settings();
            $submit = $frm_settings->submit_value;
        }

        return $submit;
    }

    public static function get_form_style_class($form = false) {
        $style = self::get_form_style($form);
        $class = ' with_frm_style';

        if ( empty($style) ) {
            if ( FrmAppHelper::is_admin_page('formidable-entries') ) {
                return $class;
            } else {
                return;
            }
        }

        //If submit button needs to be inline or centered
        if ( is_object($form) ) {
			$form = $form->options;
		}

		$submit_align = isset( $form['submit_align'] ) ? $form['submit_align'] : '';

		if ( $submit_align == 'inline' ) {
			$class .= ' frm_inline_form';
		} else if ( $submit_align == 'center' ) {
			$class .= ' frm_center_submit';
		}

        $class = apply_filters('frm_add_form_style_class', $class, $style);

        return $class;
    }

    /**
     * @param string|boolean $form
     *
     * @return boolean
     */
    public static function get_form_style( $form ) {
		$style = 1;
		if ( empty( $form ) || 'default' == 'form' ) {
			return $style;
		} else if ( is_object( $form ) && $form->parent_form_id ) {
			// get the parent form if this is a child
			$form = $form->parent_form_id;
		} else if ( is_array( $form ) && isset( $form['parent_form_id'] ) && $form['parent_form_id'] ) {
			$form = $form['parent_form_id'];
		} else if ( is_array( $form ) && isset( $form['custom_style'] ) ) {
			$style = $form['custom_style'];
		}

		if ( $form && is_string( $form ) ) {
			$form = FrmForm::getOne( $form );
		}

		$style = ( $form && is_object( $form ) && isset( $form->options['custom_style'] ) ) ? $form->options['custom_style'] : $style;

		return $style;
    }

    public static function form_loaded($form, $this_load, $global_load) {
        global $frm_vars;
        $small_form = new stdClass();
        foreach ( array( 'id', 'form_key', 'name' ) as $var ) {
            $small_form->{$var} = $form->{$var};
            unset($var);
        }

        $frm_vars['forms_loaded'][] = $small_form;

        if ( $this_load && empty($global_load) ) {
            $global_load = $frm_vars['load_css'] = true;
        }

        if ( ( ! isset($frm_vars['css_loaded']) || ! $frm_vars['css_loaded'] ) && $global_load ) {
            echo FrmAppController::footer_js('header');
            $frm_vars['css_loaded'] = true;
        }
    }

    public static function get_scroll_js($form_id) {
        ?><script type="text/javascript">jQuery(document).ready(function(){frmFrontForm.scrollMsg(<?php echo (int) $form_id ?>);})</script><?php
    }

    public static function edit_form_link($form_id) {
        if ( is_object($form_id) ) {
            $form = $form_id;
            $name = $form->name;
            $form_id = $form->id;
        } else {
            $name = FrmForm::getName($form_id);
        }

        if ( $form_id ) {
            $val = '<a href="'. admin_url('admin.php') .'?page=formidable&frm_action=edit&id='. $form_id .'">'. ( '' == $name ? __( '(no title)') : FrmAppHelper::truncate($name, 40) ) .'</a>';
	    } else {
	        $val = '';
	    }

	    return $val;
	}

    public static function delete_trash_link($id, $status, $length = 'long') {
        $link = '';
        $labels = array(
            'restore' => array(
                'long'  => __( 'Restore from Trash', 'formidable' ),
                'short' => __( 'Restore', 'formidable' ),
            ),
            'trash' => array(
                'long'  => __( 'Move to Trash', 'formidable' ),
                'short' => __( 'Trash', 'formidable' ),
            ),
            'delete' => array(
                'long'  => __( 'Delete Permanently', 'formidable' ),
                'short' => __( 'Delete', 'formidable' ),
            ),
        );

        $current_page = isset( $_REQUEST['form_type'] ) ? $_REQUEST['form_type'] : '';
        $base_url = '?page=formidable&form_type='. $current_page .'&id='. $id;
        if ( 'trash' == $status ) {
            $link = '<a href="'. esc_url(wp_nonce_url( $base_url .'&frm_action=untrash', 'untrash_form_' . $id )) .'" class="submitdelete deletion">'. $labels['restore'][$length] .'</a>';
        } else if ( current_user_can('frm_delete_forms') ) {
            if ( EMPTY_TRASH_DAYS ) {
                $link = '<a href="'. wp_nonce_url( $base_url .'&frm_action=trash', 'trash_form_' . $id ) .'" class="submitdelete deletion">'. $labels['trash'][$length] .'</a>';
            } else {
                $link = '<a href="'. wp_nonce_url( $base_url .'&frm_action=destroy', 'destroy_form_' . $id ) .'" class="submitdelete deletion" onclick="return confirm(\''. __( 'Are you sure you want to delete this form and all its entries?', 'formidable' ) .'\')">'. $labels['delete'][$length] .'</a>';
            }
        }

        return $link;
    }

    public static function status_nice_name($status) {
        $nice_names = array(
            'draft'     => __( 'Draft', 'formidable' ),
            'trash'     => __( 'Trash', 'formidable' ),
            'publish'   => __( 'Published', 'formidable' ),
        );

        if ( ! in_array($status, array_keys($nice_names)) ) {
            $status = 'publish';
        }

        $name = $nice_names[$status];

        return $name;
    }

    public static function get_params() {
        $values = array();
        foreach ( array( 'template' => 0, 'id' => '', 'paged' => 1, 'form' => '', 'search' => '', 'sort' => '', 'sdir' => '') as $var => $default ) {
            $values[$var] = FrmAppHelper::get_param($var, $default);
        }

        return $values;
    }

    /**
     * @param string $status
     *
     * @return int The number of forms changed
     */
    public static function change_form_status( $status ) {
        $available_status = array(
            'untrash'   => array(
                'permission' => 'frm_edit_forms', 'new_status' => 'published',
            ),
            'trash'     => array(
                'permission' => 'frm_delete_forms', 'new_status' => 'trash',
            ),
        );

        if ( ! isset($available_status[$status]) ) {
            return;
        }

        FrmAppHelper::permission_check($available_status[$status]['permission']);

        $params = self::get_params();

        //check nonce url
        check_admin_referer($status .'_form_' . $params['id']);

        $count = 0;
        if ( FrmForm::set_status( $params['id'], $available_status[$status]['new_status'] ) ) {
            $count++;
        }

        $available_status['untrash']['message'] = sprintf(_n( '%1$s form restored from the Trash.', '%1$s forms restored from the Trash.', $count, 'formidable' ), $count );
        $available_status['trash']['message'] = sprintf(_n( '%1$s form moved to the Trash. %2$sUndo%3$s', '%1$s forms moved to the Trash. %2$sUndo%3$s', $count, 'formidable' ), $count, '<a href="'. esc_url(wp_nonce_url( '?page=formidable&frm_action=untrash&form_type='. ( isset( $_REQUEST['form_type'] ) ? $_REQUEST['form_type'] : '' ) .'&id='. $params['id'], 'untrash_form_' . $params['id'] )) .'">', '</a>' );

        $message = $available_status[$status]['message'];

        FrmFormsController::display_forms_list($params, $message, 1);
    }
}
