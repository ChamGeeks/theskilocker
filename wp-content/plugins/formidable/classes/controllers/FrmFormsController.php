<?php

class FrmFormsController {

    public static function trigger_load_form_hooks() {
        FrmHooksController::trigger_load_hook( 'load_form_hooks' );
    }

    /**
     * The hooks only needed when a form is loaded
     */
    public static function load_form_hooks() {
        add_filter('frm_form_classes', 'FrmFormsController::form_classes' );
    }

    public static function menu() {
        add_submenu_page('formidable', 'Formidable | '. __( 'Forms', 'formidable' ), __( 'Forms', 'formidable' ), 'frm_view_forms', 'formidable', 'FrmFormsController::route' );

	    add_filter('get_user_option_managetoplevel_page_formidablecolumnshidden', 'FrmFormsController::hidden_columns' );

	    add_filter('manage_toplevel_page_formidable_columns', 'FrmFormsController::get_columns', 0 );
        add_filter('manage_toplevel_page_formidable_sortable_columns', 'FrmFormsController::get_sortable_columns' );
    }

    public static function head() {
        wp_enqueue_script('formidable-editinplace');

        if ( wp_is_mobile() ) {
    		wp_enqueue_script( 'jquery-touch-punch' );
    	}
    }

    public static function register_widgets() {
        require_once(FrmAppHelper::plugin_path() . '/classes/widgets/FrmShowForm.php');
        register_widget('FrmShowForm');
    }

    public static function list_form() {
        FrmAppHelper::permission_check('frm_view_forms');

        $params = FrmFormsHelper::get_params();
        $errors = self::process_bulk_form_actions( array());
        $errors = apply_filters('frm_admin_list_form_action', $errors);

        return self::display_forms_list($params, '', false, $errors);
    }

	public static function new_form( $values = array() ) {
        FrmAppHelper::permission_check('frm_edit_forms');

        global $frm_vars;

        $action = isset($_REQUEST['frm_action']) ? 'frm_action' : 'action';
		$action = empty( $values ) ? sanitize_title( FrmAppHelper::get_param( $action ) ) : $values[ $action ];

        if ($action == 'create') {
            return self::create($values);
        } else if ($action == 'new') {
            $frm_field_selection = FrmFieldsHelper::field_selection();
            $values = FrmFormsHelper::setup_new_vars($values);
            $id = FrmForm::create( $values );
            $form = FrmForm::getOne($id);

            // add default email notification
            $action_control = FrmFormActionsController::get_form_actions( 'email' );
            $action_control->create($form->id);

			$all_templates = FrmForm::getAll( array( 'is_template' => 1 ), 'name' );

            $values['id'] = $id;
            require(FrmAppHelper::plugin_path() .'/classes/views/frm-forms/new.php');
        }
    }

	public static function create( $values = array() ) {
        FrmAppHelper::permission_check('frm_edit_forms');

        global $frm_vars;
        if ( empty( $values ) ) {
            $values = $_POST;
        }

        //Set radio button and checkbox meta equal to "other" value
        if ( FrmAppHelper::pro_is_installed() ) {
            $values = FrmProEntry::mod_other_vals( $values, 'back' );
        }

		$id = isset($values['id']) ? absint( $values['id'] ) : absint( FrmAppHelper::get_param( 'id' ) );

        if ( ! current_user_can( 'frm_edit_forms' ) || ( $_POST && ( ! isset( $values['frm_save_form'] ) || ! wp_verify_nonce( $values['frm_save_form'], 'frm_save_form_nonce' ) ) ) ) {
            $frm_settings = FrmAppHelper::get_settings();
            $errors = array( 'form' => $frm_settings->admin_permission );
        } else {
            $errors = FrmForm::validate($values);
        }

        if ( count($errors) > 0 ) {
            $hide_preview = true;
            $frm_field_selection = FrmFieldsHelper::field_selection();
            $form = FrmForm::getOne( $id );
            $fields = FrmField::get_all_for_form($id);

            $values = FrmAppHelper::setup_edit_vars($form, 'forms', $fields, true);
			$all_templates = FrmForm::getAll( array( 'is_template' => 1 ), 'name' );

            require(FrmAppHelper::plugin_path() .'/classes/views/frm-forms/new.php');
        } else {
            FrmForm::update( $id, $values, true );
            die(FrmAppHelper::js_redirect(admin_url('admin.php?page=formidable&frm_action=settings&id='. $id)));
        }
    }

    public static function edit( $values = false ) {
        FrmAppHelper::permission_check('frm_edit_forms');

		$id = isset( $values['id'] ) ? absint( $values['id'] ) : absint( FrmAppHelper::get_param( 'id' ) );
        return self::get_edit_vars($id);
    }

    public static function settings( $id = false, $message = '' ) {
        FrmAppHelper::permission_check('frm_edit_forms');

        if ( ! $id || ! is_numeric($id) ) {
			$id = absint( FrmAppHelper::get_param( 'id' ) );
        }
        return self::get_settings_vars($id, '', $message);
    }

