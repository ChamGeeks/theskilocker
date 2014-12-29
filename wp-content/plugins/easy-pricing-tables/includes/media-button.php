<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function dh_ptp_media_button()
{
    global $pagenow, $typenow, $wp_version;
    
    $button_title = __('Insert pricing table', PTP_LOC);
    $output = '';
    
    // Show button only in post and page edit screens
    if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) && $typenow != 'download' ) {
        /* check current WP version */
        if ( version_compare( $wp_version, '3.5', '<' ) ) {
                $output = '<a href="#TB_inline?width=640&inlineId=dh-ptp-pricing-table-thickbox" class="thickbox" title="' . $button_title . '">' . $button_title . '</a>';
        } else {
                $img = '<span class="wp-media-buttons-icon" id="dh-ptp-media-button"></span>';
                $output = '<a href="#TB_inline?width=640&inlineId=dh-ptp-pricing-table-thickbox" class="thickbox button" title="' . $button_title . '" style="padding-left: .4em;">' . $img . $button_title . '</a>';
        }
    }
    
    echo $output;
}
add_action( 'media_buttons', 'dh_ptp_media_button', 11);

function dh_ptp_media_button_thickbox()
{
    global $pagenow, $typenow, $post;

    // Only run in post/page creation and edit screens
    if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) && $typenow != 'download' ) { ?>
    
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#dh-ptp-pricing-table-insert').on('click', function () {
                    var id = $('#dh-ptp-pricing-table').val();
                    
                    // Return early if no download is selected
                    if ('' === id) {
                        alert(__('You must choose a download', PTP_LOC));
                        return;
                    }
                    window.send_to_editor('[easy-pricing-table id="' + id + '"]');
                    
                    // Tracking
                    jQuery.ajax({
                        type: "POST",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {
                            action: "dh_ptp_tracking_deploy",
                            id: id
                        }
                    });
                });
            });
        </script>
        <style>
            
            #dh-ptp-media-button {
			background: url(<?php echo PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ept-icon-16x16.png'; ?>) 0 -1px no-repeat;
			background-size: 16px 16px;
		}
        </style>
        <div id="dh-ptp-pricing-table-thickbox" style="display: none;">
            <div class="wrap" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                <p><?php _e('Use the form below to insert the shortcode for a pricing table.', PTP_LOC); ?></p>
                <div>
                    <select id="dh-ptp-pricing-table">
                        <option value=""><?php _e('Please select...', PTP_LOC); ?></option>
                        <?php
                            // Fetch all pricing tables
                            $post_clone = $post;
                            $query = new WP_Query(array('post_type'=>'easy-pricing-table', 'post_status'=>array('publish', 'draft'), 'posts_per_page'=>-1));
                            if ( $query->have_posts() ) : 
                                while ( $query->have_posts() ) : $query->the_post();
                                    echo '<option value="' . get_the_ID() . '">' . (get_the_title()?get_the_title():'(no title)') . '</option>';
                                endwhile;
                            endif;
                            
                            // Restore original Post Data
                            $post = $post_clone;
                        ?>
                    </select>
                </div>
                <p class="submit">
                    <input type="button" id="dh-ptp-pricing-table-insert" class="button-primary" value="<?php _e('Insert', PTP_LOC); ?>"/>
                    <a id="dh-ptp-pricing-table-cancel" class="button-secondary" onclick="tb_remove();" title="<?php _e('Cancel', PTP_LOC); ?>"><?php _e('Cancel', PTP_LOC); ?></a>
                </p>
            </div>
        </div>
    <?php
    }
}
add_action( 'admin_footer', 'dh_ptp_media_button_thickbox' );
?>