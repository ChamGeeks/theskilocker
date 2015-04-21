<?php
// Custom post types
function kad_portfolio_post_init() {
  $portfoliolabels = array(
    'name' =>  __('Portfolio', 'kadencetoolkit'),
    'singular_name' => __('Portfolio Item', 'kadencetoolkit'),
    'add_new' => __('Add New', 'kadencetoolkit'),
    'add_new_item' => __('Add New Portfolio Item', 'kadencetoolkit'),
    'edit_item' => __('Edit Portfolio Item', 'kadencetoolkit'),
    'new_item' => __('New Portfolio Item', 'kadencetoolkit'),
    'all_items' => __('All Portfolio', 'kadencetoolkit'),
    'view_item' => __('View Portfolio Item', 'kadencetoolkit'),
    'search_items' => __('Search Portfolio', 'kadencetoolkit'),
    'not_found' =>  __('No Portfolio Item found', 'kadencetoolkit'),
    'not_found_in_trash' => __('No Portfolio Items found in Trash', 'kadencetoolkit'),
    'parent_item_colon' => '',
    'menu_name' => __('Portfolio', 'kadencetoolkit')
  );

  $portargs = array(
    'labels' => $portfoliolabels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite'  => array( 'slug' => 'portfolio' ), /* you can specify its url slug */
    'has_archive' => false, 
    'capability_type' => 'post', 
    'hierarchical' => false,
    'menu_position' => 8,
    'menu_icon' =>  'dashicons-format-gallery',
    'supports' => array( 'title', 'editor', 'excerpt', 'author', 'page-attributes', 'thumbnail', 'custom-fields', 'comments' )
  ); 
  // Initialize Taxonomy Labels
	$worklabels = array(
		'name' => __( 'Portfolio Type', 'kadencetoolkit' ),
		'singular_name' => __( 'Type', 'kadencetoolkit' ),
		'search_items' =>  __( 'Search Type', 'kadencetoolkit' ),
		'all_items' => __( 'All Type', 'kadencetoolkit' ),
		'parent_item' => __( 'Parent Type', 'kadencetoolkit' ),
		'parent_item_colon' => __( 'Parent Type:', 'kadencetoolkit' ),
		'edit_item' => __( 'Edit Type', 'kadencetoolkit' ),
		'update_item' => __( 'Update Type', 'kadencetoolkit' ),
		'add_new_item' => __( 'Add New Type', 'kadencetoolkit' ),
		'new_item_name' => __( 'New Type Name', 'kadencetoolkit' ),
	);
	// Register Custom Taxonomy
	register_taxonomy('portfolio-type',array('portfolio'), array(
		'hierarchical' => true, // define whether to use a system like tags or categories
		'labels' => $worklabels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
	));

  register_post_type( 'portfolio', $portargs );
}
add_action( 'init', 'kad_portfolio_post_init', 1 );
	