    public static function update_settings() {
        FrmAppHelper::permission_check('frm_edit_forms');

		$id = absint( FrmAppHelper::get_param( 'id' ) );

        $errors = FrmForm::validate($_POST);
        if ( count($errors) > 0 ) {
            return self::get_settings_vars($id, $errors);
        }

        do_action('frm_before_update_form_settings', $id);

		FrmForm::update( $id, $_POST );

        $message = __( 'Settings Successfully Updated', 'formidable' );
        return self::get_settings_vars($id, '', $message);
    }

    public static function edit_key() {
        check_ajax_referer( 'frm_ajax', 'nonce' );
        FrmAppHelper::permission_check('frm_edit_forms', 'hide');

        global $wpdb;
        $values = array( 'form_key' => trim($_POST['update_value']));

        FrmForm::update($_POST['form_id'], $values);
        $key = FrmForm::getKeyById($_POST['form_id']);
        echo stripslashes($key);
        wp_die();
    }

    public static function edit_description() {
        check_ajax_referer( 'frm_ajax', 'nonce' );
        FrmAppHelper::permission_check('frm_edit_forms', 'hide');

        FrmForm::update($_POST['form_id'], array( 'description' => $_POST['update_value']));
        $description = FrmAppHelper::use_wpautop(stripslashes($_POST['update_value']));
        echo $description;
        wp_die();
    }

	public static function update( $values = array() ) {
		if ( empty( $values ) ) {
            $values = $_POST;
        }

        //Set radio button and checkbox meta equal to "other" value
        if ( FrmAppHelper::pro_is_installed() ) {
            $values = FrmProEntry::mod_other_vals( $values, 'back' );
        }

        $errors = FrmForm::validate( $values );
        $permission_error = FrmAppHelper::permission_nonce_error( 'frm_edit_forms', 'frm_save_form', 'frm_save_form_nonce' );
        if ( $permission_error !== false ) {
            $errors['form'] = $permission_error;
        }

        $id = isset( $values['id'] ) ? (int) $values['id'] : (int) FrmAppHelper::get_param( 'id' );

		if ( count( $errors ) > 0 ) {
            return self::get_edit_vars( $id, $errors );
		} else {
            FrmForm::update( $id, $values );
            $message = __( 'Form was Successfully Updated', 'formidable' );
            if ( defined( 'DOING_AJAX' ) ) {
                die( $message );
            }
            return self::get_edit_vars( $id, '', $message );
        }
    }

    public static function bulk_create_template( $ids ) {
        FrmAppHelper::permission_check( 'frm_edit_forms' );

        foreach ( $ids as $id ) {
            FrmForm::duplicate( $id, true, true );
        }

        return __( 'Form template was Successfully Created', 'formidable' );
    }

	/**
	 * Redirect to the url for creating from a template
	 * Also delete the current form
	 * @since 2.0
	 */
	public static function _create_from_template() {
		check_ajax_referer( 'frm_ajax', 'nonce' );

		$current_form = (int) FrmAppHelper::get_param( 'this_form' );
		$template_id = (int) FrmAppHelper::get_param( 'id' );

		if ( $current_form ) {
			FrmForm::destroy( $current_form );
		}

		echo admin_url( 'admin.php?page=formidable&action=duplicate&id=' . $template_id );
		wp_die();
	}

    public static function duplicate() {
        FrmAppHelper::permission_check('frm_edit_forms');

        $params = FrmFormsHelper::get_params();
        $form = FrmForm::duplicate( $params['id'], $params['template'], true );
        $message = ($params['template']) ? __( 'Form template was Successfully Created', 'formidable' ) : __( 'Form was Successfully Copied', 'formidable' );
        if ( $form ) {
            return self::get_edit_vars($form, '', $message, true);
        } else {
            return self::display_forms_list($params, __( 'There was a problem creating new template.', 'formidable' ));
        }
    }

    public static function page_preview() {
        $params = FrmFormsHelper::get_params();
        if ( ! $params['form'] ) {
            return;
        }

        $form = FrmForm::getOne( $params['form'] );
        if ( ! $form ) {
            return;
        }
        return self::show_form( $form->id, '', true, true );
    }

