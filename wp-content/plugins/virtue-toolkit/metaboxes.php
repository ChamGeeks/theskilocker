<?php 
// Build Metaboxs for gallery
function kadtool_gallery_field( $field, $meta ) {
    echo '<div class="kad-gallery kad_widget_image_gallery">';
    echo '<div class="gallery_images">';
    $attachments = array_filter( explode( ',', $meta ) );
             if ( $attachments )
            foreach ( $attachments as $attachment_id ) {
                $img = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                $imgfull = wp_get_attachment_image_src($attachment_id, 'full');
                    echo '<a class="of-uploaded-image" target="_blank" rel="external" href="' . esc_url($imgfull[0]) . '">';
                    echo '<img class="gallery-widget-image" id="gallery_widget_image_'.esc_attr($attachment_id). '" src="' . esc_url($img[0]) . '" />';
                    echo '</a>';
                }
    echo '</div>';
    echo ' <input type="hidden" id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '" class="gallery_values" value="' . esc_attr($meta) . '" />';
    echo '<a href="#" onclick="return false;" id="edit-gallery" class="gallery-attachments button button-primary">' . __('Add/Edit Gallery', 'kadencetoolkit') . '</a>';
    echo '<a href="#" onclick="return false;" id="clear-gallery" class="gallery-attachments button">' . __('Clear Gallery', 'kadencetoolkit') . '</a>';
    echo '</div>';

    if ( ! empty( $field['desc'] ) ) echo '<p class="cmb_metabox_description">' . $field['desc'] . '</p>';
}
add_filter( 'cmb_render_kad_gallery', 'kadtool_gallery_field', 10, 2 );

function kadtool_gallery_field_sanitise( $field, $meta ) {
    if ( empty( $meta ) ) {
        $meta = '';
    } else {
        $meta = $meta;
    }
    return $meta;
}
$the_theme = wp_get_theme();
if( ($the_theme->get( 'Name' ) == 'Pinnacle' && $the_theme->get( 'Version') >= '1.0.6' ) || ($the_theme->get( 'Template') == 'pinnacle') ) {
add_filter( 'cmb_meta_boxes', 'kadence_pinnacletoolkit_metaboxes', 100 );
}

function kadence_pinnacletoolkit_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_kad_';
$meta_boxes[] = array(
				'id'         => 'subtitle_metabox',
				'title'      => __( "Page Title and Subtitle", 'kadencetoolkit' ),
				'pages'      => array( 'page' ), // Post type
				'context'    => 'normal',
				'priority'   => 'default',
				'show_names' => true, // Show field names on the left
				'fields' => array(
					array(
						'name' => __( "Subtitle", 'kadencetoolkit' ),
						'desc' => __( "Subtitle will go below page title", 'kadencetoolkit' ),
						'id'   => $prefix . 'subtitle',
						'type' => 'textarea_code',
					),
					array(
						'name'    => __("Hide Page Title", 'kadencetoolkit' ),
						'desc'    => '',
						'id'      => $prefix . 'pagetitle_hide',
						'type'    => 'select',
						'options' => array(
							array( 'name' => __("Default", 'kadencetoolkit' ), 'value' => 'default', ),
							array( 'name' => __("Show", 'kadencetoolkit' ), 'value' => 'show', ),
							array( 'name' => __("Hide", 'kadencetoolkit' ), 'value' => 'hide', ),
						),
					),
					array(
						'name'    => __("Page Title background behind Header", 'kadencetoolkit' ),
						'desc'    => '',
						'id'      => $prefix . 'pagetitle_behind_head',
						'type'    => 'select',
						'options' => array(
							array( 'name' => __("Default", 'kadencetoolkit' ), 'value' => 'default', ),
							array( 'name' => __("Place behind Header", 'kadencetoolkit' ), 'value' => 'true', ),
							array( 'name' => __("Don't place behind Header", 'kadencetoolkit' ), 'value' => 'false', ),
						),
					),
				)
			);
$meta_boxes[] = array(
				'id'         => 'subtitle_metabox',
				'title'      => __( "Post Title and Subtitle", 'kadencetoolkit' ),
				'pages'      => array( 'product', 'post', 'portfolio'), // Post type
				'context'    => 'normal',
				'priority'   => 'default',
				'show_names' => true, // Show field names on the left
				'fields' => array(
					array(
						'name' => __( "Post Header Title", 'kadencetoolkit' ),
						'desc' => __( "Post Header Title", 'kadencetoolkit' ),
						'id'   => $prefix . 'post_header_title',
						'type' => 'textarea_code',
					),
					array(
						'name' => __( "Subtitle", 'kadencetoolkit' ),
						'desc' => __( "Subtitle will go below post title", 'kadencetoolkit' ),
						'id'   => $prefix . 'subtitle',
						'type' => 'textarea_code',
					),
					array(
						'name'    => __("Hide Page Title", 'kadencetoolkit' ),
						'desc'    => '',
						'id'      => $prefix . 'pagetitle_hide',
						'type'    => 'select',
						'options' => array(
							array( 'name' => __("Default", 'kadencetoolkit' ), 'value' => 'default', ),
							array( 'name' => __("Show", 'kadencetoolkit' ), 'value' => 'show', ),
							array( 'name' => __("Hide", 'kadencetoolkit' ), 'value' => 'hide', ),
						),
					),
					array(
						'name'    => __("Page Title background behind Header", 'kadencetoolkit' ),
						'desc'    => '',
						'id'      => $prefix . 'pagetitle_behind_head',
						'type'    => 'select',
						'options' => array(
							array( 'name' => __("Default", 'kadencetoolkit' ), 'value' => 'default', ),
							array( 'name' => __("Place behind Header", 'kadencetoolkit' ), 'value' => 'true', ),
							array( 'name' => __("Don't place behind Header", 'kadencetoolkit' ), 'value' => 'false', ),
						),
					),
				)
			);
