<div id="form_global_settings" class="wrap">
    <div class="frmicon icon32"><br/></div>
    <h2><?php _e( 'Global Settings', 'formidable' ); ?></h2>

    <?php require(FrmAppHelper::plugin_path() .'/classes/views/shared/errors.php'); ?>

    <div id="poststuff" class="metabox-holder">
    <div id="post-body">
        <div class="meta-box-sortables">
        <div class="categorydiv postbox">
        <h3 class="hndle"><span><?php _e( 'Global Settings', 'formidable' ) ?></span></h3>
        <div class="inside frm-help-tabs">
        <div id="contextual-help-back"></div>
        <div id="contextual-help-columns">
        <div class="contextual-help-tabs">
        <ul class="frm-category-tabs">
            <?php $a = isset($_GET['t']) ? $_GET['t'] : 'general_settings'; ?>
        	<li <?php echo ($a == 'general_settings') ? 'class="tabs active"' : '' ?>><a href="#general_settings" class="frm_cursor_pointer"><?php _e( 'General', 'formidable' ) ?></a></li>
			<?php foreach ( $sections as $sec_name => $section ) { ?>
                <li <?php echo ($a == $sec_name .'_settings') ? 'class="tabs active"' : '' ?>><a href="#<?php echo esc_attr( $sec_name ) ?>_settings"><?php echo isset($section['name']) ? $section['name'] : ucfirst($sec_name) ?></a></li>
            <?php } ?>
        </ul>
        </div>

    <?php do_action('frm_before_settings'); ?>

    <form name="frm_settings_form" method="post" class="frm_settings_form" action="?page=formidable-settings<?php echo (isset($_GET['t'])) ? '&amp;t='. $_GET['t'] : ''; ?>">
        <input type="hidden" name="frm_action" value="process-form" />
        <input type="hidden" name="action" value="process-form" />
        <?php wp_nonce_field('process_form_nonce', 'process_form'); ?>

        <div class="general_settings tabs-panel <?php echo ($a == 'general_settings') ? 'frm_block' : 'frm_hidden'; ?>">
            <p class="submit">
				<input class="button-primary" type="submit" value="<?php esc_attr_e( 'Update Options', 'formidable' ) ?>" />
            </p>

            <h3><?php _e( 'Styling & Scripts', 'formidable' ); ?></h3>

            <p><label class="frm_left_label"><?php _e( 'Load Formidable styling', 'formidable' ) ?></label>
                <select id="frm_load_style" name="frm_load_style">
                <option value="all" <?php selected($frm_settings->load_style, 'all') ?>><?php _e( 'on every page of your site', 'formidable' ) ?></option>
                <option value="dynamic" <?php selected($frm_settings->load_style, 'dynamic') ?>><?php _e( 'only on applicable pages', 'formidable' ) ?></option>
                <option value="none" <?php selected($frm_settings->load_style, 'none') ?>><?php _e( 'Don\'t use Formidable styling on any page', 'formidable' ) ?></option>
                </select>
            </p>

            <p><label for="frm_use_html"><input type="checkbox" id="frm_use_html" name="frm_use_html" value="1" <?php checked($frm_settings->use_html, 1) ?>	> <?php _e( 'Use HTML5 in forms', 'formidable' ) ?></label>
            </p>

            <?php do_action('frm_style_general_settings', $frm_settings); ?>

			<h3><?php _e( 'User Permissions', 'formidable' ); ?>
				<span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php esc_attr_e( 'Select users that are allowed access to Formidable. Without access to View Forms, users will be unable to see the Formidable menu.', 'formidable' ) ?>"></span>
			</h3>
            <table class="form-table">
				<?php foreach ( $frm_roles as $frm_role => $frm_role_description ) { ?>
                <tr>
                    <td class="frm_left_label"><label><?php echo esc_html( $frm_role_description ) ?></label></td>
                    <td><?php FrmAppHelper::wp_roles_dropdown( $frm_role, $frm_settings->$frm_role, 'multiple' ) ?></td>
                </tr>
                <?php } ?>
            </table>

			<h3><?php _e( 'reCAPTCHA', 'formidable' ); ?>
				<span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php esc_attr_e( 'reCAPTCHA is a free, accessible CAPTCHA service that helps to digitize books while blocking spam on your blog. reCAPTCHA asks commenters to retype two words scanned from a book to prove that they are a human. This verifies that they are not a spambot.', 'formidable' ) ?>" ></span>
			</h3>

            <p class="howto">reCAPTCHA requires an API key, consisting of a "site" and a "private" key. You can sign up for a <a href="https://www.google.com/recaptcha/" target="_blank">free reCAPTCHA key</a>.</p>

			<p><label class="frm_left_label"><?php _e( 'Site Key', 'formidable' ) ?></label>
			<input type="text" name="frm_pubkey" id="frm_pubkey" size="42" value="<?php echo esc_attr($frm_settings->pubkey) ?>" /></p>

			<p><label class="frm_left_label"><?php _e( 'Private Key', 'formidable' ) ?></label>
			<input type="text" name="frm_privkey" id="frm_privkey" size="42" value="<?php echo esc_attr($frm_settings->privkey) ?>" /></p>

		    <p><label class="frm_left_label"><?php _e( 'reCAPTCHA Language', 'formidable' ) ?></label>
			<select name="frm_re_lang" id="frm_re_lang">
			    <?php foreach ( $captcha_lang as $lang => $lang_name ) { ?>
				<option value="<?php echo esc_attr($lang) ?>" <?php selected($frm_settings->re_lang, $lang) ?>><?php echo esc_html( $lang_name ) ?></option>
                <?php } ?>
            </select></p>

			<h3><?php _e( 'Default Messages', 'formidable' ); ?>
				<span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php esc_attr_e( 'You can override the success message and submit button settings on individual forms.', 'formidable' ) ?>"></span>
			</h3>

			<p>
				<label class="frm_left_label"><?php _e( 'Failed/Duplicate Entry', 'formidable' ); ?>
					<span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php esc_attr_e( 'The message seen when a form is submitted and passes validation, but something goes wrong.', 'formidable' ) ?>" ></span>
				</label>
                <input type="text" id="frm_failed_msg" name="frm_failed_msg" class="frm_with_left_label" value="<?php echo esc_attr( $frm_settings->failed_msg ) ?>" />
			</p>

			<p>
				<label class="frm_left_label"><?php _e( 'Blank Field', 'formidable' ); ?>
					<span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php esc_attr_e( 'The message seen when a required field is left blank.', 'formidable' ) ?>" ></span>
				</label>
				<input type="text" id="frm_blank_msg" name="frm_blank_msg" class="frm_with_left_label" value="<?php echo esc_attr( $frm_settings->blank_msg ) ?>" />
			</p>

			<p>
				<label class="frm_left_label"><?php _e( 'Incorrect Field', 'formidable' ); ?>
					<span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php esc_attr_e( 'The message seen when a field response is either incorrect or missing.', 'formidable' ) ?>" ></span>
				</label>
				<input type="text" id="frm_invalid_msg" name="frm_invalid_msg" class="frm_with_left_label" value="<?php echo esc_attr( $frm_settings->invalid_msg ) ?>" />
			</p>

