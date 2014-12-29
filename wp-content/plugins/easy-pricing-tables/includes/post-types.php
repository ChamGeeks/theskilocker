<?php

/**
 * Register "Pricing Table" post type
 * @return [type] [description]
 */
function dh_ptp_register_pricing_table_post_type() {

	$labels = array(
	    'name' => __('Pricing Tables', PTP_LOC),
	    'singular_name' => __('Pricing Table', PTP_LOC),
	    'add_new' => __('Add New', PTP_LOC),
	    'add_new_item' => __('Add New Pricing Table', PTP_LOC),
	    'edit_item' => __('Edit Pricing Table', PTP_LOC),
	    'new_item' => __('New Pricing Table', PTP_LOC),
	    'all_items' => __('All Pricing Tables', PTP_LOC),
	    'view_item' => __('View Pricing Table', PTP_LOC),
	    'search_items' => __('Search Pricing Tables', PTP_LOC),
	    'not_found' =>  __('No Pricing Tables found', PTP_LOC),
	    'not_found_in_trash' => __('No Pricing Tables found in Trash', PTP_LOC),
	    'parent_item_colon' => '',
	    'menu_name' => __('Pricing Tables', PTP_LOC)
	  );

  	$args = array(
	    'labels' => $labels,
	    'public' => false,
	    'exclude_from_search' => true,
	    'publicly_queryable' => true,
	    'show_ui' => true, 
	    'show_in_menu' => true, 
	    'query_var' => true,
	    'rewrite' => array( 'slug' => 'pricing-table' ),
	    'capability_type' => 'post',
	    'has_archive' => false, 
	    'hierarchical' => false,
	    'menu_position' => 104,
	    'menu_icon' => PTP_PLUGIN_PATH_FOR_SUBDIRS.'/assets/ept-icon-16x16.png',
	    'supports' => array( 'title', 'revisions')
  	); 

	register_post_type( 'easy-pricing-table', $args);

}
add_action( 'init', 'dh_ptp_register_pricing_table_post_type');

/**
 * customize UI interaction messages
 * eg: Changes "Post published" to "Pricing Table published"
 * Code template from http://wp.smashingmagazine.com/2012/11/08/complete-guide-custom-post-types/
 * I removed the "view post" hyperlinks from notification messages since they are pointless
 * 
 * @param  [type] $messages [description]
 * @return [type]           [description]
 */