    public static function preview() {
        do_action( 'frm_wp' );

        global $frm_vars;
        $frm_vars['preview'] = true;

        if ( ! defined( 'ABSPATH' ) && ! defined( 'XMLRPC_REQUEST' ) ) {
            global $wp;
            $root = dirname( dirname( dirname( dirname( __FILE__ ) ) ) );
            include_once( $root.'/wp-config.php' );
            $wp->init();
            $wp->register_globals();
        }

        if ( FrmAppHelper::pro_is_installed() ) {
            FrmProEntriesController::register_scripts();
        }

		header( 'Content-Type: text/html; charset='. get_option( 'blog_charset' ) );

        $plugin     = FrmAppHelper::get_param('plugin');
        $controller = FrmAppHelper::get_param('controller');
        $key = (isset($_GET['form']) ? $_GET['form'] : (isset($_POST['form']) ? $_POST['form'] : ''));
        $form = FrmForm::getAll( array( 'form_key' => $key), '', 1);
        if ( empty($form) ) {
			$form = FrmForm::getAll( array(), '', 1 );
        }

        require(FrmAppHelper::plugin_path() .'/classes/views/frm-entries/direct.php');
        wp_die();
    }

    public static function untrash() {
        FrmFormsHelper::change_form_status('untrash');
    }

    public static function bulk_untrash($ids) {
        FrmAppHelper::permission_check('frm_edit_forms');

        $count = FrmForm::set_status( $ids, 'published' );

        $message = sprintf(_n( '%1$s form restored from the Trash.', '%1$s forms restored from the Trash.', $count, 'formidable' ), 1 );
        return $message;
    }

    public static function trash() {
        FrmFormsHelper::change_form_status('trash');
    }

    public static function bulk_trash($ids) {
        FrmAppHelper::permission_check('frm_delete_forms');

        $count = 0;
        foreach ( $ids as $id ) {
            if ( FrmForm::trash( $id ) ) {
                $count++;
            }
        }

        $current_page = isset( $_REQUEST['form_type'] ) ? $_REQUEST['form_type'] : '';
        $message = sprintf(_n( '%1$s form moved to the Trash. %2$sUndo%3$s', '%1$s forms moved to the Trash. %2$sUndo%3$s', $count, 'formidable' ), $count, '<a href="'. esc_url(wp_nonce_url( '?page=formidable&frm_action=list&action=bulk_untrash&form_type='. $current_page .'&item-action[]='. implode('item-action[]=', $ids), 'bulk-toplevel_page_formidable' )) .'">', '</a>' );

        return $message;
    }

    public static function destroy() {
        FrmAppHelper::permission_check('frm_delete_forms');

        $params = FrmFormsHelper::get_params();

        //check nonce url
        check_admin_referer('destroy_form_' . $params['id']);

        $count = 0;
        if ( FrmForm::destroy( $params['id'] ) ) {
            $count++;
        }

        $message = sprintf(_n( '%1$s form permanently deleted.', '%1$s forms permanently deleted.', $count, 'formidable' ), $count);

        self::display_forms_list($params, $message, 1);
    }

    public static function bulk_destroy($ids) {
        FrmAppHelper::permission_check('frm_delete_forms');

        $count = 0;
        foreach ( $ids as $id ) {
            $d = FrmForm::destroy( $id );
            if ( $d ) {
                $count++;
            }
        }

        $message = sprintf(_n( '%1$s form permanently deleted.', '%1$s forms permanently deleted.', $count, 'formidable' ), $count);

        return $message;
    }

    private static function delete_all() {
        //check nonce url
        $permission_error = FrmAppHelper::permission_nonce_error('frm_delete_forms', '_wpnonce', 'bulk-toplevel_page_formidable');
        if ( $permission_error !== false ) {
			self::display_forms_list( array(), '', 1, array( $permission_error ) );
            return;
        }

        $count = self::scheduled_delete(time());
        $message = sprintf(_n( '%1$s form permanently deleted.', '%1$s forms permanently deleted.', $count, 'formidable' ), $count);

		self::display_forms_list( array(), $message, 1 );
    }

    /**
     * Delete trashed forms based on how long they have been trashed
     * @return int The number of forms deleted
     */
    public static function scheduled_delete($delete_timestamp = '') {
        global $wpdb;

        $trash_forms = FrmDb::get_results($wpdb->prefix .'frm_forms', array( 'status' => 'trash'), 'id, options' );

        if ( ! $trash_forms ) {
            return;
        }

        if ( empty($delete_timestamp) ) {
            $delete_timestamp = time() - ( DAY_IN_SECONDS * EMPTY_TRASH_DAYS );
        }

        $count = 0;
        foreach ( $trash_forms as $form ) {
            $form->options = maybe_unserialize($form->options);
            if ( ! isset( $form->options['trash_time'] ) || $form->options['trash_time'] < $delete_timestamp ) {
                FrmForm::destroy($form->id);
                $count++;
            }

            unset($form);
        }
        return $count;
    }

    public static function insert_form_button($content) {
        if ( current_user_can('frm_view_forms') ) {
            $content .= '<a href="#TB_inline?width=50&height=50&inlineId=frm_insert_form" class="thickbox button add_media frm_insert_form" title="' . __( 'Add forms and content', 'formidable' ) . '"><span class="frm-buttons-icon wp-media-buttons-icon"></span> Formidable</a>';

        }
        return $content;
    }

