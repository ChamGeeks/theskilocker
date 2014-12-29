<?php

// include our HTML generators for each table
include ( PTP_PLUGIN_PATH . '/includes/table-generation/design1.php');

/* CSS Styling */
function dh_ptp_easy_pricing_table_dynamic_css( $id )
{
    global $features_metabox;
    
    $css = '';

    // Retrieve all meta data for easy pricing tables
    $query = new WP_Query( array(
            'post_type' => 'easy-pricing-table',
            'p' => $id,
            'post_status' => 'any',
    ) );
    ob_start();
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            // Print CSS styles per table
            $meta = get_post_meta(get_the_ID(), $features_metabox->get_the_id(), true);
            dh_ptp_simple_flat_css(get_the_ID(), $meta);
        endwhile;
    endif;
    wp_reset_postdata();
    $css = ob_get_clean();
    
    // Minify
    $css = preg_replace( '/\s+/', ' ', $css );
    $css = preg_replace( '/;(?=\s*})/', '', $css );
    $css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );
    $css = preg_replace( '/ (,|;|\{|}|\(|\)|>)/', '$1', $css );
    $css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
    $css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
    $css = preg_replace( '/0 0 0 0/', '0', $css );
    $css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );

    $css = '<style type="text/css">' . $css . '</style>' . "\n";

    return $css;
}

/**
 * This function decides which table style we should create. It enqueue the appropriate CSS file and calls the appropriate function.
 *
 * @return string pricing table html
 */
function dh_ptp_generate_pricing_table($id)
{
    global $features_metabox;
    $meta = get_post_meta($id, $features_metabox->get_the_id(), TRUE);

    // Enqueue IE Hacks
    wp_enqueue_style('ept-ie-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-ie.css');
    global $wp_styles;
    $wp_styles->add_data('ept-ie-style', 'conditional', 'lt IE 9');
    
    //include css
    wp_enqueue_style( 'dh-ptp-design1', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/design1/pricingtable.css' );
    
    // Print stylish enable match-column-height
       if(isset($meta['match-column-height-dg1'])) {   
             wp_enqueue_script( 'matchHeight', PTP_PLUGIN_PATH_FOR_SUBDIRS.'/assets/ui/jquery.matchHeight-min.js', array('jquery'));
         
             tt_ptp_enable_column_match_height_script_dg1();
        }
    //call appropriate function
    return dh_ptp_generate_simple_flat_pricing_table_html($id);
}