$meta_boxes[] = array(
				'id'         => 'gallery_post_metabox',
				'title'      => __("Gallery Post Options", 'kadencetoolkit'),
				'pages'      => array( 'post',), // Post type
				//'show_on' => array( 'key' => 'format', 'value' => 'standard'),
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
			
			array(
				'name'    => __("Post Head Content", 'kadencetoolkit' ),
				'desc'    => '',
				'id'      => $prefix . 'gallery_blog_head',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __("Gallery Post Default", 'kadencetoolkit' ), 'value' => 'default', ),
					array( 'name' => __("Image Slider - (Flex Slider)", 'kadencetoolkit' ), 'value' => 'flex', ),
					array( 'name' => __("Carousel Slider - (Caroufedsel Slider)", 'kadencetoolkit' ), 'value' => 'carouselslider', ),
					array( 'name' => __("None", 'kadencetoolkit' ), 'value' => 'none', ),
				),
			),
			array(
				'name' => __("Post Slider Gallery", 'kadencetoolkit' ),
				'desc' => __("Add images for gallery here", 'kadencetoolkit' ),
				'id'   => $prefix . 'image_gallery',
				'type' => 'kad_gallery',
			),
			array(
				'name' => __("Max Slider Height", 'kadencetoolkit' ),
				'desc' => __("Default is: 400 (Note: just input number, example: 350)", 'kadencetoolkit' ),
				'id'   => $prefix . 'gallery_posthead_height',
				'type' => 'text_small',
			),
			array(
				'name' => __("Max Slider Width", 'kadencetoolkit' ),
				'desc' => __("Default is: 848 or 1140 on fullwidth posts (Note: just input number, example: 650, only applys to Image Slider)", 'kadencetoolkit' ),
				'id'   => $prefix . 'gallery_posthead_width',
				'type' => 'text_small',
			),
			array(
				'name'    => __("Post Summary", 'kadencetoolkit' ),
				'desc'    => '',
				'id'      => $prefix . 'gallery_post_summery',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Gallery Post Default', 'kadencetoolkit' ), 'value' => 'default', ),
					array( 'name' => __('Portrait Image (feature image)', 'kadencetoolkit'), 'value' => 'img_portrait', ),
					array( 'name' => __('Landscape Image (feature image)', 'kadencetoolkit'), 'value' => 'img_landscape', ),
					array( 'name' => __('Portrait Image Slider', 'kadencetoolkit'), 'value' => 'slider_portrait', ),
					array( 'name' => __('Landscape Image Slider', 'kadencetoolkit'), 'value' => 'slider_landscape', ),
				),
			),
		),
	);
$meta_boxes[] = array(
				'id'         => 'video_post_metabox',
				'title'      => __("Video Post Options", 'kadencetoolkit'),
				'pages'      => array( 'post',), // Post type
				//'show_on' => array( 'key' => 'format', 'value' => 'standard'),
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
			
			array(
				'name'    => __("Post Head Content", 'kadencetoolkit' ),
				'desc'    => '',
				'id'      => $prefix . 'video_blog_head',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __("Video Post Default", 'kadencetoolkit' ), 'value' => 'default', ),
					array( 'name' => __("Video", 'kadencetoolkit' ), 'value' => 'video', ),
					array( 'name' => __("None", 'kadencetoolkit' ), 'value' => 'none', ),
				),
			),
			array(
				'name' => __('Video Post embed code', 'kadencetoolkit'),
				'desc' => __('Place Embed Code Here, works with youtube, vimeo. (Use the featured image for screen shot)', 'kadencetoolkit'),
				'id'   => $prefix . 'post_video',
				'type' => 'textarea_code',
			),
			array(
				'name' => __("Max Video Width", 'kadencetoolkit' ),
				'desc' => __("Default is: 848 or 1140 on fullwidth posts (Note: just input number, example: 650, does not apply to carousel slider)", 'kadencetoolkit' ),
				'id'   => $prefix . 'video_posthead_width',
				'type' => 'text_small',
			),
			array(
				'name'    => __("Post Summary", 'kadencetoolkit' ),
				'desc'    => '',
				'id'      => $prefix . 'video_post_summery',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Video Post Default', 'kadencetoolkit' ), 'value' => 'default', ),
					array( 'name' => __('Video - (when possible)', 'kadencetoolkit'), 'value' => 'video', ),
					array( 'name' => __('Portrait Image (feature image)', 'kadencetoolkit'), 'value' => 'img_portrait', ),
					array( 'name' => __('Landscape Image (feature image)', 'kadencetoolkit'), 'value' => 'img_landscape', ),
				),
			),
		),
	);