    public static function insert_form_popup() {
        $page = basename($_SERVER['PHP_SELF']);
        if ( ! in_array($page, array( 'post.php', 'page.php', 'page-new.php', 'post-new.php') ) ) {
            return;
        }

        FrmAppHelper::load_admin_wide_js();

        $shortcodes = array(
            'formidable' => array( 'name' => __( 'Form', 'formidable' ), 'label' => __( 'Insert a Form', 'formidable' )),
        );

        $shortcodes = apply_filters('frm_popup_shortcodes', $shortcodes);

        include(FrmAppHelper::plugin_path() .'/classes/views/frm-forms/insert_form_popup.php');
    }

    public static function get_shortcode_opts() {
        check_ajax_referer( 'frm_ajax', 'nonce' );

        $shortcode = sanitize_text_field( $_POST['shortcode'] );
        if ( empty($shortcode) ) {
            wp_die();
        }

        echo '<div id="sc-opts-'. esc_attr( $shortcode ) .'" class="frm_shortcode_option">';
        echo '<input type="radio" name="frmsc" value="'. esc_attr($shortcode) .'" id="sc-'. esc_attr($shortcode) .'" class="frm_hidden" />';

        $form_id = '';
        $opts = array();
        switch( $shortcode ) {
            case 'formidable':
                $opts = array(
					'form_id'       => 'id',
                    //'key' => ',
                    'title'         => array( 'val' => 1, 'label' => __( 'Display form title', 'formidable' )),
                    'description'   => array( 'val' => 1, 'label' => __( 'Display form description', 'formidable' )),
                    'minimize'      => array( 'val' => 1, 'label' => __( 'Minimize form HTML', 'formidable' )),
                );
            break;
        }
        $opts = apply_filters('frm_sc_popup_opts', $opts, $shortcode);

		if ( isset( $opts['form_id'] ) && is_string( $opts['form_id'] ) ) {
			// allow other shortcodes to use the required form id option
			$form_id = $opts['form_id'];
			unset( $opts['form_id'] );
		}

        include(FrmAppHelper::plugin_path() .'/classes/views/frm-forms/shortcode_opts.php');

        echo '</div>';

        wp_die();
    }

    public static function display_forms_list( $params = array(), $message = '', $current_page_ov = false, $errors = array() ) {
        FrmAppHelper::permission_check( 'frm_view_forms' );

        global $wpdb, $frm_vars;

		if ( empty( $params ) ) {
            $params = FrmFormsHelper::get_params();
        }

        $wp_list_table = new FrmFormsListHelper( compact( 'params' ) );

        $pagenum = $wp_list_table->get_pagenum();

        $wp_list_table->prepare_items();

        $total_pages = $wp_list_table->get_pagination_arg( 'total_pages' );
        if ( $pagenum > $total_pages && $total_pages > 0 ) {
            wp_redirect( add_query_arg( 'paged', $total_pages ) );
            die();
        }

        require(FrmAppHelper::plugin_path() .'/classes/views/frm-forms/list.php');
    }

    public static function get_columns($columns) {
	    $columns['cb'] = '<input type="checkbox" />';
	    $columns['id'] = 'ID';

        $type = isset( $_REQUEST['form_type'] ) ? $_REQUEST['form_type'] : 'published';

        if ( 'template' == $type ) {
            $columns['name']        = __( 'Template Name', 'formidable' );
            $columns['type']        = __( 'Type', 'formidable' );
            $columns['form_key']    = __( 'Key', 'formidable' );
        } else {
            $columns['name']        = __( 'Form Title', 'formidable' );
            $columns['entries']     = __( 'Entries', 'formidable' );
            $columns['form_key']    = __( 'Key', 'formidable' );
            $columns['shortcode']   = __( 'Shortcodes', 'formidable' );
        }

        $columns['created_at'] = __( 'Date', 'formidable' );

        add_screen_option( 'per_page', array( 'label' => __( 'Forms', 'formidable' ), 'default' => 20, 'option' => 'formidable_page_formidable_per_page' ) );

        return $columns;
	}

	public static function get_sortable_columns() {
		return array(
		    'id'            => 'id',
			'name'          => 'name',
			'description'   => 'description',
			'form_key'      => 'form_key',
			'created_at'    => 'created_at',
		);
	}

	public static function hidden_columns( $result ) {
        $return = false;
        foreach ( (array) $result as $r ) {
            if ( ! empty( $r ) ) {
                $return = true;
                break;
            }
        }

        if ( $return ) {
            return $result;
		}

        $type = isset( $_REQUEST['form_type'] ) ? $_REQUEST['form_type'] : '';

        $result[] = 'created_at';
        if ( $type == 'template' ) {
            $result[] = 'id';
            $result[] = 'form_key';
        }

        return $result;
    }

	public static function save_per_page( $save, $option, $value ) {
        if ( $option == 'formidable_page_formidable_per_page' ) {
            $save = (int) $value;
        }
        return $save;
    }

