<?php


// global styles for the meta boxes
if (is_admin()){
	add_action('admin_print_scripts-post-new.php', 'dh_ptp_metabox_styles_and_scripts');
	add_action('admin_print_scripts-post.php', 'dh_ptp_metabox_styles_and_scripts');
}

function dh_ptp_metabox_styles_and_scripts()
{
	global $post_type;

    if( 'easy-pricing-table' == $post_type ) {
	       //UI styles - includes all styles necessary for the UI
	       wp_enqueue_style('wpalchemy-metabox', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-styles.css' );

	       // Color Picker JS
	       wp_enqueue_style( 'wp-color-picker' );
		
	       // Jquery UI Tabs
	       wp_enqueue_script("jquery-ui-tabs");

               // Jquery lighbox - colorbox
               wp_enqueue_script( 'dh-ptp-colorbox', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-components/colorbox/jquery.colorbox-min.js', array('jquery') );


                //ui scripts - this file contains all Javascript necessary for the GUI
	 	wp_enqueue_script( 'ui-script', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-scripts.js', array('jquery', 'jquery-ui-tabs', 'wp-color-picker', 'dh-ptp-colorbox') );

		/** UI Components **/
		//add bootstrap css for popover help boxes
		wp_enqueue_style('bootstrap-popover', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-components/bootstrap/css/bootstrap.min.css' );
		//add bootstrap js for popovers
		wp_enqueue_script( 'bootstrap-popover', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-components/bootstrap/js/bootstrap.min.js' );
		// fontello icons
		wp_enqueue_style('fontello-icon', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-components/fontello/fontello.css' );               
        }

}

?>