function dh_ptp_updated_interaction_messages( $messages ) {
	global $post, $post_ID;
	$messages['easy-pricing-table'] = array(
		0 => '', 
		1 => sprintf( __('Pricing table saved. <a href="%s">View pricing table</a>.', PTP_LOC), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.', PTP_LOC),
		3 => __('Custom field deleted.', PTP_LOC),
		4 => __('Pricing table saved.', PTP_LOC),
		5 => isset($_GET['revision']) ? sprintf( __('Pricing table restored to revision from %s', PTP_LOC), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Pricing table saved. <a href="%s">View pricing table</a>', PTP_LOC), esc_url( get_permalink($post_ID) ) ),
		7 => __('Pricing table saved.', PTP_LOC),
		//8 => sprintf( __('Pricing table submitted. <a target="_blank" href="%s">Preview pricing table</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		8 => __('Pricing table submitted.', PTP_LOC),
		9 => sprintf( __('Pricing table scheduled for: <strong>%1$s</strong>.', PTP_LOC), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => __('Pricing table saved.', PTP_LOC),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'dh_ptp_updated_interaction_messages' );

/**
 * changes the "Enter title here" to "Enter pricing table name here'" for pricing-table post type
 */
add_filter('gettext', 'dh_ptp_custom_rewrites', 10, 4);
function dh_ptp_custom_rewrites($translation, $text, $domain)
{
	global $post;
	
	if ( ! isset( $post->post_type ) ) {
		return $translation;
	}
	
	$translations = get_translations_for_domain($domain);
	$translation_array = array();
 
	switch ($post->post_type) {
		case 'easy-pricing-table': // enter your post type name here
			$translation_array = array(
				'Enter title here' => 'Enter pricing table name here'
			);
			break;
	}
 
	if (array_key_exists($text, $translation_array)) {
		return $translations->translate($translation_array[$text]);
	}
	return $translation;
}


/**
 * Customize pricing table overview tables ("Pricing Tables" -> "All Pricing Tables")
 * Add / modify columns at pricing table edit overview
 * @param  [type] $gallery_columns [description]
 * @return [type]                  [description]
 */
function dh_ptp_add_new_pricing_table_columns($gallery_columns) {
    $new_columns['cb'] = '<input type="checkbox" />';
    $new_columns['title'] = _x('Pricing Table Name', 'column name', PTP_LOC);    
    $new_columns['shortcode'] = __('Shortcode', PTP_LOC);
    $new_columns['date'] = _x('Date', 'column name', PTP_LOC);
 
    return $new_columns;
}
// Add to admin_init function
add_filter('manage_edit-easy-pricing-table_columns', 'dh_ptp_add_new_pricing_table_columns');

function dh_ptp_manage_pricing_table_columns($column_name, $id) {
    global $wpdb;

    switch ($column_name) {
	    case 'shortcode':
	        echo '<input type="text" style="width: 300px;" readonly="readonly" onclick="this.select()" value="[easy-pricing-table id=&quot;'. $id . '&quot;]"/>';
	            break;
	 
	    default:
	        break;
    } // end switch
}   
// Add to admin_init function
add_action('manage_easy-pricing-table_posts_custom_column', 'dh_ptp_manage_pricing_table_columns', 10, 2);


/**
 * Preview functionality.
(Append the pricing table shortcode to the empty post.)
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function dh_ptp_live_preview($content)
{
    global $post;
    if(get_post_type() == 'easy-pricing-table' && is_main_query()) {
		return $content . do_shortcode("[easy-pricing-table id={$post->ID}]");
	} else {
		return $content;
    }
}
add_filter( 'the_content', 'dh_ptp_live_preview');

/**
 * Remove the publish metabox for pricing tables
 * @return [type] [description]
 */

//function dh_ptp_remove_publish_metabox()
//{
//    remove_meta_box( 'submitdiv', 'easy-pricing-table', 'side' );
//}
//add_action( 'admin_menu', 'dh_ptp_remove_publish_metabox' );

function dh_ptp_admin_footer_js()
{
	global $post;
	
	if (isset($post) && $post->post_type == 'easy-pricing-table') :
		?>
			<script type="text/javascript">
				jQuery('#submitdiv').hide();
			</script>
		<?php
	endif;
}
add_action('admin_footer', 'dh_ptp_admin_footer_js');

/* Save tab state */
function dh_ptp_save_tab_state( $post_id ) {

	// If this is just a revision, don't send the email.
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}
	
	// Check if post type matches the pricing tables
	if ( isset($_POST['post_type']) && 'easy-pricing-table' != $_POST['post_type'] ) {
        return;
    }

	// Set cookie with tab data
	if (!isset($_COOKIE['dh_ptp_current_tab']) && isset($_REQUEST['dh_ptp_tab'])) {
        setcookie('dh_ptp_current_tab', $_REQUEST['dh_ptp_tab'], time()+3, COOKIEPATH, COOKIE_DOMAIN, false);
    }
}
add_action( 'save_post', 'dh_ptp_save_tab_state' );

/* Redirect when Save & Preview button is clicked */
add_filter('redirect_post_location', 'dh_ptp_save_preview_redirect');
function dh_ptp_save_preview_redirect ($location)
{
    global $post;
 
    if (
        (isset($_POST['publish']) || isset($_POST['save'])) && preg_match("/post=([0-9]*)/", $location, $match) && $post &&
		$post->ID == $match[1] && (isset($_POST['publish']) || $post->post_status == 'publish') && $pl = get_permalink($post->ID)
		&& isset($_POST['dh_ptp_preview_url'])
    ) {
		// Flush rewrite rules
		global $wp_rewrite;
		$wp_rewrite->flush_rules(true);
		
        // Always redirect to the post
        $location = $_POST['dh_ptp_preview_url'];
    }
 
    return $location;
}

/**
 * Enqueue jquery-ui-accordion in wp-admin
 */
add_action('admin_enqueue_scripts', 'dh_ptp_jquery_ui_accordion_enqueue' );
function dh_ptp_jquery_ui_accordion_enqueue(){
	$screen = get_current_screen();
	if ( 'easy-pricing-table' != $screen->id ) {
		return;
	}
	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_style('dh-ptp-jquery-ui', plugins_url('assets/ui/ui-accordion.css', dirname(__FILE__)));
}

/**
 * Print accordion related JS in Pricing Tables create/edit pages
 */
add_action('admin_print_footer_scripts', 'dh_ptp_print_jquery_ui_accordion_js' );
function dh_ptp_print_jquery_ui_accordion_js() {
	$screen = get_current_screen();
	if ( 'easy-pricing-table' != $screen->id ) {
		return;
	}
	?>
	<script type="text/javascript">
		//<![CDATA[
			jQuery(document).ready(function(){
				jQuery( ".dh_ptp_accordion" ).accordion({icons: false,heightStyle: 'content'});
			});
		//]]>
	</script>
	<?php
}

/* Deal with parasite Post Type Switcher plugin */
add_filter('pts_post_type_filter', 'ptp_dh_pts_disable');
function ptp_dh_pts_disable( $args )
{
    $postType  = get_post_type();
    if( 'easy-pricing-table' === $postType){
        $args = array(
          'name' => 'easy-pricing-table'
        );
    }
    return $args;
}

/**
 *  set screen layout to 2 colums
 */
add_filter('screen_layout_columns', 'tt_ptp_set_custom_branding_screen_layout', 10, 2);
function tt_ptp_set_custom_branding_screen_layout($columns, $screen) {
	
       if ($screen === 'easy-pricing-table'){
		$columns[$screen] = 2;
	}
	return $columns;
}

add_filter( 'get_user_option_screen_layout_easy-pricing-table', 'tt_ptp_user_option_screen_layout_easy_pricing_table' );
function tt_ptp_user_option_screen_layout_easy_pricing_table() {
    
    $screen = get_current_screen();
	if ( 'easy-pricing-table' == $screen->id ) {
		 return 2;
	}
   
}