    private static function get_edit_vars( $id, $errors = '', $message = '', $create_link = false ) {
        global $frm_vars;

        $form = FrmForm::getOne( $id );
        if ( ! $form ) {
            wp_die( __( 'You are trying to edit a form that does not exist.', 'formidable' ) );
        }

        if ( $form->parent_form_id ) {
            wp_die( sprintf(__( 'You are trying to edit a child form. Please edit from %1$shere%2$s', 'formidable' ), '<a href="'. esc_url(admin_url('admin.php') .'?page=formidable&frm_action=edit&id='. $form->parent_form_id) .'">', '</a>' ));
        }

        $frm_field_selection = FrmFieldsHelper::field_selection();
        $fields = FrmField::get_all_for_form($form->id);

        // Automatically add end section fields if they don't exist (2.0 migration)
        $reset_fields = false;
        FrmFormsHelper::auto_add_end_section_fields( $form, $fields, $reset_fields );

        if ( $reset_fields ) {
            $fields = FrmField::get_all_for_form( $form->id, '', 'exclude' );
        }

        unset($end_section_values, $last_order, $open, $reset_fields);

        $values = FrmAppHelper::setup_edit_vars($form, 'forms', $fields, true);

        $edit_message = __( 'Form was Successfully Updated', 'formidable' );
        if ( $form->is_template && $message == $edit_message ) {
            $message = __( 'Template was Successfully Updated', 'formidable' );
        }

		$all_templates = FrmForm::getAll( array( 'is_template' => 1 ), 'name' );

        if ( $form->default_template ) {
            wp_die(__( 'That template cannot be edited', 'formidable' ));
        } else if ( defined('DOING_AJAX') ) {
            wp_die();
        } else if ( $create_link ) {
            require(FrmAppHelper::plugin_path() .'/classes/views/frm-forms/new.php');
        } else {
            require(FrmAppHelper::plugin_path() .'/classes/views/frm-forms/edit.php');
        }
    }

    public static function get_settings_vars( $id, $errors = '', $message = '' ) {
		FrmAppHelper::permission_check( 'frm_edit_forms' );

        global $frm_vars;

        $form = FrmForm::getOne( $id );

        $fields = FrmField::get_all_for_form($id);
        $values = FrmAppHelper::setup_edit_vars($form, 'forms', $fields, true);

        if ( isset($values['default_template']) && $values['default_template'] ) {
            wp_die(__( 'That template cannot be edited', 'formidable' ));
        }

        $action_controls = FrmFormActionsController::get_form_actions();

        $sections = apply_filters('frm_add_form_settings_section', array(), $values);
        $pro_feature = FrmAppHelper::pro_is_installed() ? '' : ' class="pro_feature"';

        $styles = apply_filters('frm_get_style_opts', array());

        require(FrmAppHelper::plugin_path() .'/classes/views/frm-forms/settings.php');
    }

    public static function mb_tags_box( $form_id, $class = '' ) {
        $fields = FrmField::get_all_for_form($form_id, '', 'include');
        $linked_forms = array();
        $col = 'one';
        $settings_tab = FrmAppHelper::is_admin_page('formidable' ) ? true : false;

        $cond_shortcodes = apply_filters('frm_conditional_shortcodes', array());

        $adv_shortcodes = array(
            'sep=", "' => array( 'label' => __( 'Separator', 'formidable' ), 'title' => __( 'Use a different separator for checkbox fields', 'formidable' ) ),
            'format="d-m-Y"' => __( 'Date Format', 'formidable' ),
            'show="field_label"' => __( 'Field Label', 'formidable' ),
            'wpautop=0' => array( 'label' => __( 'No Auto P', 'formidable' ), 'title' => __( 'Do not automatically add any paragraphs or line breaks', 'formidable' )),
        );
        $adv_shortcodes = apply_filters('frm_advanced_shortcodes', $adv_shortcodes);

        // __( 'Leave blank instead of defaulting to User Login', 'formidable' ) : blank=1

        $user_fields = apply_filters('frm_user_shortcodes', array());

        include(FrmAppHelper::plugin_path() .'/classes/views/shared/mb_adv_info.php');
    }

    // Insert the form class setting into the form
    public static function form_classes($form) {
        if ( isset($form->options['form_class']) ) {
            echo esc_attr($form->options['form_class']);
        }
    }

    public static function get_email_html() {
        check_ajax_referer( 'frm_ajax', 'nonce' );
	    echo FrmEntriesController::show_entry_shortcode( array(
	        'form_id'       => $_POST['form_id'],
	        'default_email' => true,
	        'plain_text'    => $_POST['plain_text'],
	    ) );
	    wp_die();
	}

