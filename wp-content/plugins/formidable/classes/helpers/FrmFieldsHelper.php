<?php
if ( ! defined('ABSPATH') ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmFieldsHelper {

    public static function field_selection() {
        $fields = apply_filters('frm_available_fields', array(
            'text'      => __( 'Single Line Text', 'formidable' ),
            'textarea'  => __( 'Paragraph Text', 'formidable' ),
            'checkbox'  => __( 'Checkboxes', 'formidable' ),
            'radio'     => __( 'Radio Buttons', 'formidable' ),
            'select'    => __( 'Dropdown', 'formidable' ),
            'email'     => __( 'Email Address', 'formidable' ),
            'url'       => __( 'Website/URL', 'formidable' ),
            'captcha'   => __( 'reCAPTCHA', 'formidable' ),
        ));

        return $fields;
    }

    public static function pro_field_selection() {
        return apply_filters('frm_pro_available_fields', array(
            'end_divider' => array(
            	'name'  => __( 'End Section', 'formidable' ),
            	'switch_from' => 'divider',
			),
            'divider'   => __( 'Section', 'formidable' ),
            'break'     => __( 'Page Break', 'formidable' ),
            'file'      => __( 'File Upload', 'formidable' ),
            'rte'       => __( 'Rich Text', 'formidable' ),
            'number'    => __( 'Number', 'formidable' ),
            'phone'     => __( 'Phone Number', 'formidable' ),
            'date'      => __( 'Date', 'formidable' ),
            'time'      => __( 'Time', 'formidable' ),
            'image'     => __( 'Image URL', 'formidable' ),
            'scale'     => __( 'Scale', 'formidable' ),
            'data'      => __( 'Dynamic Field', 'formidable' ),
            'form'      => __( 'Embed Form', 'formidable' ),
            'hidden'    => __( 'Hidden Field', 'formidable' ),
            'user_id'   => __( 'User ID (hidden)', 'formidable' ),
            'password'  => __( 'Password', 'formidable' ),
            'html'      => __( 'HTML', 'formidable' ),
            'tag'       => __( 'Tags', 'formidable' )
            //'address' => 'Address' //Address line 1, Address line 2, City, State/Providence, Postal Code, Select Country
            //'city_selector' => 'US State/County/City selector',
            //'full_name' => 'First and Last Name',
            //'quiz'    => 'Question and Answer' // for captcha alternative
        ));
    }

    public static function is_no_save_field($type) {
        return in_array($type, self::no_save_fields());
    }

    public static function no_save_fields() {
		return array( 'divider', 'end_divider', 'captcha', 'break', 'html' );
    }

    /**
     * Check if this is a multiselect dropdown field
     *
     * @since 2.0
     * @return boolean
     */
    public static function is_multiple_select($field) {
        if ( is_array($field) ) {
            return isset($field['multiple']) && $field['multiple'] && ( ( $field['type'] == 'select' || ( $field['type'] == 'data' && isset($field['data_type']) && $field['data_type'] == 'select') ) );
        } else {
            return isset($field->field_options['multiple']) && $field->field_options['multiple'] && ( ( $field->type == 'select' || ( $field->type == 'data' && isset($field->field_options['data_type']) && $field->field_options['data_type'] == 'select') ) );
        }
    }

    /**
    * Check if this field can hold an array of values
    *
    * @since 2.0
    *
    * @param array|object $field
    * @return boolean
    */
    public static function is_field_with_multiple_values( $field ) {
        if ( ! $field ) {
            return false;
        }

        if ( is_array( $field ) ) {
			// For field array
            return $field['type'] == 'checkbox' || ( $field['type'] == 'data' && isset($field['data_type']) && $field['data_type'] == 'checkbox' ) || self::is_multiple_select( $field );
        } else {
			// For field object
            return $field->type == 'checkbox' || ( $field->type == 'data' && $field->field_options['data_type'] == 'checkbox' ) || self::is_multiple_select($field);
        }
    }

    /**
     * If $field is numeric, get the field object
     */
    public static function maybe_get_field( &$field ) {
        if ( ! is_object($field) ) {
            $field = FrmField::getOne($field);
        }
    }

    public static function setup_new_vars($type = '', $form_id = '') {

        if ( strpos($type, '|') ) {
            list($type, $setting) = explode('|', $type);
        }

        $defaults = self::get_default_field_opts($type, $form_id);
        $defaults['field_options']['custom_html'] = self::get_default_html($type);

        $values = array();

        foreach ( $defaults as $var => $default ) {
            if ( $var == 'field_options' ) {
                $values['field_options'] = array();
                foreach ( $default as $opt_var => $opt_default ) {
                    $values['field_options'][ $opt_var ] = $opt_default;
                    unset($opt_var, $opt_default);
                }
            } else {
                $values[ $var ] = $default;
            }
            unset($var, $default);
        }

        if ( isset( $setting ) && ! empty( $setting ) ) {
            if ( 'data' == $type ) {
                $values['field_options']['data_type'] = $setting;
            } else {
                $values['field_options'][ $setting ] = 1;
            }
        }

        if ( $type == 'radio' || $type == 'checkbox' ) {
            $values['options'] = serialize( array(
                __( 'Option 1', 'formidable' ),
                __( 'Option 2', 'formidable' ),
            ) );
        } else if ( $type == 'select' ) {
            $values['options'] = serialize( array(
                '', __( 'Option 1', 'formidable' ),
            ) );
        } else if ( $type == 'textarea' ) {
            $values['field_options']['max'] = '5';
        } else if ( $type == 'captcha' ) {
            $frm_settings = FrmAppHelper::get_settings();
            $values['invalid'] = $frm_settings->re_msg;
        } else if ( 'url' == $type ) {
            $values['name'] = __( 'Website', 'formidable' );
        }

        $fields = self::field_selection();
        $fields = array_merge($fields, self::pro_field_selection());

        if ( isset( $fields[ $type ] ) ) {
            $values['name'] = is_array( $fields[ $type ] ) ? $fields[ $type ]['name'] : $fields[ $type ];
        }

        unset($fields);

        return $values;
    }

    public static function get_html_id($field, $plus = '') {
        return apply_filters('frm_field_html_id', 'field_'. $field['field_key'] . $plus, $field);
    }

    public static function setup_edit_vars( $record, $doing_ajax = false ) {
        $values = array( 'id' => $record->id, 'form_id' => $record->form_id);
        $defaults = array( 'name' => $record->name, 'description' => $record->description);
        $default_opts = array(
            'field_key' => $record->field_key, 'type' => $record->type,
            'default_value'=> $record->default_value, 'field_order' => $record->field_order,
            'required' => $record->required,
        );

		if ( $doing_ajax ) {
            $values = $values + $defaults + $default_opts;
            $values['form_name'] = '';
		} else {
			foreach ( $defaults as $var => $default ) {
                $values[ $var ] = htmlspecialchars( FrmAppHelper::get_param( $var, $default ) );
                unset($var, $default);
            }

            foreach ( array( 'field_key' => $record->field_key, 'type' => $record->type, 'default_value' => $record->default_value, 'field_order' => $record->field_order, 'required' => $record->required ) as $var => $default ) {
                $values[ $var ] = FrmAppHelper::get_param( $var, $default );
                unset($var, $default);
            }

            $values['form_name'] = ($record->form_id) ? FrmForm::getName( $record->form_id ) : '';
        }

        unset($defaults, $default_opts);

        $values['options'] = $record->options;
        $values['field_options'] = $record->field_options;

        $defaults = self::get_default_field_opts($values['type'], $record, true);

		if ( $values['type'] == 'captcha' ) {
            $frm_settings = FrmAppHelper::get_settings();
            $defaults['invalid'] = $frm_settings->re_msg;
        }

		foreach ( $defaults as $opt => $default ) {
            $values[ $opt ] = isset( $record->field_options[ $opt ] ) ? $record->field_options[ $opt ] : $default;
            unset($opt, $default);
        }

        $values['custom_html'] = (isset($record->field_options['custom_html'])) ? $record->field_options['custom_html'] : self::get_default_html($record->type);

        return apply_filters('frm_setup_edit_field_vars', $values, array( 'doing_ajax' => $doing_ajax));
    }

    public static function get_default_field_opts( $type, $field, $limit = false ) {
        $field_options = array(
            'size' => '', 'max' => '', 'label' => '', 'blank' => '',
            'required_indicator' => '*', 'invalid' => '', 'separate_value' => 0,
            'clear_on_focus' => 0, 'default_blank' => 0, 'classes' => '',
			'custom_html' => '',
        );

		if ( $limit ) {
            return $field_options;
		}

        global $wpdb;

        $form_id = (is_numeric($field)) ? $field : $field->form_id;

        $key = is_numeric($field) ? FrmAppHelper::get_unique_key('', $wpdb->prefix .'frm_fields', 'field_key') : $field->field_key;

        $field_count = FrmDb::get_var( 'frm_fields', array( 'form_id' => $form_id ), 'field_order', array( 'order_by' => 'field_order DESC' ) );

        $frm_settings = FrmAppHelper::get_settings();
        return array(
            'name' => __( 'Untitled', 'formidable' ), 'description' => '',
            'field_key' => $key, 'type' => $type, 'options'=>'', 'default_value'=>'',
            'field_order' => $field_count+1, 'required' => false,
            'blank' => $frm_settings->blank_msg, 'unique_msg' => $frm_settings->unique_msg,
            'invalid' => __( 'This field is invalid', 'formidable' ), 'form_id' => $form_id,
			'field_options' => $field_options,
        );
    }

    public static function fill_field( &$values, $field, $form_id, $new_key = '' ) {
        global $wpdb;

        $values['field_key'] = FrmAppHelper::get_unique_key($new_key, $wpdb->prefix .'frm_fields', 'field_key');
        $values['form_id'] = $form_id;
        $values['options'] = maybe_serialize($field->options);
        $values['default_value'] = maybe_serialize($field->default_value);

        foreach ( array( 'name', 'description', 'type', 'field_order', 'field_options', 'required' ) as $col ) {
            $values[ $col ] = $field->{$col};
        }
    }

    /**
     * @since 2.0
     */
    public static function get_error_msg($field, $error) {
        $frm_settings = FrmAppHelper::get_settings();
        $default_settings = $frm_settings->default_options();

        $defaults = array(
            'unique_msg' => array( 'full' => $default_settings['unique_msg'], 'part' => $field->name.' '. __( 'must be unique', 'formidable' )),
            'invalid'   => array( 'full' => __( 'This field is invalid', 'formidable' ), 'part' => $field->name.' '. __( 'is invalid', 'formidable' ))
        );

        $msg = ( $field->field_options[ $error ] == $defaults[ $error ]['full'] || empty( $field->field_options[ $error ] ) ) ? $defaults[ $error ]['part'] : $field->field_options[ $error ];
        return $msg;
    }

    public static function get_form_fields( $form_id, $error = false ) {
        $fields = FrmField::get_all_for_form($form_id);
        $fields = apply_filters('frm_get_paged_fields', $fields, $form_id, $error);
        return $fields;
    }

	public static function get_default_html( $type = 'text' ) {
        if (apply_filters('frm_normal_field_type_html', true, $type)) {
            $input = (in_array($type, array( 'radio', 'checkbox', 'data'))) ? '<div class="frm_opt_container">[input]</div>' : '[input]';
            $for = '';
            if ( ! in_array( $type, array( 'radio', 'checkbox', 'data', 'scale') ) ) {
                $for = 'for="field_[key]"';
            }

            $default_html = <<<DEFAULT_HTML
<div id="frm_field_[id]_container" class="frm_form_field form-field [required_class][error_class]">
    <label $for class="frm_primary_label">[field_name]
        <span class="frm_required">[required_label]</span>
    </label>
    $input
    [if description]<div class="frm_description">[description]</div>[/if description]
    [if error]<div class="frm_error">[error]</div>[/if error]
</div>
DEFAULT_HTML;
        } else {
			$default_html = apply_filters('frm_other_custom_html', '', $type);
        }

        return apply_filters('frm_custom_html', $default_html, $type);
    }

    public static function replace_shortcodes($html, $field, $errors = array(), $form = false, $args = array()) {
        $html = apply_filters('frm_before_replace_shortcodes', $html, $field, $errors, $form);

        $defaults = array(
            'field_name'  => 'item_meta['. $field['id'] .']',
            'field_id'    => $field['id'],
            'field_plus_id' => '',
            'section_id'    => '',
        );
        $args = wp_parse_args($args, $defaults);
        $field_name = $args['field_name'];
        $field_id = $args['field_id'];
        $html_id = self::get_html_id($field, $args['field_plus_id']);

        if ( self::is_multiple_select($field) ) {
            $field_name .= '[]';
        }

        //replace [id]
        $html = str_replace('[id]', $field_id, $html);

        // Remove the for attribute for captcha
        if ( $field['type'] == 'captcha' ) {
            $html = str_replace(' for="field_[key]"', '', $html);
        }

        // set the label for
        $html = str_replace('field_[key]', $html_id, $html);

        //replace [key]
        $html = str_replace('[key]', $field['field_key'], $html);

        //replace [description] and [required_label] and [error]
        $required = ($field['required'] == '0') ? '' : $field['required_indicator'];
        if ( ! is_array( $errors ) ) {
            $errors = array();
        }
        $error = isset($errors['field'. $field_id]) ? $errors['field'. $field_id] : false;

        //If field type is section heading, add class so a bottom margin can be added to either the h3 or description
        if ( $field['type'] == 'divider' ) {
            if ( isset( $field['description'] ) && $field['description'] ) {
                $html = str_replace( 'frm_description', 'frm_description frm_section_spacing', $html );
            } else {
                $html = str_replace('[label_position]', '[label_position] frm_section_spacing', $html);
            }
        }

        foreach ( array( 'description' => $field['description'], 'required_label' => $required, 'error' => $error) as $code => $value) {
            self::remove_inline_conditions( ( $value && $value != '' ), $code, $value, $html );
        }

        //replace [required_class]
        $required_class = ($field['required'] == '0') ? '' : ' frm_required_field';
        $html = str_replace('[required_class]', $required_class, $html);

        //replace [label_position]
        $field['label'] = apply_filters('frm_html_label_position', $field['label'], $field, $form);
        $field['label'] = ( $field['label'] && $field['label'] != '' ) ? $field['label'] : 'top';
		$html = str_replace( '[label_position]', ( ( in_array( $field['type'], array( 'divider', 'end_divider', 'break' ) ) ) ? $field['label'] : ' frm_primary_label' ), $html );

        //replace [field_name]
        $html = str_replace('[field_name]', $field['name'], $html);

        //replace [error_class]
		$error_class = isset ( $errors['field'. $field_id] ) ? ' frm_blank_field' : '';
		self::get_more_field_classes( $error_class, $field, $field_id, $html );
		if ( $field['type'] == 'html' && strpos( $html, '[error_class]' ) === false ) {
			// there is no error_class shortcode to use for addign fields
			$html = str_replace( 'class="frm_form_field', 'class="frm_form_field ' . $error_class, $html );
		}
        $html = str_replace('[error_class]', $error_class, $html);

        //replace [entry_key]
        $entry_key = ( $_GET && isset($_GET['entry']) ) ? $_GET['entry'] : '';
        $html = str_replace('[entry_key]', $entry_key, $html);

        //replace [input]
        preg_match_all("/\[(input|deletelink)\b(.*?)(?:(\/))?\]/s", $html, $shortcodes, PREG_PATTERN_ORDER);
        global $frm_vars;
        $frm_settings = FrmAppHelper::get_settings();

        foreach ( $shortcodes[0] as $short_key => $tag ) {
            $atts = shortcode_parse_atts( $shortcodes[2][ $short_key ] );
            $tag = self::get_shortcode_tag($shortcodes, $short_key, array( 'conditional' => false, 'conditional_check' => false));

            $replace_with = '';

            if ( $tag == 'input' ) {
                if ( isset($atts['opt']) ) {
                    $atts['opt']--;
                }

                $field['input_class'] = isset($atts['class']) ? $atts['class'] : '';
                if ( isset($atts['class']) ) {
                    unset($atts['class']);
                }

                $field['shortcodes'] = $atts;
                ob_start();
                include(FrmAppHelper::plugin_path() .'/classes/views/frm-fields/input.php');
                $replace_with = ob_get_contents();
                ob_end_clean();
            } else if ( $tag == 'deletelink' && FrmAppHelper::pro_is_installed() ) {
                $replace_with = FrmProEntriesController::entry_delete_link($atts);
            }

            $html = str_replace( $shortcodes[0][ $short_key ], $replace_with, $html );
        }

		if ( $form ) {
            $form = (array) $form;

            //replace [form_key]
            $html = str_replace('[form_key]', $form['form_key'], $html);

            //replace [form_name]
            $html = str_replace('[form_name]', $form['name'], $html);
        }
        $html .= "\n";

        //Return html if conf_field to prevent loop
        if ( isset($field['conf_field']) && $field['conf_field'] == 'stop' ) {
            return $html;
        }

        //If field is in repeating section
        if ( $args['section_id'] ) {
            $html = apply_filters('frm_replace_shortcodes', $html, $field, array( 'errors' => $errors, 'form' => $form, 'field_name' => $field_name, 'field_id' => $field_id, 'field_plus_id' => $args['field_plus_id'], 'section_id' => $args['section_id'] ));
        } else {
            $html = apply_filters('frm_replace_shortcodes', $html, $field, array( 'errors' => $errors, 'form' => $form ));
        }

        // remove [collapse_this] when running the free version
        if (preg_match('/\[(collapse_this)\]/s', $html)) {
                    $html = str_replace('[collapse_this]', '', $html);
        }

        return $html;
    }

	/**
	* Add more classes to certain fields (like confirmation fields, other fields, repeating fields, etc.)
	*
	* @since 2.0
	* @param $error_class string, pass by reference
	* @param $field array
	* @param $field_id int
	* @param $html string
	*/
	private static function get_more_field_classes( &$error_class, $field, $field_id, $html ) {
		$error_class .= ' frm_'. $field['label'] .'_container';
		if ( $field['id'] != $field_id ) {
			// add a class for repeating/embedded fields
			$error_class .= ' frm_field_'. $field['id'] .'_container';
		}

		//Add classes to inline confirmation field (if it doesn't already have classes set)
		if ( isset ( $field['conf_field'] ) && $field['conf_field'] == 'inline' && ! $field['classes'] ) {
			$error_class .= ' frm_first_half';
		}

		//Add class if field includes other option
		if ( isset( $field['other'] ) && true == $field['other'] ) {
			$error_class .= ' frm_other_container';
		}

		// If this is a Section
		if ( $field['type'] == 'divider' ) {

			// If the top margin needs to be removed from a section heading
			if ( $field['label'] == 'none' ) {
				$error_class .= ' frm_hide_section';
			}

			// If this is a repeating section that should be hidden with exclude_fields or fields shortcode, hide it
			if ( $field['repeat'] ) {
				global $frm_vars;
				if ( isset( $frm_vars['show_fields'] ) && ! empty ( $frm_vars['show_fields'] ) && ! in_array( $field['id'], $frm_vars['show_fields'] ) && ! in_array( $field['field_key'], $frm_vars['show_fields'] ) ) {
					$error_class .= ' frm_hidden';
				}
			}
		}

		//insert custom CSS classes
		if ( ! empty( $field['classes'] ) ) {
			if ( ! strpos( $html, 'frm_form_field ') ) {
				$error_class .= ' frm_form_field';
			}
			$error_class .= ' '. $field['classes'];
		}
	}

    public static function remove_inline_conditions( $no_vars, $code, $replace_with, &$html ) {
        if ( $no_vars ) {
            $html = str_replace( '[if '. $code.']', '', $html );
    	    $html = str_replace( '[/if '. $code.']', '', $html );
        } else {
            $html = preg_replace( '/(\[if\s+'. $code .'\])(.*?)(\[\/if\s+'. $code .'\])/mis', '', $html );
        }

        $html = str_replace( '['. $code .']', $replace_with, $html );
    }

    public static function get_shortcode_tag($shortcodes, $short_key, $args) {
        $args = wp_parse_args($args, array( 'conditional' => false, 'conditional_check' => false, 'foreach' => false));
        if ( ( $args['conditional'] || $args['foreach'] ) && ! $args['conditional_check'] ) {
            $args['conditional_check'] = true;
        }

        $prefix = '';
        if ( $args['conditional_check'] ) {
            if ( $args['conditional'] ) {
                $prefix = 'if ';
            } else if ( $args['foreach'] ) {
                $prefix = 'foreach ';
            }
        }

        $with_tags = $args['conditional_check'] ? 3 : 2;
        if ( ! empty( $shortcodes[ $with_tags ][ $short_key ] ) ) {
            $tag = str_replace( '[' . $prefix, '', $shortcodes[0][ $short_key ] );
            $tag = str_replace(']', '', $tag);
            $tags = explode(' ', $tag);
            if ( is_array($tags) ) {
                $tag = $tags[0];
            }
        } else {
            $tag = $shortcodes[ $with_tags - 1 ][ $short_key ];
        }

        return $tag;
    }

    public static function display_recaptcha($field) {
        $frm_settings = FrmAppHelper::get_settings();
        $lang = apply_filters('frm_recaptcha_lang', $frm_settings->re_lang, $field);

        $api_js_url = 'https://www.google.com/recaptcha/api.js';
        if ( $lang != 'en' ) {
            $api_js_url .= '?hl='. $lang;
        }
        $api_js_url = apply_filters('frm_recpatcha_js_url', $api_js_url);

        wp_register_script('recaptcha-api', $api_js_url, '', true);
        wp_enqueue_script('recaptcha-api');

?>
<div id="field_<?php echo esc_attr( $field['field_key'] ) ?>" class="g-recaptcha" data-sitekey="<?php echo esc_attr( $frm_settings->pubkey ) ?>"></div>
<?php
    }

    public static function show_single_option($field) {
        $field_name = $field['name'];
        $html_id = self::get_html_id($field);
        foreach ( $field['options'] as $opt_key => $opt ) {
            $field_val = apply_filters('frm_field_value_saved', $opt, $opt_key, $field);
            $opt = apply_filters('frm_field_label_seen', $opt, $opt_key, $field);

            // If this is an "Other" option, get the HTML for it
            if ( FrmAppHelper::is_other_opt( $opt_key ) ) {
                // Get string for Other text field, if needed
                $other_val = FrmAppHelper::get_other_val( $opt_key, $field );
                require(FrmAppHelper::plugin_path() .'/pro/classes/views/frmpro-fields/other-option.php');
            } else {
                require(FrmAppHelper::plugin_path() .'/classes/views/frm-fields/single-option.php');
            }
        }
    }

    public static function dropdown_categories($args) {
		$defaults = array( 'field' => false, 'name' => false, 'show_option_all' => ' ' );
        $args = wp_parse_args($args, $defaults);

        if ( ! $args['field'] ) {
            return;
        }

        if ( ! $args['name'] ) {
            $args['name'] = 'item_meta['. $args['field']['id'] .']';
        }

        $id = self::get_html_id($args['field']);
        $class = $args['field']['type'];

        $exclude = (is_array($args['field']['exclude_cat'])) ? implode(',', $args['field']['exclude_cat']) : $args['field']['exclude_cat'];
        $exclude = apply_filters('frm_exclude_cats', $exclude, $args['field']);

        if ( is_array($args['field']['value']) ) {
            if ( ! empty($exclude) ) {
                $args['field']['value'] = array_diff($args['field']['value'], explode(',', $exclude));
            }
            $selected = reset($args['field']['value']);
        } else {
            $selected = $args['field']['value'];
        }

        $tax_atts = array(
            'show_option_all' => $args['show_option_all'], 'hierarchical' => 1, 'name' => $args['name'],
            'id' => $id, 'exclude' => $exclude, 'class' => $class, 'selected' => $selected,
            'hide_empty' => false, 'echo' => 0, 'orderby' => 'name',
        );

        $tax_atts = apply_filters('frm_dropdown_cat', $tax_atts, $args['field']);

        if ( FrmAppHelper::pro_is_installed() ) {
            $post_type = FrmProFormsHelper::post_type($args['field']['form_id']);
            $tax_atts['taxonomy'] = FrmProAppHelper::get_custom_taxonomy($post_type, $args['field']);
            if ( ! $tax_atts['taxonomy'] ) {
                return;
            }

            // If field type is dropdown (not Dynamic), exclude children when parent is excluded
            if ( $args['field']['type'] != 'data' && is_taxonomy_hierarchical($tax_atts['taxonomy']) ) {
                $tax_atts['exclude_tree'] = $exclude;
            }
        }

        $dropdown = wp_dropdown_categories($tax_atts);

        $add_html = FrmFieldsController::input_html($args['field'], false);

        if ( FrmAppHelper::pro_is_installed() ) {
            $add_html .= FrmProFieldsController::input_html($args['field'], false);
        }

        $dropdown = str_replace("<select name='". $args['name'] ."' id='$id' class='$class'", "<select name='". $args['name'] ."' id='$id' ". $add_html, $dropdown);

        if ( is_array($args['field']['value']) ) {
            $skip = true;
            foreach ( $args['field']['value'] as $v ) {
				if ( $skip ) {
                    $skip = false;
                    continue;
                }
                $dropdown = str_replace(' value="'. $v. '"', ' value="'. $v .'" selected="selected"', $dropdown);
                unset($v);
            }
        }

        return $dropdown;
    }

    public static function get_term_link($tax_id) {
        $tax = get_taxonomy($tax_id);
        if ( ! $tax ) {
            return;
        }

        $link = sprintf(
            __( 'Please add options from the WordPress "%1$s" page', 'formidable' ),
            '<a href="'. esc_url( admin_url( 'edit-tags.php?taxonomy='. $tax->name ) ) .'" target="_blank">'. ( empty($tax->labels->name) ? __( 'Categories' ) : $tax->labels->name ) .'</a>'
        );
        unset($tax);

        return $link;
    }

    public static function value_meets_condition($observed_value, $cond, $hide_opt) {
		// Remove white space from hide_opt
		if ( ! is_array( $hide_opt ) ) {
			$hide_opt = rtrim( $hide_opt );
		}

        if ( is_array($observed_value) ) {
            return self::array_value_condition($observed_value, $cond, $hide_opt);
        }

        $m = false;
        if ( $cond == '==' ) {
            $m = $observed_value == $hide_opt;
        } else if ( $cond == '!=' ) {
            $m = $observed_value != $hide_opt;
        } else if ( $cond == '>' ) {
            $m = $observed_value > $hide_opt;
        } else if ( $cond == '<' ) {
            $m = $observed_value < $hide_opt;
        } else if ( $cond == 'LIKE' || $cond == 'not LIKE' ) {
            $m = strpos($observed_value, $hide_opt);
            if ( $cond == 'not LIKE' ) {
                $m = ( $m === false ) ? true : false;
            } else {
                $m = ( $m === false ) ? false : true;
            }
        }
        return $m;
    }

    public static function array_value_condition($observed_value, $cond, $hide_opt) {
        $m = false;
        if ( $cond == '==' ) {
            if ( is_array($hide_opt) ) {
                $m = array_intersect($hide_opt, $observed_value);
                $m = empty($m) ? false : true;
            } else {
                $m = in_array($hide_opt, $observed_value);
            }
        } else if ( $cond == '!=' ) {
            $m = ! in_array($hide_opt, $observed_value);
        } else if ( $cond == '>' ) {
            $min = min($observed_value);
            $m = $min > $hide_opt;
        } else if ( $cond == '<' ) {
            $max = max($observed_value);
            $m = $max < $hide_opt;
        } else if ( $cond == 'LIKE' || $cond == 'not LIKE' ) {
            foreach ( $observed_value as $ob ) {
                $m = strpos($ob, $hide_opt);
                if ( $m !== false ) {
                    $m = true;
                    break;
                }
            }

            if ( $cond == 'not LIKE' ) {
                $m = ( $m === false ) ? true : false;
            }
        }

        return $m;
    }

    /**
     * Replace a few basic shortcodes and field ids
     * @since 2.0
     * @return string
     */
    public static function basic_replace_shortcodes($value, $form, $entry) {
        if ( strpos($value, '[sitename]') !== false ) {
            $new_value = wp_specialchars_decode( FrmAppHelper::site_name(), ENT_QUOTES );
            $value = str_replace('[sitename]', $new_value, $value);
        }

        $value = apply_filters('frm_content', $value, $form, $entry);
        $value = do_shortcode($value);

        return $value;
    }

    public static function get_shortcodes($content, $form_id) {
        if ( FrmAppHelper::pro_is_installed() ) {
            return FrmProDisplaysHelper::get_shortcodes($content, $form_id);
        }

        $fields = FrmField::getAll( array( 'fi.form_id' => (int) $form_id, 'fi.type not' => self::no_save_fields() ) );

        $tagregexp = self::allowed_shortcodes($fields);

        preg_match_all("/\[(if )?($tagregexp)\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s", $content, $matches, PREG_PATTERN_ORDER);

        return $matches;
    }

    public static function allowed_shortcodes($fields = array()) {
        $tagregexp = array(
            'editlink', 'id', 'key', 'ip',
            'siteurl', 'sitename', 'admin_email',
            'post[-|_]id', 'created[-|_]at', 'updated[-|_]at', 'updated[-|_]by',
        );

        foreach ( $fields as $field ) {
            $tagregexp[] = $field->id;
            $tagregexp[] = $field->field_key;
        }

        $tagregexp = implode('|', $tagregexp);
        return $tagregexp;
    }

    public static function replace_content_shortcodes($content, $entry, $shortcodes) {
        $shortcode_values = array(
           'id'     => $entry->id,
           'key'    => $entry->item_key,
           'ip'     => $entry->ip,
        );

        foreach ( $shortcodes[0] as $short_key => $tag ) {
            $atts = shortcode_parse_atts( $shortcodes[3][ $short_key ] );

            if ( ! empty( $shortcodes[3][ $short_key ] ) ) {
				$tag = str_replace( array( '[', ']' ), '', $shortcodes[0][ $short_key ] );
                $tags = explode(' ', $tag);
                if ( is_array($tags) ) {
                    $tag = $tags[0];
                }
            } else {
                $tag = $shortcodes[2][ $short_key ];
            }

            switch ( $tag ) {
                case 'id':
                case 'key':
                case 'ip':
                    $replace_with = $shortcode_values[ $tag ];
                break;

                case 'user_agent':
                case 'user-agent':
                    $entry->description = maybe_unserialize($entry->description);
                    $replace_with = FrmEntriesHelper::get_browser($entry->description['browser']);
                break;

                case 'created_at':
                case 'created-at':
                case 'updated_at':
                case 'updated-at':
                    if ( isset($atts['format']) ) {
                        $time_format = ' ';
                    } else {
                        $atts['format'] = get_option('date_format');
                        $time_format = '';
                    }

                    $this_tag = str_replace('-', '_', $tag);
                    $replace_with = FrmAppHelper::get_formatted_time($entry->{$this_tag}, $atts['format'], $time_format);
                    unset($this_tag);
                break;

                case 'created_by':
                case 'created-by':
                case 'updated_by':
                case 'updated-by':
                    $this_tag = str_replace('-', '_', $tag);
                    $replace_with = self::get_display_value($entry->{$this_tag}, (object) array( 'type' => 'user_id'), $atts);
                    unset($this_tag);
                break;

                case 'admin_email':
                case 'siteurl':
                case 'frmurl':
                case 'sitename':
                case 'get':
                    $replace_with = self::dynamic_default_values( $tag, $atts );
                break;

                default:
                    $field = FrmField::getOne( $tag );
                    if ( ! $field ) {
                        break;
                    }

                    $sep = isset($atts['sep']) ? $atts['sep'] : ', ';

                    $replace_with = FrmEntryMeta::get_entry_meta_by_field($entry->id, $field->id);

                    $atts['entry_id'] = $entry->id;
                    $atts['entry_key'] = $entry->item_key;
                    //$replace_with = apply_filters('frmpro_fields_replace_shortcodes', $replace_with, $tag, $atts, $field);

                    if ( is_array($replace_with) ) {
                        $replace_with = implode($sep, $replace_with);
                    }

                    if ( isset($atts['show']) && $atts['show'] == 'field_label' ) {
                        $replace_with = $field->name;
                    } else if ( isset($atts['show']) && $atts['show'] == 'description' ) {
                        $replace_with = $field->description;
                    } else if ( empty($replace_with) && $replace_with != '0' ) {
                        $replace_with = '';
                    } else {
                        $replace_with = self::get_display_value($replace_with, $field, $atts);
                    }

                    unset($field);
                break;
            }

            if ( isset($replace_with) ) {
                $content = str_replace( $shortcodes[0][ $short_key ], $replace_with, $content );
            }

            unset($atts, $conditional, $replace_with);
		}

		return $content;
    }

    /**
     * Get the value to replace a few standard shortcodes
     *
     * @since 2.0
     * @return string
     */
    public static function dynamic_default_values( $tag, $atts = array(), $return_array = false ) {
        $new_value = '';
        switch ( $tag ) {
            case 'admin_email':
                $new_value = get_option('admin_email');
                break;
            case 'siteurl':
                $new_value = FrmAppHelper::site_url();
                break;
            case 'frmurl':
                $new_value = FrmAppHelper::plugin_url();
                break;
            case 'sitename':
                $new_value = FrmAppHelper::site_name();
                break;
            case 'get':
                $new_value = self::process_get_shortcode( $atts, $return_array );
                break;
        }

        return $new_value;
    }

    /**
     * Process the [get] shortcode
     *
     * @since 2.0
     * @return string|array
     */
    public static function process_get_shortcode( $atts, $return_array = false ) {
        if ( ! isset($atts['param']) ) {
            return '';
        }

        if ( strpos($atts['param'], '&#91;') ) {
            $atts['param'] = str_replace('&#91;', '[', $atts['param']);
            $atts['param'] = str_replace('&#93;', ']', $atts['param']);
        }

        $new_value = FrmAppHelper::get_param($atts['param'], '');
        $new_value = FrmAppHelper::get_query_var( $new_value, $atts['param'] );

        if ( $new_value == '' ) {
            if ( ! isset($atts['prev_val']) ) {
                $atts['prev_val'] = '';
            }

            $new_value = isset($atts['default']) ? $atts['default'] : $atts['prev_val'];
        }

        if ( is_array($new_value) && ! $return_array ) {
            $new_value = implode(', ', $new_value);
        }

        return $new_value;
    }

    public static function get_display_value($replace_with, $field, $atts = array()) {
		$sep = isset( $atts['sep'] ) ? $atts['sep'] : ', ';

		$replace_with = apply_filters( 'frm_get_display_value', $replace_with, $field, $atts );

        if ( $field->type == 'textarea' || $field->type == 'rte' ) {
            $autop = isset($atts['wpautop']) ? $atts['wpautop'] : true;
            if ( apply_filters('frm_use_wpautop', $autop) ) {
                if ( is_array($replace_with) ) {
                    $replace_with = implode("\n", $replace_with);
                }
                $replace_with = wpautop($replace_with);
            }
			unset( $autop );
		} else if ( is_array( $replace_with ) ) {
			$replace_with = implode( $sep, $replace_with );
		}

		return $replace_with;
	}

    public static function get_field_types($type) {
        $single_input = array(
            'text', 'textarea', 'rte', 'number', 'email', 'url',
            'image', 'file', 'date', 'phone', 'hidden', 'time',
            'user_id', 'tag', 'password',
        );
		$multiple_input = array( 'radio', 'checkbox', 'select', 'scale' );
		$other_type = array( 'divider', 'html', 'break' );

        $field_selection = array_merge( self::pro_field_selection(), self::field_selection() );

        $field_types = array();
        if ( in_array($type, $single_input) ) {
            self::field_types_for_input( $single_input, $field_selection, $field_types );
        } else if ( in_array($type, $multiple_input) ) {
            self::field_types_for_input( $multiple_input, $field_selection, $field_types );
        } else if ( in_array($type, $other_type) ) {
            self::field_types_for_input( $other_type, $field_selection, $field_types );
		} else if ( isset( $field_selection[ $type ] ) ) {
            $field_types[ $type ] = $field_selection[ $type ];
        }

        return $field_types;
    }

    private static function field_types_for_input( $inputs, $fields, &$field_types ) {
        foreach ( $inputs as $input ) {
            $field_types[ $input ] = $fields[ $input ];
            unset($input);
        }
    }

    public static function show_onfocus_js($clear_on_focus){ ?>
    <a href="javascript:void(0)" class="frm_bstooltip <?php echo ($clear_on_focus) ? '' : 'frm_inactive_icon '; ?>frm_default_val_icons frm_action_icon frm_reload_icon frm_icon_font" title="<?php echo esc_attr($clear_on_focus ? __( 'Clear default value when typing', 'formidable' ) : __( 'Do not clear default value when typing', 'formidable' )); ?>"></a>
    <?php
    }

    public static function show_default_blank_js($default_blank){ ?>
    <a href="javascript:void(0)" class="frm_bstooltip <?php echo $default_blank ? '' : 'frm_inactive_icon '; ?>frm_default_val_icons frm_action_icon frm_error_icon frm_icon_font" title="<?php echo $default_blank ? esc_attr( 'Default value will NOT pass form validation', 'formidable' ) : esc_attr( 'Default value will pass form validation', 'formidable' ); ?>"></a>
    <?php
    }

    public static function switch_field_ids($val) {
        global $frm_duplicate_ids;
        $replace = array();
        $replace_with = array();
        foreach ( (array) $frm_duplicate_ids as $old => $new ) {
            $replace[] = '[if '. $old .']';
            $replace_with[] = '[if '. $new .']';
            $replace[] = '[if '. $old .' ';
            $replace_with[] = '[if '. $new .' ';
            $replace[] = '[/if '. $old .']';
            $replace_with[] = '[/if '. $new .']';
            $replace[] = '['. $old .']';
            $replace_with[] = '['. $new .']';
            $replace[] = '['. $old .' ';
            $replace_with[] = '['. $new .' ';
            unset($old, $new);
        }
		if ( is_array( $val ) ) {
			foreach ( $val as $k => $v ) {
                $val[ $k ] = str_replace( $replace, $replace_with, $v );
                unset($k, $v);
            }
        } else {
            $val = str_replace($replace, $replace_with, $val);
        }

        return $val;
    }

    public static function get_us_states() {
        return apply_filters( 'frm_us_states', array(
            'AL' => 'Alabama', 'AK' => 'Alaska', 'AR' => 'Arkansas', 'AZ' => 'Arizona',
            'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
            'DC' => 'District of Columbia',
            'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
            'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
            'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine','MD' => 'Maryland',
            'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
            'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
            'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
            'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
            'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
            'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
            'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
            'WI' => 'Wisconsin', 'WY' => 'Wyoming',
        ) );
    }

    public static function get_countries() {
        return apply_filters( 'frm_countries', array(
            __( 'Afghanistan', 'formidable' ), __( 'Albania', 'formidable' ), __( 'Algeria', 'formidable' ),
            __( 'American Samoa', 'formidable' ), __( 'Andorra', 'formidable' ), __( 'Angola', 'formidable' ),
            __( 'Anguilla', 'formidable' ), __( 'Antarctica', 'formidable' ), __( 'Antigua and Barbuda', 'formidable' ),
            __( 'Argentina', 'formidable' ), __( 'Armenia', 'formidable' ), __( 'Aruba', 'formidable' ),
            __( 'Australia', 'formidable' ), __( 'Austria', 'formidable' ), __( 'Azerbaijan', 'formidable' ),
            __( 'Bahamas', 'formidable' ), __( 'Bahrain', 'formidable' ), __( 'Bangladesh', 'formidable' ),
            __( 'Barbados', 'formidable' ), __( 'Belarus', 'formidable' ), __( 'Belgium', 'formidable' ),
            __( 'Belize', 'formidable' ), __( 'Benin', 'formidable' ), __( 'Bermuda', 'formidable' ),
            __( 'Bhutan', 'formidable' ), __( 'Bolivia', 'formidable' ), __( 'Bosnia and Herzegovina', 'formidable' ),
            __( 'Botswana', 'formidable' ), __( 'Brazil', 'formidable' ), __( 'Brunei', 'formidable' ),
            __( 'Bulgaria', 'formidable' ), __( 'Burkina Faso', 'formidable' ), __( 'Burundi', 'formidable' ),
            __( 'Cambodia', 'formidable' ), __( 'Cameroon', 'formidable' ), __( 'Canada', 'formidable' ),
            __( 'Cape Verde', 'formidable' ), __( 'Cayman Islands', 'formidable' ), __( 'Central African Republic', 'formidable' ),
            __( 'Chad', 'formidable' ), __( 'Chile', 'formidable' ), __( 'China', 'formidable' ),
            __( 'Colombia', 'formidable' ), __( 'Comoros', 'formidable' ), __( 'Congo', 'formidable' ),
            __( 'Costa Rica', 'formidable' ), __( 'C&ocirc;te d\'Ivoire', 'formidable' ), __( 'Croatia', 'formidable' ),
            __( 'Cuba', 'formidable' ), __( 'Cyprus', 'formidable' ), __( 'Czech Republic', 'formidable' ),
            __( 'Denmark', 'formidable' ), __( 'Djibouti', 'formidable' ), __( 'Dominica', 'formidable' ),
            __( 'Dominican Republic', 'formidable' ), __( 'East Timor', 'formidable' ), __( 'Ecuador', 'formidable' ),
            __( 'Egypt', 'formidable' ), __( 'El Salvador', 'formidable' ), __( 'Equatorial Guinea', 'formidable' ),
            __( 'Eritrea', 'formidable' ), __( 'Estonia', 'formidable' ), __( 'Ethiopia', 'formidable' ),
            __( 'Fiji', 'formidable' ), __( 'Finland', 'formidable' ), __( 'France', 'formidable' ),
            __( 'French Guiana', 'formidable' ), __( 'French Polynesia', 'formidable' ), __( 'Gabon', 'formidable' ),
            __( 'Gambia', 'formidable' ), __( 'Georgia', 'formidable' ), __( 'Germany', 'formidable' ),
            __( 'Ghana', 'formidable' ), __( 'Gibraltar', 'formidable' ), __( 'Greece', 'formidable' ),
            __( 'Greenland', 'formidable' ), __( 'Grenada', 'formidable' ), __( 'Guam', 'formidable' ),
            __( 'Guatemala', 'formidable' ), __( 'Guinea', 'formidable' ), __( 'Guinea-Bissau', 'formidable' ),
            __( 'Guyana', 'formidable' ), __( 'Haiti', 'formidable' ), __( 'Honduras', 'formidable' ),
            __( 'Hong Kong', 'formidable' ), __( 'Hungary', 'formidable' ), __( 'Iceland', 'formidable' ),
            __( 'India', 'formidable' ), __( 'Indonesia', 'formidable' ), __( 'Iran', 'formidable' ),
            __( 'Iraq', 'formidable' ), __( 'Ireland', 'formidable' ), __( 'Israel', 'formidable' ),
            __( 'Italy', 'formidable' ), __( 'Jamaica', 'formidable' ), __( 'Japan', 'formidable' ),
            __( 'Jordan', 'formidable' ), __( 'Kazakhstan', 'formidable' ), __( 'Kenya', 'formidable' ),
            __( 'Kiribati', 'formidable' ), __( 'North Korea', 'formidable' ), __( 'South Korea', 'formidable' ),
            __( 'Kuwait', 'formidable' ), __( 'Kyrgyzstan', 'formidable' ), __( 'Laos', 'formidable' ),
            __( 'Latvia', 'formidable' ), __( 'Lebanon', 'formidable' ), __( 'Lesotho', 'formidable' ),
            __( 'Liberia', 'formidable' ), __( 'Libya', 'formidable' ), __( 'Liechtenstein', 'formidable' ),
            __( 'Lithuania', 'formidable' ), __( 'Luxembourg', 'formidable' ), __( 'Macedonia', 'formidable' ),
            __( 'Madagascar', 'formidable' ), __( 'Malawi', 'formidable' ), __( 'Malaysia', 'formidable' ),
            __( 'Maldives', 'formidable' ), __( 'Mali', 'formidable' ), __( 'Malta', 'formidable' ),
            __( 'Marshall Islands', 'formidable' ), __( 'Mauritania', 'formidable' ), __( 'Mauritius', 'formidable' ),
            __( 'Mexico', 'formidable' ), __( 'Micronesia', 'formidable' ), __( 'Moldova', 'formidable' ),
            __( 'Monaco', 'formidable' ), __( 'Mongolia', 'formidable' ), __( 'Montenegro', 'formidable' ),
            __( 'Montserrat', 'formidable' ), __( 'Morocco', 'formidable' ), __( 'Mozambique', 'formidable' ),
            __( 'Myanmar', 'formidable' ), __( 'Namibia', 'formidable' ), __( 'Nauru', 'formidable' ),
            __( 'Nepal', 'formidable' ), __( 'Netherlands', 'formidable' ), __( 'New Zealand', 'formidable' ),
            __( 'Nicaragua', 'formidable' ), __( 'Niger', 'formidable' ), __( 'Nigeria', 'formidable' ),
            __( 'Norway', 'formidable' ), __( 'Northern Mariana Islands', 'formidable' ), __( 'Oman', 'formidable' ),
            __( 'Pakistan', 'formidable' ), __( 'Palau', 'formidable' ), __( 'Palestine', 'formidable' ),
            __( 'Panama', 'formidable' ), __( 'Papua New Guinea', 'formidable' ), __( 'Paraguay', 'formidable' ),
            __( 'Peru', 'formidable' ), __( 'Philippines', 'formidable' ), __( 'Poland', 'formidable' ),
            __( 'Portugal', 'formidable' ), __( 'Puerto Rico', 'formidable' ), __( 'Qatar', 'formidable' ),
            __( 'Romania', 'formidable' ), __( 'Russia', 'formidable' ), __( 'Rwanda', 'formidable' ),
            __( 'Saint Kitts and Nevis', 'formidable' ), __( 'Saint Lucia', 'formidable' ),
            __( 'Saint Vincent and the Grenadines', 'formidable' ), __( 'Samoa', 'formidable' ),
            __( 'San Marino', 'formidable' ), __( 'Sao Tome and Principe', 'formidable' ), __( 'Saudi Arabia', 'formidable' ),
            __( 'Senegal', 'formidable' ), __( 'Serbia and Montenegro', 'formidable' ), __( 'Seychelles', 'formidable' ),
            __( 'Sierra Leone', 'formidable' ), __( 'Singapore', 'formidable' ), __( 'Slovakia', 'formidable' ),
            __( 'Slovenia', 'formidable' ), __( 'Solomon Islands', 'formidable' ), __( 'Somalia', 'formidable' ),
            __( 'South Africa', 'formidable' ), __( 'South Sudan', 'formidable' ),
            __( 'Spain', 'formidable' ), __( 'Sri Lanka', 'formidable' ),
            __( 'Sudan', 'formidable' ), __( 'Suriname', 'formidable' ), __( 'Swaziland', 'formidable' ),
            __( 'Sweden', 'formidable' ), __( 'Switzerland', 'formidable' ), __( 'Syria', 'formidable' ),
            __( 'Taiwan', 'formidable' ), __( 'Tajikistan', 'formidable' ), __( 'Tanzania', 'formidable' ),
            __( 'Thailand', 'formidable' ), __( 'Togo', 'formidable' ), __( 'Tonga', 'formidable' ),
            __( 'Trinidad and Tobago', 'formidable' ), __( 'Tunisia', 'formidable' ), __( 'Turkey', 'formidable' ),
            __( 'Turkmenistan', 'formidable' ), __( 'Tuvalu', 'formidable' ), __( 'Uganda', 'formidable' ),
            __( 'Ukraine', 'formidable' ), __( 'United Arab Emirates', 'formidable' ), __( 'United Kingdom', 'formidable' ),
            __( 'United States', 'formidable' ), __( 'Uruguay', 'formidable' ), __( 'Uzbekistan', 'formidable' ),
            __( 'Vanuatu', 'formidable' ), __( 'Vatican City', 'formidable' ), __( 'Venezuela', 'formidable' ),
            __( 'Vietnam', 'formidable' ), __( 'Virgin Islands, British', 'formidable' ),
            __( 'Virgin Islands, U.S.', 'formidable' ), __( 'Yemen', 'formidable' ), __( 'Zambia', 'formidable' ),
            __( 'Zimbabwe', 'formidable' ),
        ) );
    }

    public static function get_bulk_prefilled_opts(array &$prepop) {
        $prepop[__( 'Countries', 'formidable' )] = FrmFieldsHelper::get_countries();

        $states = FrmFieldsHelper::get_us_states();
        $state_abv = array_keys($states);
        sort($state_abv);
        $prepop[__( 'U.S. State Abbreviations', 'formidable' )] = $state_abv;

        $states = array_values($states);
        sort($states);
        $prepop[__( 'U.S. States', 'formidable' )] = $states;
        unset($state_abv, $states);

        $prepop[__( 'Age', 'formidable' )] = array(
            __( 'Under 18', 'formidable' ), __( '18-24', 'formidable' ), __( '25-34', 'formidable' ),
            __( '35-44', 'formidable' ), __( '45-54', 'formidable' ), __( '55-64', 'formidable' ),
            __( '65 or Above', 'formidable' ), __( 'Prefer Not to Answer', 'formidable' ),
        );

        $prepop[__( 'Satisfaction', 'formidable' )] = array(
            __( 'Very Satisfied', 'formidable' ), __( 'Satisfied', 'formidable' ), __( 'Neutral', 'formidable' ),
            __( 'Unsatisfied', 'formidable' ), __( 'Very Unsatisfied', 'formidable' ), __( 'N/A', 'formidable' ),
        );

        $prepop[__( 'Importance', 'formidable' )] = array(
            __( 'Very Important', 'formidable' ), __( 'Important', 'formidable' ), __( 'Neutral', 'formidable' ),
            __( 'Somewhat Important', 'formidable' ), __( 'Not at all Important', 'formidable' ), __( 'N/A', 'formidable' ),
        );

        $prepop[__( 'Agreement', 'formidable' )] = array(
            __( 'Strongly Agree', 'formidable' ), __( 'Agree', 'formidable' ), __( 'Neutral', 'formidable' ),
            __( 'Disagree', 'formidable' ), __( 'Strongly Disagree', 'formidable' ), __( 'N/A', 'formidable' ),
        );

        $prepop = apply_filters('frm_bulk_field_choices', $prepop);
    }
}
