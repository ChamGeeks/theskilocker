<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 11/15/13
 * Time: 15:28
 */


function dh_ptp_simple_flat_css($id, $meta)
{
    /**
     * Overall Styles *
     */
    
    // get rounded corner width
    $rounded_corner_width = (isset($meta['rounded-corners']))?$meta['rounded-corners']:'0px';
    
    /**
     * Font Styles
     */
    
    // most popular
    $most_popular_font_size = (isset($meta['most-popular-font-size']))?$meta['most-popular-font-size']:0.9;
    $most_popular_font_size_type = (isset($meta['most-popular-font-size-type']))?$meta['most-popular-font-size-type']:"em";
    
    // plan name
    $plan_name_font_size = (isset($meta['plan-name-font-size']))?$meta['plan-name-font-size']:1;
    $plan_name_font_size_type = (isset($meta['plan-name-font-size-type']))?$meta['plan-name-font-size-type']:"em";
    
    // price
    $price_font_size = (isset($meta['price-font-size']))?$meta['price-font-size']:1.25;
    $price_font_size_type = (isset($meta['price-font-size-type']))?$meta['price-font-size-type']:"em";
    
    // bullet item
    $bullet_item_font_size = (isset($meta['bullet-item-font-size']))?$meta['bullet-item-font-size']:0.875;
    $bullet_item_font_size_type = (isset($meta['bullet-item-font-size-type']))?$meta['bullet-item-font-size-type']:"em";
    
    // button
    $button_font_size = (isset($meta['button-font-size']))?$meta['button-font-size']:1;
    $button_font_size_type = (isset($meta['button-font-size-type']))?$meta['button-font-size-type']:"em";
    
    // get featured button font color
    $featured_button_font_color = (isset($meta['featured-button-font-color']))?$meta['featured-button-font-color']:'#ffffff';
    
    // get featured button color
    $featured_button_color = (isset($meta['featured-button-color']))?$meta['featured-button-color']:'#3498db';
    
    // get featured button  border color
    $featured_button_border_color = (isset($meta['featured-button-border-color']))?$meta['featured-button-border-color']:'#2980b9';
    
    // get featured button hover color
    $featured_button_hover_color = (isset($meta['featured-button-hover-color']))?$meta['featured-button-hover-color']:'#2980b9';
    
    // non-featured buttons
    // get  button font color
    $button_font_color = (!empty($meta['button-font-color']))?$meta['button-font-color']:'#ffffff';
    
    // get button color
    $button_color = (!empty($meta['button-color']))?$meta['button-color']:'#e74c3c';
    
    // get button border color
    $button_border_color = (isset($meta['button-border-color']))?$meta['button-border-color']:'#c0392b';
    
    // get button hover color
    $button_hover_color = (isset($meta['button-hover-color']))?$meta['button-hover-color']:'#c0392b';
    ?>
    
    #ptp-<?php echo $id ?> ul li {list-style-type: none;}
    #ptp-<?php echo $id ?> ul.ptp-item-container {
        border-radius: <?php echo $rounded_corner_width; ?>;
        padding: 0px;
        margin-left: 0px;
        margin-right: 0px;
    }
    #ptp-<?php echo $id ?> ul.ptp-item-container li{
        list-style-type: none;
        margin: 0px; 
    }
    #ptp-<?php echo $id ?> li.ptp-plan{
        border-top-right-radius: <?php echo $rounded_corner_width; ?>;
        border-top-left-radius: <?php echo $rounded_corner_width; ?>;
        font-size: <?php echo $plan_name_font_size . $plan_name_font_size_type; ?>;
    }
    #ptp-<?php echo $id ?> li.ptp-price{
        font-size: <?php echo $price_font_size . $price_font_size_type; ?>;
    }
    #ptp-<?php echo $id ?> li.ptp-cta{
        border-bottom-right-radius: <?php echo $rounded_corner_width; ?>;
        border-top-left-radius: <?php echo $rounded_corner_width; ?>;
    }
    #ptp-<?php echo $id ?> a.ptp-button{
        border-radius: <?php echo $rounded_corner_width; ?>;
        font-size: <?php echo $button_font_size.$button_font_size_type; ?>;
        color: <?php echo $button_font_color; ?>;
        background-color: <?php echo $button_color; ?>;
        border-bottom: <?php echo $button_border_color;?> 4px solid;
        margin: 0 0 1.25em;
    }
    #ptp-<?php echo $id ?> a.ptp-button:hover{
        background-color: <?php echo $button_hover_color; ?>
    }

    div#ptp-<?php echo $id ?> .ptp-highlight a.ptp-button{
        color: <?php echo $featured_button_font_color; ?>;
        background-color: <?php echo $featured_button_color; ?>;
        border-bottom: <?php echo $featured_button_border_color;?> 4px solid;
    }
    div#ptp-<?php echo $id ?> .ptp-highlight a.ptp-button:hover{
        background-color: <?php echo $featured_button_hover_color; ?>;
    }
    #ptp-<?php echo $id ?> li.ptp-bullet-item{
        font-size: <?php echo $bullet_item_font_size.$bullet_item_font_size_type; ?>;
    }
    #ptp-<?php echo $id ?> div.ptp-most-popular{
        border-radius: <?php echo $rounded_corner_width; ?>;
        font-size: <?php echo $most_popular_font_size.$most_popular_font_size_type; ?>;
    }
    <?php
}


/**
 * Generate our simple flat pricing table HTML
 * @return [type]
 */