$meta_boxes[] = array(
				'id'         => 'portfolio_post_metabox',
				'title'      => __('Portfolio Post Options', 'kadencetoolkit'),
				'pages'      => array( 'portfolio' ), // Post type
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
			
			array(
				'name'    => __('Project Layout', 'kadencetoolkit'),
				'desc'    => '<a href="http://docs.kadencethemes.com/pinnacle/#portfolio_posts" target="_blank" >Whats the difference?</a>',
				'id'      => $prefix . 'ppost_layout',
				'type'    => 'radio_inline',
				'options' => array(
					array( 'name' => __('Beside 40%', 'kadencetoolkit'), 'value' => 'beside', ),
					array( 'name' => __('Beside 33%', 'kadencetoolkit'), 'value' => 'besidesmall', ),
					array( 'name' => __('Above', 'kadencetoolkit'), 'value' => 'above', ),
					array( 'name' => __('Three Rows', 'kadencetoolkit'), 'value' => 'three', ), 
				),
			),
			array(
				'name'    => __('Project Options', 'kadencetoolkit'),
				'desc'    => '',
				'id'      => $prefix . 'ppost_type',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Image', 'kadencetoolkit'), 'value' => 'image', ),
					array( 'name' => __('Image Slider (Flex Slider)', 'kadencetoolkit'), 'value' => 'flex', ),
					array( 'name' => __('Carousel Slider', 'kadencetoolkit'), 'value' => 'carousel', ),
					array( 'name' => __('Video', 'kadencetoolkit'), 'value' => 'video', ),
					array( 'name' => __('None', 'kadencetoolkit'), 'value' => 'none', ),
				),
			),
			array(
				'name' => __("Portfolio Slider/Images", 'kadencetoolkit' ),
				'desc' => __("Add images for post here", 'kadencetoolkit' ),
				'id'   => $prefix . 'image_gallery',
				'type' => 'kad_gallery',
			),
			array(
				'name' => __("Max Image/Slider Height", 'kadencetoolkit' ),
				'desc' => __("Default is: 450 (Note: just input number, example: 350)", 'kadencetoolkit' ),
				'id'   => $prefix . 'posthead_height',
				'type' => 'text_small',
			),
			array(
				'name' => __("Max Image/Slider Width", 'kadencetoolkit' ),
				'desc' => __("Default is: 670 or 1140 on above or three row layouts (Note: just input number, example: 650)", 'kadencetoolkit' ),
				'id'   => $prefix . 'posthead_width',
				'type' => 'text_small',
			),
			array(
				'name' => __('Value 01 Title', 'kadencetoolkit'),
				'desc' => __('ex. Project Type:', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val01_title',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Value 01 Description', 'kadencetoolkit'),
				'desc' => __('ex. Character Illustration', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val01_description',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Value 02 Title', 'kadencetoolkit'),
				'desc' => __('ex. Skills Needed:', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val02_title',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Value 02 Description', 'kadencetoolkit'),
				'desc' => __('ex. Photoshop, Illustrator', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val02_description',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Value 03 Title', 'kadencetoolkit'),
				'desc' => __('ex. Customer:', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val03_title',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Value 03 Description', 'kadencetoolkit'),
				'desc' => __('ex. Example Inc', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val03_description',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Value 04 Title', 'kadencetoolkit'),
				'desc' => __('ex. Project Year:', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val04_title',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Value 04 Description', 'kadencetoolkit'),
				'desc' => __('ex. 2013', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val04_description',
				'type' => 'text_medium',
			),
			array(
				'name' => __('External Website', 'kadencetoolkit'),
				'desc' => __('ex. Website:', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val05_title',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Website Address', 'kadencetoolkit'),
				'desc' => __('ex. http://www.example.com', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val05_description',
				'type' => 'text_medium',
			),
			array(
						'name' => __('If Video Project', 'kadencetoolkit'),
						'desc' => __('Place Embed Code Here, works with youtube, vimeo...', 'kadencetoolkit'),
						'id'   => $prefix . 'post_video',
						'type' => 'textarea_code',
					),
				
		),
	);
	$meta_boxes[] = array(
				'id'         => 'portfolio_post_carousel_metabox',
				'title'      => __('Bottom Carousel Options', 'kadencetoolkit'),
				'pages'      => array( 'portfolio' ), // Post type
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
			array(
				'name' => __('Carousel Title', 'kadencetoolkit'),
				'desc' => __('ex. Similar Projects', 'kadencetoolkit'),
				'id'   => $prefix . 'portfolio_carousel_title',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Bottom Portfolio Carousel', 'kadencetoolkit'),
				'desc' => __('Display a carousel with portfolio items below project?', 'kadencetoolkit'),
				'id'   => $prefix . 'portfolio_carousel_recent',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Default', 'kadencetoolkit'), 'value' => 'defualt', ),
					array( 'name' => __('No', 'kadencetoolkit'), 'value' => 'no', ),
					array( 'name' => __('Yes', 'kadencetoolkit'), 'value' => 'yes', ),
				),
			),
			array(
				'name' => __('Carousel Items', 'kadencetoolkit'),
				'desc' => '',
				'id'   => $prefix . 'portfolio_carousel_group',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Default', 'kadencetoolkit'), 'value' => 'defualt', ),
					array( 'name' => __('All Portfolio Posts', 'kadencetoolkit'), 'value' => 'all', ),
					array( 'name' => __('Only of same Portfolio Type', 'kadencetoolkit'), 'value' => 'cat', ),
				),
			),
			array(
				'name' => __('Carousel Order', 'kadencetoolkit'),
				'desc' => '',
				'id'   => $prefix . 'portfolio_carousel_order',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Menu Order', 'kadencetoolkit'), 'value' => 'menu_order', ),
					array( 'name' => __('Title', 'kadencetoolkit'), 'value' => 'title', ),
					array( 'name' => __('Date', 'kadencetoolkit'), 'value' => 'date', ),
					array( 'name' => __('Random', 'kadencetoolkit'), 'value' => 'rand', ),
				),
			),
				
		),
	);
			$meta_boxes[] = array(
				'id'         => 'portfolio_metabox',
				'title'      => __('Portfolio Page Options', 'kadencetoolkit'),
				'pages'      => array( 'page' ), // Post type
				'show_on' => array('key' => 'page-template', 'value' => array( 'template-portfolio-grid.php')),
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
			array(
				'name'    => __('Style', 'kadencetoolkit'),
				'desc'    => '',
				'id'      => $prefix . 'portfolio_style',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Default', 'kadencetoolkit'), 'value' => 'default', ),
					array( 'name' => __('Post Boxes', 'kadencetoolkit'), 'value' => 'padded_style', ),
					array( 'name' => __('Flat with Margin', 'kadencetoolkit'), 'value' => 'flat-w-margin', ),
				),
			),
			array(
				'name'    => __('Hover Style', 'kadencetoolkit'),
				'desc'    => '',
				'id'      => $prefix . 'portfolio_hover_style',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Default', 'kadencetoolkit'), 'value' => 'default', ),
					array( 'name' => __('Light', 'kadencetoolkit'), 'value' => 'p_lightstyle', ),
					array( 'name' => __('Dark', 'kadencetoolkit'), 'value' => 'p_darkstyle', ),
					array( 'name' => __('Primary Color', 'kadencetoolkit'), 'value' => 'p_primarystyle', ),
				),
			),
			array(
				'name'    => __('Columns', 'kadencetoolkit'),
				'desc'    => '',
				'id'      => $prefix . 'portfolio_columns',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Four Column', 'kadencetoolkit'), 'value' => '4', ),
					array( 'name' => __('Three Column', 'kadencetoolkit'), 'value' => '3', ),
					array( 'name' => __('Two Column', 'kadencetoolkit'), 'value' => '2', ),
					array( 'name' => __('Five Column', 'kadencetoolkit'), 'value' => '5', ),
				),
			),
			array(
                'name' => __('Portfolio Work Types', 'kadencetoolkit'),
                'id' => $prefix .'portfolio_type',
                'type' => 'imag_select_taxonomy',
                'taxonomy' => 'portfolio-type',
            ),
            array(
				'name'    => __('Order Items By', 'kadencetoolkit'),
				'desc'    => '',
				'id'      => $prefix . 'portfolio_order',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Menu Order', 'kadencetoolkit'), 'value' => 'menu_order', ),
					array( 'name' => __('Title', 'kadencetoolkit'), 'value' => 'title', ),
					array( 'name' => __('Date', 'kadencetoolkit'), 'value' => 'date', ),
					array( 'name' => __('Random', 'kadencetoolkit'), 'value' => 'rand', ),
				),
			),
			array(
				'name'    => __('Items per Page', 'kadencetoolkit'),
				'desc'    => __('How many portfolio items per page', 'kadencetoolkit'),
				'id'      => $prefix . 'portfolio_items',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('All', 'kadencetoolkit'), 'value' => 'all', ),
					array( 'name' => '3', 'value' => '3', ),
					array( 'name' => '4', 'value' => '4', ),
					array( 'name' => '5', 'value' => '5', ),
					array( 'name' => '6', 'value' => '6', ),
					array( 'name' => '7', 'value' => '7', ),
					array( 'name' => '8', 'value' => '8', ),
					array( 'name' => '9', 'value' => '9', ),
					array( 'name' => '10', 'value' => '10', ),
					array( 'name' => '11', 'value' => '11', ),
					array( 'name' => '12', 'value' => '12', ),
					array( 'name' => '13', 'value' => '13', ),
					array( 'name' => '14', 'value' => '14', ),
					array( 'name' => '15', 'value' => '15', ),
					array( 'name' => '16', 'value' => '16', ),
				),
			),
			array(
				'name'    => __('Image Ratio?', 'kadencetoolkit'),
				'desc'    => '',
				'id'      => $prefix . 'portfolio_img_ratio',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Default', 'kadencetoolkit'), 'value' => 'default', ),
					array( 'name' => __('Square 1:1', 'kadencetoolkit'), 'value' => 'square', ),
					array( 'name' => __('Portrait 3:4', 'kadencetoolkit'), 'value' => 'portrait', ),
					array( 'name' => __('Landscape 4:3', 'kadencetoolkit'), 'value' => 'landscape', ),
					array( 'name' => __('Wide Landscape 4:2', 'kadencetoolkit'), 'value' => 'widelandscape', ),
				),
			),
			array(
				'name' => __('Display Item Work Types', 'kadencetoolkit'),
				'desc' => '',
				'id'   => $prefix . 'portfolio_item_types',
				'type' => 'checkbox',
			),
			array(
				'name' => __('Display Item Excerpt', 'kadencetoolkit'),
				'desc' => '',
				'id'   => $prefix . 'portfolio_item_excerpt',
				'type' => 'checkbox',
			),
			array(
				'name' => __('Add Lightbox link in each item', 'kadencetoolkit'),
				'desc' => '',
				'id'   => $prefix . 'portfolio_lightbox',
				'type' => 'checkbox',
			),
				
			));
			$meta_boxes[] = array(
				'id'         => 'pagefeature_metabox',
				'title'      => __('Feature Page Options', 'kadencetoolkit'),
				'pages'      => array( 'page' ), // Post type
				'show_on' => array('key' => 'page-template', 'value' => array( 'template-feature.php')),
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
			
			array(
				'name'    => __('Header Options', 'kadencetoolkit'),
				'desc'    => __('If image slider make sure images uploaded are at-least 1170px wide.', 'kadencetoolkit'),
				'id'      => $prefix . 'page_head',
				'type'    => 'select',
				'defualt' => 'pagetitle',
				'options' => array(
					array( 'name' => __('Page Title', 'kadencetoolkit'), 'value' => 'pagetitle', ),
					array( 'name' => __('Image Slider (Flex Slider)', 'kadencetoolkit'), 'value' => 'flex', ),
					array( 'name' => __('Carousel Slider', 'kadencetoolkit'), 'value' => 'carousel', ),
					array( 'name' => __('Video', 'kadencetoolkit'), 'value' => 'video', ),
				),
			),
			array(
				'name' => __("Slider Images", 'kadencetoolkit' ),
				'desc' => __("Add for flex, carousel, and image carousel.", 'kadencetoolkit' ),
				'id'   => $prefix . 'image_gallery',
				'type' => 'kad_gallery',
			),
			array(
				'name' => __('If Cyclone Slider', 'kadencetoolkit'),
				'desc' => __('Paste Cyclone slider shortcode here (example: [cycloneslider id="slider1"])', 'kadencetoolkit'),
				'id'   => $prefix . 'shortcode_slider',
				'type' => 'textarea_code',
			),
			array(
				'name' => __('Max Image/Slider Height', 'kadencetoolkit'),
				'desc' => __('Default is: 400 (Note: just input number, example: 350)', 'kadencetoolkit'),
				'id'   => $prefix . 'posthead_height',
				'type' => 'text_small',
			),
			array(
				'name' => __("Max Image/Slider Width", 'kadencetoolkit' ),
				'desc' => __("Default is: 1140 on fullwidth posts (Note: just input number, example: 650, does not apply to Carousel slider)", 'kadencetoolkit' ),
				'id'   => $prefix . 'posthead_width',
				'type' => 'text_small',
			),
			array(
				'name' => __('If Video Post', 'kadencetoolkit'),
				'desc' => __('Place Embed Code Here, works with youtube, vimeo...', 'kadencetoolkit'),
				'id'   => $prefix . 'post_video',
				'type' => 'textarea_code',
			),
								
			));
			$meta_boxes[] = array(
				'id'         => 'contact_metabox',
				'title'      => __('Contact Page Options', 'kadencetoolkit'),
				'pages'      => array( 'page' ), // Post type
				'show_on' => array('key' => 'page-template', 'value' => array( 'template-contact.php')),
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
			
			array(
                'name' => __('Use Contact Form', 'kadencetoolkit'),
                'desc' => '',
                'id' => $prefix .'contact_form',
                'type'    => 'select',
				'options' => array(
					array( 'name' => __('Yes', 'kadencetoolkit'), 'value' => 'yes', ),
					array( 'name' => __('No', 'kadencetoolkit'), 'value' => 'no', ),
				),
			),
			array(
				'name' => __('Contact Form Title', 'kadencetoolkit'),
				'desc' => __('ex. Send us an Email', 'kadencetoolkit'),
				'id'   => $prefix . 'contact_form_title',
				'type' => 'text',
			),
			array(
				'name' => __('Contact Form Email Recipient', 'kadencetoolkit'),
				'desc' => __('ex. joe@gmail.com', 'kadencetoolkit'),
				'id'   => $prefix . 'contact_form_email',
				'type' => 'text',
			),
			array(
                'name' => __('Use Simple Math Question', 'kadencetoolkit'),
                'desc' => 'Adds a simple math question to form.',
                'id' => $prefix .'contact_form_math',
                'type'    => 'select',
				'options' => array(
					array( 'name' => __('Yes', 'kadencetoolkit'), 'value' => 'yes', ),
					array( 'name' => __('No', 'kadencetoolkit'), 'value' => 'no', ),
				),
			),
			array(
                'name' => __('Use Map', 'kadencetoolkit'),
                'desc' => '',
                'id' => $prefix .'contact_map',
                'type'    => 'select',
				'options' => array(
					array( 'name' => __('No', 'kadencetoolkit'), 'value' => 'no', ),
					array( 'name' => __('Yes', 'kadencetoolkit'), 'value' => 'yes', ),
				),
			),
			array(
				'name' => __('Address', 'kadencetoolkit'),
				'desc' => __('Enter your Location', 'kadencetoolkit'),
				'id'   => $prefix . 'contact_address',
				'type' => 'text',
			),
			array(
				'name'    => __('Map Type', 'kadencetoolkit'),
				'desc'    => '',
				'id'      => $prefix . 'contact_maptype',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('ROADMAP', 'kadencetoolkit'), 'value' => 'ROADMAP', ),
					array( 'name' => __('HYBRID', 'kadencetoolkit'), 'value' => 'HYBRID', ),
					array( 'name' => __('TERRAIN', 'kadencetoolkit'), 'value' => 'TERRAIN', ),
					array( 'name' => __('SATELLITE', 'kadencetoolkit'), 'value' => 'SATELLITE', ),
				),
			),
			array(
				'name' => __('Map Zoom Level', 'kadencetoolkit'),
				'desc' => __('A good place to start is 15', 'kadencetoolkit'),
				'id'   => $prefix . 'contact_zoom',
				'std'  => '15',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('1 (World View)', 'kadencetoolkit'), 'value' => '1', ),
					array( 'name' => '2', 'value' => '2', ),
					array( 'name' => '3', 'value' => '3', ),
					array( 'name' => '4', 'value' => '4', ),
					array( 'name' => '5', 'value' => '5', ),
					array( 'name' => '6', 'value' => '6', ),
					array( 'name' => '7', 'value' => '7', ),
					array( 'name' => '8', 'value' => '8', ),
					array( 'name' => '9', 'value' => '9', ),
					array( 'name' => '10', 'value' => '10', ),
					array( 'name' => '11', 'value' => '11', ),
					array( 'name' => '12', 'value' => '12', ),
					array( 'name' => '13', 'value' => '13', ),
					array( 'name' => '14', 'value' => '14', ),
					array( 'name' => '15', 'value' => '15', ),
					array( 'name' => '16', 'value' => '16', ),
					array( 'name' => '17', 'value' => '17', ),
					array( 'name' => '18', 'value' => '18', ),
					array( 'name' => '19', 'value' => '19', ),
					array( 'name' => '20', 'value' => '20', ),
					array( 'name' => __('21 (Street View)', 'kadencetoolkit'), 'value' => '21', ),
					),
			),
			array(
				'name' => __('Map Height', 'kadencetoolkit'),
				'desc' => __('Default is 300', 'kadencetoolkit'),
				'id'   => $prefix . 'contact_mapheight',
				'type' => 'text_small',
			),
			));

	return $meta_boxes;
}


