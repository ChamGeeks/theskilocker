<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function dh_ptp_upgrade_to_premium_menu()
{
    $page_hook = add_submenu_page('edit.php?post_type=easy-pricing-table', __('Upgrade to Premium', PTP_LOC), __('Upgrade to Premium', PTP_LOC), 'manage_options', 'easy-pricing-tables-upgrade', 'dh_ptp_upgrade_to_premium');
    add_action('load-' . $page_hook , 'dh_ptp_upgrade_ob_start');
}
add_action('admin_menu', 'dh_ptp_upgrade_to_premium_menu');

function dh_ptp_upgrade_ob_start() {
    ob_start();
}

function dh_ptp_upgrade_to_premium()
{
    wp_redirect('https://fatcatapps.com/easypricingtables/?utm_campaign=wp%2Bsubmenu&utm_source=Easy%2BPricing%2BTables%2BFree&utm_medium=plugin&utm_content=v1', 301);
    exit();
}

function dh_ptp_upgrade_to_premium_menu_js()
{
    ?>
    <script type="text/javascript">
    	jQuery(document).ready(function ($) {
            $('a[href="edit.php?post_type=easy-pricing-table&page=easy-pricing-tables-upgrade"]').on('click', function () {
        		$(this).attr('target', '_blank');
            });
        });
    </script>
    <style>
        a[href="edit.php?post_type=easy-pricing-table&page=easy-pricing-tables-upgrade"] {
            color: #ee6800 !important;
        }
        a[href="edit.php?post_type=easy-pricing-table&page=easy-pricing-tables-upgrade"]:hover {
            color: #C65700 !important;
        }
    </style>
    <?php 
}
add_action( 'admin_footer', 'dh_ptp_upgrade_to_premium_menu_js');

/* Upgrade to premium notice when plugin is upgraded */

/*
 * // Remove upgrade nag is due WP.org policy
 * 
function dh_ptp_upgrade_check()
{
	$installed_version = get_option('dh_ptp_ept_free_version');
	if (defined('PTP_PLUGIN_VERSION') && $installed_version != PTP_PLUGIN_VERSION) {
		if (is_admin() && current_user_can('manage_options')) {
			add_action('admin_head', 'dh_ptp_all_admin_notices_css');
			add_action('all_admin_notices', 'dh_ptp_all_admin_notices');
			update_option("dh_ptp_ept_free_version", PTP_PLUGIN_VERSION);
		}
	}
}
add_action('plugins_loaded', 'dh_ptp_upgrade_check');

function dh_ptp_all_admin_notices()
{
	echo
		'<div class="dh-ptp-upgrade-nag">'.
			'<p>' . sprintf( __('Thanks for using Easy Pricing Tables. If you like this plugin, please consider supporting continued development by <a href="%s">purchasing the premium version</a>.', PTP_LOC), 'http://easypricingtables.com/?utm_source=free-plugin&utm_medium=link&utm_campaign=upgrade-notice') . '</p>' .
			'<p>' . __('Easy Pricing Tables Premium comes with 4 additional table designs, 369 icons and tons of customization options.', PTP_LOC) . '</p>'.
			'<p>' . sprintf( __('<a href="%s">Click here to learn more...</a>', PTP_LOC), 'http://fatcatapps.com/easypricingtables/?utm_campaign=ept-upgrade-nag&utm_source=free-plugin&utm_medium=link&utm_content=v1') . '</p>'.
		'</div>';
}

function dh_ptp_all_admin_notices_css()
{
	echo
		'<style type="text/css">' .
			'.dh-ptp-upgrade-nag {background: #282929; color: #fff; padding:10px 40px 10px 40px; margin-top: 40px; margin-right: 20px; text-align: center;} ' .
			'.dh-ptp-upgrade-nag {color: #fff;} ' .
			'.dh-ptp-upgrade-nag a {color: #6bbc5b;} '.
			'.dh-ptp-upgrade-nag a:hover {color: #7ad368;}' .
		'</style>';
}*/
?>