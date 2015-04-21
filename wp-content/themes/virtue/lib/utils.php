<?php
/**
 * Theme wrapper
 *
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */
function kadence_template_path() {
  return Kadence_Wrapping::$main_template;
}

function kadence_sidebar_path() {
  return Kadence_Wrapping::sidebar();
}

class Kadence_Wrapping {
  // Stores the full path to the main template file
  static $main_template;

  // Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
  static $base;

  static function wrap($template) {
    self::$main_template = $template;

    self::$base = substr(basename(self::$main_template), 0, -4);

    if (self::$base === 'index') {
      self::$base = false;
    }

    $templates = array('base.php');

    if (self::$base) {
      array_unshift($templates, sprintf('base-%s.php', self::$base));
    }

    return locate_template($templates);
  }

  static function sidebar() {
    $templates = array('templates/sidebar.php');

    if (self::$base) {
      array_unshift($templates, sprintf('templates/sidebar-%s.php', self::$base));
    }

    return locate_template($templates);
  }
}
add_filter('template_include', array('Kadence_Wrapping', 'wrap'), 99);

/**
 * Page titles
 */
function kadence_title() {
  if (is_home()) {
    if (get_option('page_for_posts', true)) {
      return get_the_title(get_option('page_for_posts', true));
    } else {
      return __('Latest Posts', 'virtue');
    }
  } elseif (is_archive()) {
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    if ($term) {
      return $term->name;
    } elseif (is_post_type_archive()) {
      return get_queried_object()->labels->name;
    } elseif (is_day()) {
      return sprintf(__('Daily Archives: %s', 'virtue'), get_the_date());
    } elseif (is_month()) {
      return sprintf(__('Monthly Archives: %s', 'virtue'), get_the_date('F Y'));
    } elseif (is_year()) {
      return sprintf(__('Yearly Archives: %s', 'virtue'), get_the_date('Y'));
    } elseif (is_author()) {
      return sprintf(__('Author Archives: %s', 'virtue'), get_the_author());
    } else {
      return single_cat_title("", false);
    }
  } elseif (is_search()) {
    return sprintf(__('Search Results for %s', 'virtue'), get_search_query());   
  } elseif (is_404()) {
    return __('Not Found', 'virtue');
  } else {
    return get_the_title();
  }
}


/**
 * Return WordPress subdirectory if applicable
 */
function wp_base_dir() {
  preg_match('!(https?://[^/|"]+)([^"]+)?!', site_url(), $matches);
  if (count($matches) === 3) {
    return end($matches);
  } else {
    return '';
  }
}

/**
 * Opposite of built in WP functions for trailing slashes
 */
function leadingslashit($string) {
  return '/' . unleadingslashit($string);
}

function unleadingslashit($string) {
  return ltrim($string, '/');
}

function add_filters($tags, $function) {
  foreach($tags as $tag) {
    add_filter($tag, $function);
  }
}

function is_element_empty($element) {
  $element = trim($element);
  return empty($element) ? false : true;
}