$the_theme = wp_get_theme();
if( ($the_theme->get( 'Name' ) == 'Virtue' && $the_theme->get( 'Version') >= '2.3.5') || ($the_theme->get( 'Template') == 'virtue') ) {
add_filter( 'cmb_meta_boxes', 'kadence_virtuetoolkit_metaboxes', 100 );
}
function kadence_virtuetoolkit_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_kad_';
	$meta_boxes[] = array(
				'id'         => 'post_video_metabox',
				'title'      => __('Post Video Box', 'kadencetoolkit'),
				'pages'      => array( 'post',), // Post type
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, 
				'fields' => array(
			
					array(
						'name' => __('If Video Post', 'kadencetoolkit'),
						'desc' => __('Place Embed Code Here, works with youtube, vimeo...', 'kadencetoolkit'),
						'id'   => $prefix . 'post_video',
						'type' => 'textarea_code',
					),
				),
	);
		$meta_boxes[] = array(
				'id'         => 'portfolio_post_metabox',
				'title'      => __('Portfolio Post Options', 'kadencetoolkit'),
				'pages'      => array( 'portfolio' ), // Post type
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
			
			array(
				'name'    => __('Project Layout', 'kadencetoolkit'),
				'desc'    => '<a href="http://docs.kadencethemes.com/virtue/#portfolio_posts" target="_new" >Whats the difference?</a>',
				'id'      => $prefix . 'ppost_layout',
				'type'    => 'radio_inline',
				'options' => array(
					array( 'name' => __('Beside', 'kadencetoolkit'), 'value' => 'beside', ),
					array( 'name' => __('Above', 'kadencetoolkit'), 'value' => 'above', ),
					array( 'name' => __('Three Rows', 'kadencetoolkit'), 'value' => 'three', ), 
				),
			),
			array(
				'name'    => __('Project Options', 'kadencetoolkit'),
				'desc'    => '',
				'id'      => $prefix . 'ppost_type',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Image', 'kadencetoolkit'), 'value' => 'image', ),
					array( 'name' => __('Image Slider', 'kadencetoolkit'), 'value' => 'flex', ),
					array( 'name' => __('Carousel Slider', 'kadencetoolkit'), 'value' => 'carousel', ),
					array( 'name' => __('Video', 'kadencetoolkit'), 'value' => 'video', ),
					array( 'name' => __('None', 'kadencetoolkit'), 'value' => 'none', ),
				),
			),
			array(
				'name' => __("Max Image/Slider Height", 'kadencetoolkit' ),
				'desc' => __("Default is: 450 <b>(Note: just input number, example: 350)</b>", 'kadencetoolkit' ),
				'id'   => $prefix . 'posthead_height',
				'type' => 'text_small',
			),
			array(
				'name' => __("Max Image/Slider Width", 'kadencetoolkit' ),
				'desc' => __("Default is: 670 or 1140 on <b>above</b> or <b>three row</b> layouts (Note: just input number, example: 650)</b>", 'kadencetoolkit' ),
				'id'   => $prefix . 'posthead_width',
				'type' => 'text_small',
			),
			array(
				'name' => __('Auto Play Slider?', 'kadencetoolkit'),
				'desc' => '',
				'id'   => $prefix . 'portfolio_autoplay',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Yes', 'kadencetoolkit'), 'value' => 'Yes', ),
					array( 'name' => __('No', 'kadencetoolkit'), 'value' => 'no', ),
				),
			),
			array(
				'name' => __('Value 01 Title', 'kadencetoolkit'),
				'desc' => __('ex. Project Type:', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val01_title',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Value 01 Description', 'kadencetoolkit'),
				'desc' => __('ex. Character Illustration', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val01_description',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Value 02 Title', 'kadencetoolkit'),
				'desc' => __('ex. Skills Needed:', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val02_title',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Value 02 Description', 'kadencetoolkit'),
				'desc' => __('ex. Photoshop, Illustrator', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val02_description',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Value 03 Title', 'kadencetoolkit'),
				'desc' => __('ex. Customer:', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val03_title',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Value 03 Description', 'kadencetoolkit'),
				'desc' => __('ex. Example Inc', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val03_description',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Value 04 Title', 'kadencetoolkit'),
				'desc' => __('ex. Project Year:', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val04_title',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Value 04 Description', 'kadencetoolkit'),
				'desc' => __('ex. 2013', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val04_description',
				'type' => 'text_medium',
			),
			array(
				'name' => __('External Website', 'kadencetoolkit'),
				'desc' => __('ex. Website:', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val05_title',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Website Address', 'kadencetoolkit'),
				'desc' => __('ex. http://www.example.com', 'kadencetoolkit'),
				'id'   => $prefix . 'project_val05_description',
				'type' => 'text_medium',
			),
			array(
						'name' => __('If Video Project', 'kadencetoolkit'),
						'desc' => __('Place Embed Code Here, works with youtube, vimeo...', 'kadencetoolkit'),
						'id'   => $prefix . 'post_video',
						'type' => 'textarea_code',
					),
			array(
				'name' => __('Similar Portfolio Item Carousel', 'kadencetoolkit'),
				'desc' => __('Display a carousel with similar portfolio items below project?', 'kadencetoolkit'),
				'id'   => $prefix . 'portfolio_carousel_recent',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('No', 'kadencetoolkit'), 'value' => 'no', ),
					array( 'name' => __('Yes - Display Recent Projects', 'kadencetoolkit'), 'value' => 'recent', ),
				),
			),
			array(
				'name' => __('Carousel Title', 'kadencetoolkit'),
				'desc' => __('ex. Similar Projects', 'kadencetoolkit'),
				'id'   => $prefix . 'portfolio_carousel_title',
				'type' => 'text_medium',
			),
				
		),
	);
