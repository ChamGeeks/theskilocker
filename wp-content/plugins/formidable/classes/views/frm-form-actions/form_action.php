<?php
$a = isset($_GET['t']) ? $_GET['t'] : 'advanced_settings';

$form_action = apply_filters('frm_form_action_settings', $form_action, $form_action->post_excerpt);
$form_action = apply_filters('frm_form_'. $form_action->post_excerpt .'_action_settings', $form_action);

?>
<div id="frm_form_action_<?php echo esc_attr( $action_key ) ?>" class="widget frm_form_action_settings frm_single_<?php echo esc_attr( $form_action->post_excerpt ) ?>_settings" data-actionkey="<?php echo esc_attr( $action_key ) ?>">
    <div class="widget-top">
        <div class="widget-title-action">
            <a href="javascript:void(0)" class="widget-action hide-if-no-js"></a>
        </div>
        <div class="widget-title">
            <h3><span class="frm_form_action_icon <?php echo esc_attr( $action_control->action_options['classes'] ) ?>"></span>
                <?php echo esc_html( $form_action->post_title ); ?>
            </h3>
            <span class="frm_email_icons alignright">
                <a href="javascript:void(0)" data-removeid="frm_form_action_<?php echo esc_attr( $action_key ) ?>" class="frm_icon_font frm_delete_icon frm_remove_form_action"> </a>
            </span>
        </div>
    </div>
    <div class="widget-inside frm_hidden">
        <?php
        if ( defined('DOING_AJAX') || ! $action_control->action_options['ajax_load'] ) {
            // only load settings if they are just added or are open
            include(dirname(__FILE__) .'/_action_inside.php');
        } else {
            // include hidden settings so action won't get lost on update ?>
        <input type="hidden" name="<?php echo esc_attr( $action_control->get_field_name('post_excerpt', '') ) ?>" class="frm_action_name" value="<?php echo esc_attr( $form_action->post_excerpt ); ?>" />
        <input type="hidden" name="<?php echo esc_attr( $action_control->get_field_name('ID', '') ) ?>" value="<?php echo esc_attr( $form_action->ID ); ?>" />
        <?php
        } ?>
    </div>
<style type="text/css">
.frm_no_actions{
    opacity:0;
    *visibility:hidden;
}
</style>
</div>