<?php if ( FrmAppHelper::pro_is_installed() ) { ?>
			<p>
				<label class="frm_left_label"><?php _e( 'Unique Value', 'formidable' ); ?>
					<span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php esc_attr_e( 'The message seen when a user selects a value in a unique field that has already been used.', 'formidable' ) ?>" ></span>
				</label>
            	<input type="text" id="frm_unique_msg" name="frm_unique_msg" class="frm_with_left_label" value="<?php echo esc_attr( $frm_settings->unique_msg ) ?>" />
			</p>
<?php } else { ?>
			<input type="hidden" id="frm_unique_msg" name="frm_unique_msg" value="<?php echo esc_attr( $frm_settings->unique_msg ) ?>" />
			<input type="hidden" id="frm_login_msg" name="frm_login_msg" class="frm_with_left_label" value="<?php echo esc_attr( $frm_settings->login_msg ) ?>" />
<?php } ?>

		<p>
			<label class="frm_left_label"><?php _e( 'Success Message', 'formidable' ); ?>
				<span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php esc_attr_e( 'The default message seen after a form is submitted.', 'formidable' ) ?>" ></span>
			</label>
            <input type="text" id="frm_success_msg" name="frm_success_msg" class="frm_with_left_label" value="<?php echo esc_attr($frm_settings->success_msg) ?>" />
		</p>

		<p>
			<label class="frm_left_label"><?php _e( 'Default Submit Button', 'formidable' ); ?></label>
			<input type="text" value="<?php echo esc_attr( $frm_settings->submit_value ) ?>" id="frm_submit_value" name="frm_submit_value" class="frm_with_left_label" />
		</p>

        <?php do_action('frm_settings_form', $frm_settings); ?>

        <?php if ( ! FrmAppHelper::pro_is_installed() ) { ?>
        <div class="clear"></div>
        <h3><?php _e( 'Miscellaneous', 'formidable' ) ?></h3>
        <?php } ?>
        <p><label class="frm_left_label"><?php _e( 'Admin menu label', 'formidable' ); ?></label>
            <input type="text" name="frm_menu" id="frm_menu" value="<?php echo esc_attr($frm_settings->menu) ?>" />
            <?php if ( is_multisite() && is_super_admin() ) { ?>
            <label for="frm_mu_menu"><input type="checkbox" name="frm_mu_menu" id="frm_mu_menu" value="1" <?php checked($frm_settings->mu_menu, 1) ?> /> <?php _e( 'Use this menu name site-wide', 'formidable' ); ?></label>
            <?php } ?>
        </p>

        <p><label class="frm_left_label"><?php _e( 'Preview Page', 'formidable' ); ?></label>
        <?php FrmAppHelper::wp_pages_dropdown('frm-preview-page-id', $frm_settings->preview_page_id ) ?>
        </p>

    </div>

        <?php
		foreach ( $sections as $sec_name => $section ) {
			if ( $a == $sec_name .'_settings' ) { ?>
<style type="text/css">.<?php echo esc_attr( $sec_name ) ?>_settings{display:block;}</style><?php }?>
            <div id="<?php echo esc_attr( $sec_name ) ?>_settings" class="<?php echo esc_attr( $sec_name ) ?>_settings tabs-panel <?php echo ( $a == $sec_name .'_settings' ) ? 'frm_block' : 'frm_hidden'; ?>"><?php
				if ( isset( $section['class'] ) ) {
                    call_user_func( array($section['class'], $section['function']));
				} else {
                    call_user_func((isset($section['function']) ? $section['function'] : $section));
                } ?>
            </div>
        <?php
        } ?>

        <p class="alignright frm_uninstall">
            <a href="javascript:void(0)" id="frm_uninstall_now"><?php _e( 'Uninstall Formidable', 'formidable' ) ?></a>
            <span class="spinner frm_spinner"></span>
        </p>
        <p class="submit">
			<input class="button-primary" type="submit" value="<?php esc_attr_e( 'Update Options', 'formidable' ) ?>" />
        </p>

    </form>
    </div>
    </div>
    </div>
    </div>
</div>

</div>
</div>