$meta_boxes[] = array(
				'id'         => 'portfolio_metabox',
				'title'      => __('Portfolio Page Options', 'kadencetoolkit'),
				'pages'      => array( 'page' ), // Post type
				'show_on' => array('key' => 'page-template', 'value' => array( 'page-portfolio.php' )),
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
			
			array(
				'name'    => __('Columns', 'kadencetoolkit'),
				'desc'    => '',
				'id'      => $prefix . 'portfolio_columns',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Four Column', 'kadencetoolkit'), 'value' => '4', ),
					array( 'name' => __('Three Column', 'kadencetoolkit'), 'value' => '3', ),
					array( 'name' => __('Two Column', 'kadencetoolkit'), 'value' => '2', ),
				),
			),
			array(
                'name' => __('Portfolio Work Types', 'kadencetoolkit'),
                'desc' => '',
                'id' => $prefix .'portfolio_type',
                'type' => 'imag_select_taxonomy',
                'taxonomy' => 'portfolio-type',
            ),
            array(
				'name'    => __('Order Items By', 'kadencetoolkit'),
				'desc'    => '',
				'id'      => $prefix . 'portfolio_order',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Menu Order', 'kadencetoolkit'), 'value' => 'menu_order', ),
					array( 'name' => __('Title', 'kadencetoolkit'), 'value' => 'title', ),
					array( 'name' => __('Date', 'kadencetoolkit'), 'value' => 'date', ),
					array( 'name' => __('Random', 'kadencetoolkit'), 'value' => 'rand', ),
				),
			),
			array(
				'name'    => __('Items per Page', 'kadencetoolkit'),
				'desc'    => __('How many portfolio items per page', 'kadencetoolkit'),
				'id'      => $prefix . 'portfolio_items',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('All', 'kadencetoolkit'), 'value' => 'all', ),
					array( 'name' => '3', 'value' => '3', ),
					array( 'name' => '4', 'value' => '4', ),
					array( 'name' => '5', 'value' => '5', ),
					array( 'name' => '6', 'value' => '6', ),
					array( 'name' => '7', 'value' => '7', ),
					array( 'name' => '8', 'value' => '8', ),
					array( 'name' => '9', 'value' => '9', ),
					array( 'name' => '10', 'value' => '10', ),
					array( 'name' => '11', 'value' => '11', ),
					array( 'name' => '12', 'value' => '12', ),
					array( 'name' => '13', 'value' => '13', ),
					array( 'name' => '14', 'value' => '14', ),
					array( 'name' => '15', 'value' => '15', ),
					array( 'name' => '16', 'value' => '16', ),
				),
			),
			array(
				'name' => __('Set image height', 'kadencetoolkit'),
				'desc' => __('Default is 1:1 ratio <b>(Note: just input number, example: 350)</b>', 'kadencetoolkit'),
				'id'   => $prefix . 'portfolio_img_crop',
				'type' => 'text_small',
			),
			array(
				'name' => __('Display Item Work Types', 'kadencetoolkit'),
				'desc' => '',
				'id'   => $prefix . 'portfolio_item_types',
				'type' => 'checkbox',
			),
			array(
				'name' => __('Display Item Excerpt', 'kadencetoolkit'),
				'desc' => '',
				'id'   => $prefix . 'portfolio_item_excerpt',
				'type' => 'checkbox',
			),
			array(
				'name'    => __('Add Lightbox link in the top right of each item', 'kadencetoolkit'),
				'desc'    => '',
				'id'      => $prefix . 'portfolio_lightbox',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('No', 'kadencetoolkit'), 'value' => 'no', ),
					array( 'name' => __('Yes', 'kadencetoolkit'), 'value' => 'yes', ),
				),
			),
				
			));
