<?php
/*
	Plugin Name: Easy Pricing Tables Lite by Fatcat Apps
	Plugin URI: https://fatcatapps.com/easypricingtables
	Description: Create a Beautiful, Responsive and Highly Converting Pricing or Comparison Table in Less Than 5 Minutes with Easy Pricing Tables for WordPress. No Coding Required.
	Author: David Hehenberger
	Version: 2.0
	Author URI: https://fatcatapps.com
*/

if( ! defined( 'PTP_PLUGIN_PATH' ) ) {

  // define plugin version for update nag
  define('PTP_PLUGIN_VERSION', '1.9.5.5');

  // Define a constant to always include the absolute path
  define('PTP_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
  define('PTP_PLUGIN_PATH_FOR_SUBDIRS', plugins_url(str_replace(dirname(dirname(__FILE__)), '', dirname(__FILE__))));
  define('PTP_LOC', 'easy-pricing-tables');

  // Analytics
  include_once PTP_PLUGIN_PATH . 'includes/analytics.php';
  include_once PTP_PLUGIN_PATH . 'includes/tracking.php';

  // Include post types
  include ( PTP_PLUGIN_PATH . 'includes/post-types.php');

  // Include media button
  include ( PTP_PLUGIN_PATH . 'includes/media-button.php');

  // Include clone table
  include ( PTP_PLUGIN_PATH . 'includes/clone-table.php');

  // Include shortcodes
  include ( PTP_PLUGIN_PATH . 'includes/shortcodes.php');

  // Include pointer popups
  include ( PTP_PLUGIN_PATH . 'includes/pointer.php');

  // Upgrade to Premium
  include ( PTP_PLUGIN_PATH . 'includes/upgrade.php');

  // Include WPAlchemy
  if(!class_exists('WPAlchemy_MetaBox')) {
    include_once ( PTP_PLUGIN_PATH . 'includes/libraries/wpalchemy/MetaBox.php');
  }

  include_once ( PTP_PLUGIN_PATH . 'includes/metaboxes/spec.php');

  if(is_admin()) {
  	// include WPAlchemy scripts
  	include_once ( PTP_PLUGIN_PATH . 'includes/metaboxes/setup.php');
  }

  // Add settings link on plugin page
  function dh_ptp_plugin_settings_link($links)
  {
    // Remove Edit link
    unset($links['edit']);
    
    // Add Easy Pricing Tables links
    $add_new_link = '<a href="post-new.php?post_type=easy-pricing-table">' . __('Add New', PTP_LOC) . '</a>'; 
    $forum_link   = '<a href="http://wordpress.org/support/plugin/easy-pricing-tables">' . __('Support', PTP_LOC) . '</a>';
    $premium_link = '<a href="https://fatcatapps.com/easypricingtables/?utm_campaign=Purchase%2BPremium%2Bin%2Bplugins.php&utm_source=Easy%2BPricing%2BTables%2BFree&utm_medium=plugin&utm_content=v1">' . __('Purchase Premium', PTP_LOC) . '</a>';
    
    array_push($links, $add_new_link);
    array_push($links, $forum_link);
    array_push($links, $premium_link);
    
    return $links; 
  }

  $plugin = plugin_basename(__FILE__); 
  add_filter("plugin_action_links_$plugin", 'dh_ptp_plugin_settings_link' );

  // Footer text
  function dh_ptp_plugin_footer ($text) {
    echo
  	$text . ' '.
  	sprintf( __('Thank you for using <a href="%s" target="_blank">Easy Pricing Tables</a>.', PTP_LOC), 'http://easypricingtables.com/?utm_source=free-plugin&utm_medium=link&utm_campaign=thank-you-for-using-easy-pricing-tables' ) . ' ' .
  	sprintf( __('Please <a href="%s">rate us on WordPress.org</a>.', PTP_LOC), 'http://wordpress.org/support/view/plugin-reviews/easy-pricing-tables?filter=5#postform');
  }

  function dh_ptp_plugin_footer_enqueu($hook_suffix)
  {
    global $post;
    
    if ($post && $post->post_type == 'easy-pricing-table') {
        wp_enqueue_script( 'codemirror', PTP_PLUGIN_PATH_FOR_SUBDIRS.'/assets/ui/ui-components/codemirror/codemirror.js' );
        wp_enqueue_script( 'css', PTP_PLUGIN_PATH_FOR_SUBDIRS.'/assets/ui/ui-components/codemirror/addon-codemirror/css.js' );
        wp_enqueue_style( 'codemirror-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-components/codemirror/codemirror.css' );
        add_filter('admin_footer_text', 'dh_ptp_plugin_footer');
    }
  }
  add_action('admin_enqueue_scripts', 'dh_ptp_plugin_footer_enqueu');

  /* Localization */
  function dh_ptp_localization()
  {
    $locale = apply_filters( 'plugin_locale', get_locale(), PTP_LOC );
    
    load_textdomain( PTP_LOC, trailingslashit( WP_LANG_DIR ) . PTP_LOC . '/' . PTP_LOC . '-' . $locale . '.mo' );
    load_plugin_textdomain( PTP_LOC, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
  }
  add_action('init', 'dh_ptp_localization');
}