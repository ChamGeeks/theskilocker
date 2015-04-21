<?php
/**
 * virtue initial setup and constants
 */
function kadence_setup() {

  // Register wp_nav_menu() menus (http://codex.wordpress.org/Function_Reference/register_nav_menus)
  register_nav_menus(array(
    'primary_navigation'    => __('Primary Navigation', 'virtue'),
    'secondary_navigation'  => __('Secondary Navigation', 'virtue'),
    'mobile_navigation'     => __('Mobile Navigation', 'virtue'),
    'topbar_navigation'     => __('Topbar Navigation', 'virtue'),
    'footer_navigation'     => __('Footer Navigation', 'virtue'),
  ));
  
  // Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
  add_theme_support('post-thumbnails');
  add_image_size( 'widget-thumb', 80, 50, true );
  add_post_type_support( 'attachment', 'page-attributes' );
  add_theme_support( 'automatic-feed-links' );
  add_editor_style('/assets/css/editor-style.css');
}
add_action('after_setup_theme', 'kadence_setup');

// Backwards compatibility for older than PHP 5.3.0
if (!defined('__DIR__')) { define('__DIR__', dirname(__FILE__)); }