$meta_boxes[] = array(
				'id'         => 'pagefeature_metabox',
				'title'      => __('Feature Page Options', 'kadencetoolkit'),
				'pages'      => array( 'page' ), // Post type
				'show_on' => array('key' => 'page-template', 'value' => array( 'page-feature.php', 'page-feature-sidebar.php')),
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
			
			array(
				'name'    => __('Feature Options', 'kadencetoolkit'),
				'desc'    => __('If image slider make sure images uploaded are at least 1140px wide.', 'kadencetoolkit'),
				'id'      => $prefix . 'page_head',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Image Slider', 'kadencetoolkit'), 'value' => 'flex', ),
					array( 'name' => __('Video', 'kadencetoolkit'), 'value' => 'video', ),
					array( 'name' => __('Image', 'kadencetoolkit'), 'value' => 'image', ),
				),
			),
			array(
				'name' => __("Slider Gallery", 'kadencetoolkit' ),
				'desc' => __("Add images for gallery here", 'kadencetoolkit' ),
				'id'   => $prefix . 'image_gallery',
				'type' => 'kad_gallery',
			),
			array(
				'name' => __('Max Image/Slider Height', 'kadencetoolkit'),
				'desc' => __('Default is: 400 <b>(Note: just input number, example: 350)</b>', 'kadencetoolkit'),
				'id'   => $prefix . 'posthead_height',
				'type' => 'text_small',
			),
			array(
				'name' => __("Max Image/Slider Width", 'kadencetoolkit' ),
				'desc' => __("Default is: 1140 <b>(Note: just input number, example: 650, does not apply to Carousel slider)</b>", 'kadencetoolkit' ),
				'id'   => $prefix . 'posthead_width',
				'type' => 'text_small',
			),
			array(
				'name'    => __('Use Lightbox for Feature Image', 'kadencetoolkit'),
				'desc'    => __("If feature option is set to image, choose to use lightbox link with image.", 'kadencetoolkit' ),
				'id'      => $prefix . 'feature_img_lightbox',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Yes', 'kadencetoolkit'), 'value' => 'yes', ),
					array( 'name' => __('No', 'kadencetoolkit'), 'value' => 'no', ),
				),
			),
			array(
				'name' => __('If Video Post', 'kadencetoolkit'),
				'desc' => __('Place Embed Code Here, works with youtube, vimeo...', 'kadencetoolkit'),
				'id'   => $prefix . 'post_video',
				'type' => 'textarea_code',
			),
				
			));