    public static function filter_content( $content, $form, $entry = false ) {
        if ( ! $entry || ! is_object( $entry ) ) {
            if ( ! $entry || ! is_numeric( $entry ) ) {
                $entry = ( $_POST && isset( $_POST['id'] ) ) ? $_POST['id'] : false;
            }

            FrmEntriesHelper::maybe_get_entry( $entry );
        }

        if ( ! $entry ) {
            return $content;
        }

        if ( is_object( $form ) ) {
            $form = $form->id;
        }

        $shortcodes = FrmFieldsHelper::get_shortcodes( $content, $form );
        $content = apply_filters( 'frm_replace_content_shortcodes', $content, $entry, $shortcodes );

        return $content;
    }

    public static function replace_content_shortcodes( $content, $entry, $shortcodes ) {
        return FrmFieldsHelper::replace_content_shortcodes( $content, $entry, $shortcodes );
    }

    public static function process_bulk_form_actions( $errors ) {
        if ( ! $_REQUEST ) {
            return $errors;
        }

        $bulkaction = FrmAppHelper::get_param( 'action' );
        if ( $bulkaction == -1 ) {
            $bulkaction = FrmAppHelper::get_param( 'action2' );
        }

        if ( ! empty( $bulkaction ) && strpos( $bulkaction, 'bulk_' ) === 0 ) {
            FrmAppHelper::remove_get_action();

            $bulkaction = str_replace( 'bulk_', '', $bulkaction );
        }

        $ids = FrmAppHelper::get_param( 'item-action', '' );
        if ( empty( $ids ) ) {
            $errors[] = __( 'No forms were specified', 'formidable' );
            return $errors;
        }

        $permission_error = FrmAppHelper::permission_nonce_error( '', '_wpnonce', 'bulk-toplevel_page_formidable' );
        if ( $permission_error !== false ) {
            $errors[] = $permission_error;
            return $errors;
        }

        if ( ! is_array( $ids ) ) {
            $ids = explode( ',', $ids );
        }

        switch ( $bulkaction ) {
            case 'delete':
                $message = self::bulk_destroy( $ids );
            break;
            case 'trash':
                $message = self::bulk_trash( $ids );
            break;
            case 'untrash':
                $message = self::bulk_untrash( $ids );
            break;
            case 'create_template':
                $message = self::bulk_create_template( $ids );
            break;
        }

        if ( isset( $message ) && ! empty( $message ) ) {
            echo '<div id="message" class="updated frm_msg_padding">'.$message.'</div>';
        }

        return $errors;
    }

    public static function add_default_templates( $path, $default = true, $template = true ) {
        _deprecated_function( __FUNCTION__, '1.07.05', 'FrmXMLController::add_default_templates()' );

        $path = untrailingslashit(trim($path));
        $templates = glob( $path .'/*.php' );

        for($i = count($templates) - 1; $i >= 0; $i--) {
            $filename = str_replace( '.php', '', str_replace( $path.'/', '', $templates[ $i ] ) );
            $template_query = array( 'form_key' => $filename);
            if ( $template ) {
                $template_query['is_template'] = 1;
            }
            if ( $default ) {
                $template_query['default_template'] = 1;
            }
			$form = FrmForm::getAll( $template_query, '', 1 );

            $values = FrmFormsHelper::setup_new_vars();
            $values['form_key'] = $filename;
            $values['is_template'] = $template;
            $values['status'] = 'published';
            if ( $default ) {
                $values['default_template'] = 1;
            }

            include( $templates[ $i ] );

            //get updated form
            if ( isset($form) && ! empty($form) ) {
                $old_id = $form->id;
                $form = FrmForm::getOne($form->id);
            } else {
                $old_id = false;
				$form = FrmForm::getAll( $template_query, '', 1 );
            }

            if ( $form ) {
                do_action('frm_after_duplicate_form', $form->id, (array) $form, array( 'old_id' => $old_id));
            }
        }
    }

    public static function route() {
        $action = isset($_REQUEST['frm_action']) ? 'frm_action' : 'action';
        $vars = array();
		if ( isset( $_POST['frm_compact_fields'] ) ) {
			FrmAppHelper::permission_check( 'frm_edit_forms' );

            $json_vars = htmlspecialchars_decode(nl2br(stripslashes(str_replace('&quot;', '\\\"', $_POST['frm_compact_fields'] ))));
            $json_vars = json_decode($json_vars, true);
            if ( empty($json_vars) ) {
                // json decoding failed so we should return an error message
                $action = FrmAppHelper::get_param($action);
                if ( 'edit' == $action ) {
                    $action = 'update';
                }

                add_filter('frm_validate_form', 'FrmFormsController::json_error');
            } else {
                $vars = FrmAppHelper::json_to_array($json_vars);
                $action = $vars[ $action ];
				$_REQUEST = array_merge( $_REQUEST, $vars );
				unset( $_REQUEST['frm_compact_fields'] );
            }
        } else {
            $action = FrmAppHelper::get_param($action);
    		if ( isset( $_REQUEST['delete_all'] ) ) {
                // override the action for this page
    			$action = 'delete_all';
            }
        }

        add_action('frm_load_form_hooks', 'FrmFormsController::trigger_load_form_hooks');
        FrmAppHelper::trigger_hook_load( 'form' );

        switch ( $action ) {
            case 'new':
                return self::new_form($vars);
            case 'create':
            case 'edit':
            case 'update':
            case 'duplicate':
            case 'trash':
            case 'untrash':
            case 'destroy':
            case 'delete_all':
            case 'settings':
            case 'update_settings':
				return self::$action( $vars );
            default:
                do_action('frm_form_action_'. $action);
                if ( apply_filters('frm_form_stop_action_'. $action, false) ) {
                    return;
                }

                $action = FrmAppHelper::get_param('action');
                if ( $action == -1 ) {
                    $action = FrmAppHelper::get_param('action2');
                }

                if ( strpos($action, 'bulk_') === 0 ) {
                    FrmAppHelper::remove_get_action();
                    return self::list_form();
                }

                return self::display_forms_list();
        }
    }