function dh_ptp_generate_simple_flat_pricing_table_html ($id) {
    global $features_metabox;
    global $meta;

    $meta = get_post_meta($id, $features_metabox->get_the_id(), TRUE);

    /**
     * the string to be returned that includes the pricing table html
     * @var string
     */

    $loop_index = 0;
    $pricing_table_html = '<div id="ptp-'. $id .'" class="ptp-pricing-table">';
    foreach ($meta['column'] as $column)
    {
        // Note: beneath ifs are to prevent 'undefined variable notice'. It wasn't possible to put this code into a function since the passing argument might be undefined.

        //<editor-fold desc="get settings relevant to current column">
        // get plan name
        $planname = isset($column['planname'])?$column['planname']:'';

        // get plan price
        $planprice = isset($column['planprice'])?$column['planprice']:'';

        $planfeatures = isset($column['planfeatures'])?$column['planfeatures']:'';

        // get plan price
        $buttonurl = isset($column['buttonurl'])?$column['buttonurl']:'';

        // get plan price
        $buttontext = isset($column['buttontext'])?$column['buttontext']:'';
        //</editor-fold>

        //<editor-fold desc="set html based on if our current column is featured">
        if(isset($column['feature'])) {
            if ($column['feature'] == "featured") {
                $most_popular_text = isset($meta['most-popular-label-text'])?$meta['most-popular-label-text']:'Most Popular';
                
                $feature = "ptp-highlight";
                $feature_label = '<div class="ptp-most-popular">'.$most_popular_text.'</div>';
            } else {
                $feature = '';
                $feature_label = '<div class="ptp-not-most-popular">&nbsp;</div>';
            }
        } else {
            $feature = '';
            $feature_label = '<div class="ptp-not-most-popular">&nbsp;</div>';
        }
        //</editor-fold>

        // create the html code
        $pricing_table_html .= '
		<div class="ptp-col ' . dh_ptp_get_number_of_columns() . ' '. $feature . ' ptp-col-id-' . $loop_index . '">'
            . $feature_label .
            '<ul class="ptp-item-container">
				<li class="ptp-plan">' . $planname . '</li>
		  		<li class="ptp-price">' . $planprice . '</li>'
            . dh_ptp_features_to_html_simple_flat($planfeatures,dh_ptp_get_max_number_of_features()) . '
	  			<li class="ptp-cta">
	  				<a class="ptp-button" href="' . $buttonurl . '">' . $buttontext . '</a>
	  			</li>
			</ul>
		</div>
		';

        $loop_index++;
    }

    $pricing_table_html .= '</div>';

    return $pricing_table_html;
}

/**
 * Returns the appropriate HTML class depending on how many columns our pricing table has
 * @return string
 */
function dh_ptp_get_number_of_columns()
{
    global $meta;

    switch (count($meta['column'])) {
        case 1:
            $number_of_columns = "ptp-one-col";
            break;
        case 2:
            $number_of_columns = "ptp-two-col";
            break;
        case 3:
            $number_of_columns = "ptp-three-col";
            break;
        case 4:
            $number_of_columns = "ptp-four-col";
            break;
        case 5:
            $number_of_columns = "ptp-five-col";
            break;
        case 6:
            $number_of_columns = "ptp-six-col";
            break;
        case 7:
            $number_of_columns = "ptp-seven-col";
            break;
        case 8:
            $number_of_columns = "ptp-eight-col";
            break;
        case 9:
            $number_of_columns = "ptp-nine-col";
            break;
        case 10:
            $number_of_columns = "ptp-ten-col";
            break;
        default:
            $number_of_columns = "ptp-more-col";
            break;
    }

    return $number_of_columns;
}

/**
 * Returns the highest number of features that one of our columns uses (needed to create blank rows)
 * @return int
 */
function dh_ptp_get_max_number_of_features(){
    global $meta;
    $max_number_of_features = 0;

    // go through all columns
    foreach ($meta['column'] as $column)
    {
        if(isset($column['planfeatures']))
        {
            // get number of features
            $col_number_of_features = count( explode( "\n", $column['planfeatures'] ) );

            if ($col_number_of_features > $max_number_of_features)
                $max_number_of_features = $col_number_of_features;
        }
    }

    return $max_number_of_features;
}


/**
 * Generate HTML code for our features
 * @param $dh_ptp_plan_features - this is an array containing all features
 * @param $dh_ptp_max_number_of_features - the highest number of features that one of our columns uses
 * @return string - the html string containing all features
 */
function dh_ptp_features_to_html_simple_flat ($dh_ptp_plan_features, $dh_ptp_max_number_of_features){

    // the string to be returned
    $dh_ptp_feature_html = "";

    // explode string into a useable array
    $dh_ptp_features = explode("\n", $dh_ptp_plan_features);

    //how many features does this column have?
    $this_columns_number_of_features = count($dh_ptp_features);

    //add each feature to $dh_ptp_feature_html
    for ($iterator=0; $iterator<$dh_ptp_max_number_of_features; $iterator++)
    {
        if ($iterator < $this_columns_number_of_features)
        {
            if ($dh_ptp_features[$iterator] == "") {
                $dh_ptp_feature_html .= '<li class="ptp-bullet-item ptp-row-id-'.$iterator.'">&nbsp;</li>';
            }
            else
                $dh_ptp_feature_html .= '<li class="ptp-bullet-item ptp-row-id-'.$iterator.'">' . $dh_ptp_features[$iterator] . '</li>';
        } else {
            $dh_ptp_feature_html .= '<li class="ptp-bullet-item ptp-row-id-'.$iterator.'">&nbsp;</li>';
        }
    }

    // return the features html
    return $dh_ptp_feature_html;
}
