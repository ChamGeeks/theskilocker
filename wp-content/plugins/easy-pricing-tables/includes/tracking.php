<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Deploy
function dh_ptp_tracking_deploy()
{
    global $features_metabox;
    
    $id = (isset($_REQUEST['id']) && preg_match('/^([0-9]+)$/', $_REQUEST['id']))?$_REQUEST['id']:false;
    if ($id && get_option('dh_ptp_allow_tracking') == 'yes') {
        $meta = get_post_meta($id, $features_metabox->get_the_id(), TRUE);
        $columns = count($meta['column']);
        
        if (function_exists('dh_ptp_track_event')) {
            dh_ptp_track_event('Deploy clicked', array('Number of columns' => $columns));
        }
    }
    
    exit();
}
add_action('wp_ajax_dh_ptp_tracking_deploy', 'dh_ptp_tracking_deploy');

// Banner
function dh_ptp_tracking_banner()
{
    if (function_exists('dh_ptp_track_event') && get_option('dh_ptp_allow_tracking') == 'yes') {
        dh_ptp_track_event("Sidebar ad button clicked", array("Ad version" =>"1"));
    }
    
    exit();    
}
add_action('wp_ajax_dh_ptp_tracking_banner', 'dh_ptp_tracking_banner');

// Plugin activated
function dh_ptp_plugin_activated()
{
    if (function_exists('dh_ptp_track_event') && get_option('dh_ptp_allow_tracking') == 'yes') {
        dh_ptp_track_event('Plugin activated');
    }
}
register_activation_hook('easy-pricing-tables-free/pricing-table-plugin.php', 'dh_ptp_plugin_activated');

// Plugin deactivated
function dh_ptp_plugin_deactivated()
{
    if (function_exists('dh_ptp_track_event') && get_option('dh_ptp_allow_tracking') == 'yes') {
        dh_ptp_track_event('Plugin deactivated');
    }
}
register_deactivation_hook('easy-pricing-tables-free/pricing-table-plugin.php', 'dh_ptp_plugin_deactivated');

// Pricing Table Crash Course
function dh_ptp_crash_course($var)
{
    if (function_exists('dh_ptp_track_event') && get_option('dh_ptp_allow_tracking') == 'yes') {
        dh_ptp_track_event('Email course button clicked', array('Button copy' => $var));
    }
}
?>