    public static function json_error( $errors ) {
        $errors['json'] = __( 'Abnormal HTML characters prevented your form from saving correctly', 'formidable' );
        return $errors;
    }


    /* FRONT-END FORMS */
    public static function admin_bar_css() {
        FrmAppController::load_wp_admin_style();
    }

    public static function admin_bar_configure() {
        if ( is_admin() || !current_user_can('frm_edit_forms') ) {
            return;
        }

        global $frm_vars;
        if ( empty($frm_vars['forms_loaded']) ) {
            return;
        }

        $actions = array();
        foreach ( $frm_vars['forms_loaded'] as $form ) {
            if ( is_object($form) ) {
                $actions[ $form->id ] = $form->name;
            }
            unset($form);
        }

        if ( empty($actions) ) {
            return;
        }

        asort($actions);

        global $wp_admin_bar;

        if ( count($actions) == 1 ) {
            $wp_admin_bar->add_menu( array(
                'title' => 'Edit Form',
                'href'  => admin_url('admin.php?page=formidable&frm_action=edit&id='. current( array_keys( $actions ) )),
                'id'    => 'frm-forms',
            ) );
        } else {
            $wp_admin_bar->add_menu( array(
        		'id'    => 'frm-forms',
        		'title' => '<span class="ab-icon"></span><span class="ab-label">' . __( 'Edit Forms', 'formidable' ) . '</span>',
        		'href'  => admin_url( 'admin.php?page=formidable&frm_action=edit&id='. current( array_keys( $actions ) ) ),
        		'meta'  => array(
        			'title' => __( 'Edit Forms', 'formidable' ),
        		),
        	) );

        	foreach ( $actions as $form_id => $name ) {

        		$wp_admin_bar->add_menu( array(
        			'parent'    => 'frm-forms',
        			'id'        => 'edit_form_'. $form_id,
        			'title'     => empty($name) ? __( '(no title)') : $name,
        			'href'      => admin_url( 'admin.php?page=formidable&frm_action=edit&id='. $form_id )
        		) );
        	}
        }
    }

    //formidable shortcode
    public static function get_form_shortcode($atts) {
        global $frm_vars;
        if ( isset($frm_vars['skip_shortcode']) && $frm_vars['skip_shortcode'] ) {
            $sc = '[formidable';
            foreach ( $atts as $k => $v ) {
                $sc .= ' '. $k .'="'. $v .'"';
            }
            return $sc .']';
        }

        $shortcode_atts = shortcode_atts( array(
            'id' => '', 'key' => '', 'title' => false, 'description' => false,
            'readonly' => false, 'entry_id' => false, 'fields' => array(),
            'exclude_fields' => array(), 'minimize' => false,
        ), $atts);
        do_action('formidable_shortcode_atts', $shortcode_atts, $atts);

        return self::show_form(
            $shortcode_atts['id'], $shortcode_atts['key'], $shortcode_atts['title'],
            $shortcode_atts['description'], $atts
        );
    }

    public static function show_form( $id = '', $key = '', $title = false, $description = false, $atts = array() ) {
        if ( empty( $id ) ) {
            $id = $key;
        }

        // no form id or key set
        if ( empty( $id ) ) {
            return __( 'Please select a valid form', 'formidable' );
        }

        $form = FrmForm::getOne( $id );
        if ( ! $form || $form->parent_form_id ) {
            return __( 'Please select a valid form', 'formidable' );
        }

        add_action( 'frm_load_form_hooks', 'FrmFormsController::trigger_load_form_hooks' );
        FrmAppHelper::trigger_hook_load( 'form', $form );

        $form = apply_filters( 'frm_pre_display_form', $form );

        $frm_settings = FrmAppHelper::get_settings();

        // don't show a draft form on a page
		global $post;
        if ( $form->status == 'draft' && current_user_can( 'frm_edit_forms' ) && ( ! $post || $post->ID != $frm_settings->preview_page_id ) && ! FrmAppHelper::is_preview_page() ) {
            return __( 'Please select a valid form', 'formidable' );
        }

        // don't show the form if user should be logged in
        if ( $form->logged_in && ! is_user_logged_in() ) {
            return do_shortcode( $frm_settings->login_msg );
        }

        // don't show the form if user doesn't have permission
        if ( $form->logged_in && get_current_user_id() && isset( $form->options['logged_in_role'] ) && $form->options['logged_in_role'] != '' && ! FrmAppHelper::user_has_permission( $form->options['logged_in_role'] ) ) {
            return do_shortcode( $frm_settings->login_msg );
        }

        $form = self::get_form( $form, $title, $description, $atts );

        // check for external shortcodes
        $form = do_shortcode( $form );

        return $form;
    }