$meta_boxes[] = array(
				'id'         => 'contact_metabox',
				'title'      => __('Contact Page Options', 'kadencetoolkit'),
				'pages'      => array( 'page' ), // Post type
				'show_on' => array('key' => 'page-template', 'value' => array( 'page-contact.php')),
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
			
			array(
                'name' => __('Use Contact Form', 'kadencetoolkit'),
                'desc' => '',
                'id' => $prefix .'contact_form',
                'type'    => 'select',
				'options' => array(
					array( 'name' => __('Yes', 'kadencetoolkit'), 'value' => 'yes', ),
					array( 'name' => __('No', 'kadencetoolkit'), 'value' => 'no', ),
				),
			),
			array(
				'name' => __('Contact Form Title', 'kadencetoolkit'),
				'desc' => __('ex. Send us an Email', 'kadencetoolkit'),
				'id'   => $prefix . 'contact_form_title',
				'type' => 'text',
			),
			array(
                'name' => __('Use Simple Math Question', 'kadencetoolkit'),
                'desc' => 'Adds a simple math question to form.',
                'id' => $prefix .'contact_form_math',
                'type'    => 'select',
				'options' => array(
					array( 'name' => __('Yes', 'kadencetoolkit'), 'value' => 'yes', ),
					array( 'name' => __('No', 'kadencetoolkit'), 'value' => 'no', ),
				),
			),
			array(
                'name' => __('Use Map', 'kadencetoolkit'),
                'desc' => '',
                'id' => $prefix .'contact_map',
                'type'    => 'select',
				'options' => array(
					array( 'name' => __('No', 'kadencetoolkit'), 'value' => 'no', ),
					array( 'name' => __('Yes', 'kadencetoolkit'), 'value' => 'yes', ),
				),
			),
			array(
				'name' => __('Address', 'kadencetoolkit'),
				'desc' => __('Enter your Location', 'kadencetoolkit'),
				'id'   => $prefix . 'contact_address',
				'type' => 'text',
			),
			array(
				'name'    => __('Map Type', 'kadencetoolkit'),
				'desc'    => '',
				'id'      => $prefix . 'contact_maptype',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('ROADMAP', 'kadencetoolkit'), 'value' => 'ROADMAP', ),
					array( 'name' => __('HYBRID', 'kadencetoolkit'), 'value' => 'HYBRID', ),
					array( 'name' => __('TERRAIN', 'kadencetoolkit'), 'value' => 'TERRAIN', ),
					array( 'name' => __('SATELLITE', 'kadencetoolkit'), 'value' => 'SATELLITE', ),
				),
			),
			array(
				'name' => __('Map Zoom Level', 'kadencetoolkit'),
				'desc' => __('A good place to start is 15', 'kadencetoolkit'),
				'id'   => $prefix . 'contact_zoom',
				'std'  => '15',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('1 (World View)', 'kadencetoolkit'), 'value' => '1', ),
					array( 'name' => '2', 'value' => '2', ),
					array( 'name' => '3', 'value' => '3', ),
					array( 'name' => '4', 'value' => '4', ),
					array( 'name' => '5', 'value' => '5', ),
					array( 'name' => '6', 'value' => '6', ),
					array( 'name' => '7', 'value' => '7', ),
					array( 'name' => '8', 'value' => '8', ),
					array( 'name' => '9', 'value' => '9', ),
					array( 'name' => '10', 'value' => '10', ),
					array( 'name' => '11', 'value' => '11', ),
					array( 'name' => '12', 'value' => '12', ),
					array( 'name' => '13', 'value' => '13', ),
					array( 'name' => '14', 'value' => '14', ),
					array( 'name' => '15', 'value' => '15', ),
					array( 'name' => '16', 'value' => '16', ),
					array( 'name' => '17', 'value' => '17', ),
					array( 'name' => '18', 'value' => '18', ),
					array( 'name' => '19', 'value' => '19', ),
					array( 'name' => '20', 'value' => '20', ),
					array( 'name' => __('21 (Street View)', 'kadencetoolkit'), 'value' => '21', ),
					),
			),
			array(
				'name' => __('Map Height', 'kadencetoolkit'),
				'desc' => __('Default is 300', 'kadencetoolkit'),
				'id'   => $prefix . 'contact_mapheight',
				'type' => 'text_small',
			),
				
			));
$meta_boxes[] = array(
				'id'         => 'virtue_post_gallery',
				'title'      => __("Slider Images", 'kadencetoolkit'),
				'pages'      => array( 'post', 'portfolio'), // Post type
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
			array(
				'name' => __("Slider Gallery", 'kadencetoolkit' ),
				'desc' => __("Add images for gallery here", 'kadencetoolkit' ),
				'id'   => $prefix . 'image_gallery',
				'type' => 'kad_gallery',
			),
	));
	return $meta_boxes;
}

add_action( 'init', 'initialize_kadence_toolkit_meta_boxes', 10 );
function initialize_kadence_toolkit_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) ) {
		require_once 'cmb/init.php';
	}

}