    public static function get_form( $form, $title, $description, $atts = array() ) {
        ob_start();

        self::get_form_contents( $form, $title, $description, $atts );
        FrmEntriesHelper::enqueue_scripts( FrmEntriesController::get_params( $form ) );

        $contents = ob_get_contents();
        ob_end_clean();

        // check if minimizing is turned on
		if ( isset( $atts['minimize'] ) && ! empty( $atts['minimize'] ) ) {
			$contents = str_replace( array( "\r\n", "\r", "\n", "\t", '    ' ), '', $contents );
        }

        return $contents;
    }

    public static function get_form_contents($form, $title, $description, $atts) {
        global $frm_vars;

        $frm_settings = FrmAppHelper::get_settings();

        $submit = isset($form->options['submit_value']) ? $form->options['submit_value'] : $frm_settings->submit_value;

        $user_ID = get_current_user_id();

        $params = FrmEntriesController::get_params($form);

        $message = $errors = '';

        if ( $params['posted_form_id'] == $form->id && $_POST ) {
            $errors = isset( $frm_vars['created_entries'][ $form->id ] ) ? $frm_vars['created_entries'][ $form->id ]['errors'] : array();
        }

        $fields = FrmFieldsHelper::get_form_fields( $form->id, ( isset( $errors ) && ! empty( $errors ) ) );

        if ( $params['action'] != 'create' || $params['posted_form_id'] != $form->id || ! $_POST ) {
            do_action('frm_display_form_action', $params, $fields, $form, $title, $description);
            if ( apply_filters('frm_continue_to_new', true, $form->id, $params['action']) ) {
                $values = FrmEntriesHelper::setup_new_vars($fields, $form);
                include(FrmAppHelper::plugin_path() .'/classes/views/frm-entries/new.php');
            }
            return;
        }

        if ( ! empty($errors) ) {
            $values = $fields ? FrmEntriesHelper::setup_new_vars($fields, $form) : array();
            include(FrmAppHelper::plugin_path() .'/classes/views/frm-entries/new.php');
            return;
        }

        do_action('frm_validate_form_creation', $params, $fields, $form, $title, $description);
        if ( ! apply_filters('frm_continue_to_create', true, $form->id) ) {
            return;
        }

        $values = FrmEntriesHelper::setup_new_vars($fields, $form, true);
        $created = ( isset( $frm_vars['created_entries'] ) && isset( $frm_vars['created_entries'][ $form->id ] ) ) ? $frm_vars['created_entries'][ $form->id ]['entry_id'] : 0;
        $conf_method = apply_filters('frm_success_filter', 'message', $form, $form->options, 'create');

        if ( $created && is_numeric($created) && $conf_method != 'message' ) {
            do_action('frm_success_action', $conf_method, $form, $form->options, $created);
            do_action('frm_after_entry_processed', array( 'entry_id' => $created, 'form' => $form));
            return;
        }

        if ( $created && is_numeric($created) ) {
            $message = isset($form->options['success_msg']) ? $form->options['success_msg'] : $frm_settings->success_msg;
            $class = 'frm_message';
        } else {
            $message = $frm_settings->failed_msg;
            $class = 'frm_error_style';
        }
        $message = apply_filters('frm_content', $message, $form, $created);
        $message = '<div class="'. $class .'">'. wpautop(do_shortcode($message)) .'</div>';
        $message = apply_filters('frm_main_feedback', $message, $form, $created);

        if ( ! isset($form->options['show_form']) || $form->options['show_form'] ) {
            require(FrmAppHelper::plugin_path() .'/classes/views/frm-entries/new.php');
        } else {
            global $frm_vars;
            FrmFormsHelper::form_loaded($form, $values['custom_style'], $frm_vars['load_css']);

            $include_extra_container = 'frm_forms'. FrmFormsHelper::get_form_style_class($values);
            include(FrmAppHelper::plugin_path() .'/classes/views/frm-entries/errors.php');
        }

        do_action('frm_after_entry_processed', array( 'entry_id' => $created, 'form' => $form));
    }
}