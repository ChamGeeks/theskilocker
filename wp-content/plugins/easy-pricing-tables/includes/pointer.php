<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Load pointers 
function dh_ptp_pointer_load($hook_suffix)
{
    // Sanity checks
    if ((!current_user_can('manage_options') || !is_super_admin()) || (get_bloginfo( 'version' ) < '3.3')) {
		return;
    }
  
    // Load usage tracking pointer popup
    $usage_tracking = get_option('dh_ptp_allow_tracking');
    if (!in_array($usage_tracking, array('yes', 'no'))) {
        // Load CSS and JS elements for pointer popup
        wp_enqueue_style('wp-pointer');
        wp_enqueue_script('wp-pointer');
        
        // Add action hook
        add_action('admin_print_footer_scripts', 'dh_ptp_usage_tracking_pointer');
    }
    
    // Load mailing list pointer popup
    global $current_screen;
    global $pagenow;
    
    // Show mailing list pointer popup once
    $mailing_list = get_option('dh_ptp_mailing_list');
    if ( 'easy-pricing-table' == $current_screen->post_type &&
           (( isset($_REQUEST['action']) && 'edit' == $_REQUEST['action']) || 'post-new.php' == $pagenow) && 
           in_array($usage_tracking, array('yes', 'no')) &&
           !in_array($mailing_list, array('yes', 'no'))) {
        
        wp_enqueue_style('wp-pointer');
        wp_enqueue_script('wp-pointer');
        add_action('admin_print_footer_scripts', 'dh_ptp_mailing_list_pointer');        
    }
}
add_action('admin_enqueue_scripts', 'dh_ptp_pointer_load');

// Usage Tracking
function dh_ptp_usage_tracking_pointer() 
{
    // Ajax request template    
    $ajax = '
        jQuery.ajax({
            type: "POST",
            url:  "'.admin_url('admin-ajax.php').'",
            data: {action: "dh_ptp_usage_tracking", nonce: "'.wp_create_nonce('dh_ptp_activate_tracking').'", allow_tracking: "%s" }
        });
    ';
    
    // Target
    $id = '#wpadminbar';
    
    // Buttons
    $button_1_title = __('Do not allow tracking', PTP_LOC);
    $button_1_fn    = sprintf($ajax, 'no');
    $button_2_title = __('Allow tracking', PTP_LOC);
    $button_2_fn    = sprintf($ajax, 'yes');
    
    // Content
    $content  = '<h3>' . __('Help Improve Easy Pricing Tables', PTP_LOC) . '</h3>';
    $content .= '<p>' . __('Thanks for installing Easy Pricing Tables. Please help us improve this plugin by gathering usage stats so we know which features to improve and which plugins and themes to test with.', PTP_LOC) . '</p>';
    
    // Options
    $options = array(
        'content' => $content,
        'position' => array('edge' => 'top', 'align' => 'center')
    );
    
    dh_ptp_print_script($id, $options, $button_1_title, $button_2_title, $button_1_fn, $button_2_fn);
}

function dh_ptp_usage_tracking_pointer_ajax()
{
    if(!wp_verify_nonce($_POST['nonce'], 'dh_ptp_activate_tracking')) {
        die ('No tricky business!');
    }
    
    $result = ($_POST['allow_tracking'] == 'yes')?'yes':'no';
    
	if ($result == 'yes') {
		if (function_exists('dh_ptp_track_event')) {
			dh_ptp_track_event('Plugin activated');
		}
	}
	
    update_option('dh_ptp_allow_tracking', $result);
    exit();
}
add_action( 'wp_ajax_dh_ptp_usage_tracking', 'dh_ptp_usage_tracking_pointer_ajax');

// Add mailing list subscription
function dh_ptp_mailing_list_pointer() 
{
	global $current_user;
	
	// Get current user info
    get_currentuserinfo();
	
    // Ajax request template    
    $ajax = '
        jQuery.ajax({
            type: "POST",
            url:  "'.admin_url('admin-ajax.php').'",
            data: {action: "dh_ptp_mailing_list", email: jQuery("#ept_email").val(), nonce: "'.wp_create_nonce('dh_ptp_mailing_list').'", subscribe: "%s" }
        }).done(function( html ) {
                      eval( html  );
         });
    ';
    
    // Target
    $id = '#wpadminbar';
    
    // Buttons
    $button_1_title = __('No, thanks', PTP_LOC);
    $button_1_fn    = sprintf($ajax, 'no');
    $button_2_title = __("FREE DOWNLOAD", PTP_LOC);
    $button_2_fn    = sprintf($ajax, 'yes');
    
    // Content
    $content  = '<h3>' . __('How To Easily Get More Sales', PTP_LOC) . '</h3>';
    $content .= '<p>' . __("Instead of watching 99% of your visitors bounce, imagine you could increase your conversion rate and make more money. Find out how in our free guide on how to easily get more sales (by building pricing tables that convert).", PTP_LOC) . '</p>';
    $content .= '<p>' . '<input type="text" name="ept_email" id="ept_email" value="' . $current_user->user_email . '" style="width: 100%"/>' . '</p>';
	
    // Options
    $options = array(
        'content' => $content,
        'position' => array('edge' => 'top', 'align' => 'center')
    );
    
    dh_ptp_print_script($id, $options, $button_1_title, $button_2_title, $button_1_fn, $button_2_fn);
}

function dh_ptp_mailing_list_pointer_ajax()
{
    global $current_user;

    // Verify nonce
    if(!wp_verify_nonce($_POST['nonce'], 'dh_ptp_mailing_list') && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        die ('No tricky business!');
    }
    
    // Check status
    $result = ($_POST['subscribe'] == 'yes')?'yes':'no';
    if ($result == 'no') {
	dh_ptp_crash_course('No, thanks');
        update_option('dh_ptp_mailing_list', 'no');
        exit();
    } else {
	dh_ptp_crash_course('Lets do it!');
    }
    
    // Get current user info
    get_currentuserinfo();
    
    
   dh_ptp_add_subscriber(
                'https://www.getdrip.com/forms/7564307/submissions',
		
		array(  'fields[name]'  => $current_user->display_name,
			'fields[email]' => $_POST['email'], //$current_user->user_email,,
					)
	);
                
    update_option('dh_ptp_mailing_list', $result);
    
    // After custommers "sign up", display another pointer to ask them check the confirm email  
    $button_2_title = false;
    $kind_of_email_link = '';
    if ( strpos( $_POST['email'] , '@yahoo' ) !== false ) {
        $button_2_title = 'Go to Yahoo! Mail';
        $kind_of_email_link = 'https://mail.yahoo.com/';
    } elseif ( strpos( $_POST['email'] , '@hotmail' ) !== false )
    {
        $button_2_title = 'Go to Hotmail';
        $kind_of_email_link = 'https://www.hotmail.com/';
    } elseif ( strpos( $_POST['email'] , '@gmail' ) !== false )
    {
        $button_2_title = 'Go to Gmail';
        $kind_of_email_link = 'https://mail.google.com/';
    } elseif ( strpos( $_POST['email'] , '@aol' ) !== false ) 
    {
        $button_2_title = 'Go to AOL Mail';
        $kind_of_email_link = 'https://mail.aol.com/';
    }
    
    $button_2_func = "window.open('$kind_of_email_link', '_blank');";
    
    // Target
    $id = '#wpadminbar';
    
    // Buttons
    $button_1_title = __('Close', PTP_LOC);
    
    // Content
    $content  = '<h3>' . __('Please confirm your email', PTP_LOC) . '</h3>';
    $content .= '<p>' . __("Thanks! For privacy reasons you'll have to confirm your email. Please check your email inbox.", PTP_LOC) . '</p>';
    
    // Options
    $options = array(
        'content' => $content,
        'position' => array('edge' => 'top', 'align' => 'center')
    );
    
    dh_ptp_print_script($id, $options, $button_1_title, $button_2_title , '' , $button_2_func , true);    
    
    exit();
}
add_action( 'wp_ajax_dh_ptp_mailing_list', 'dh_ptp_mailing_list_pointer_ajax');

function dh_ptp_add_subscriber($url, $payload = array())
{
                $data = http_build_query($payload);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC) ; 
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)"); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_URL, $url);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
}


// Print JS Content
function dh_ptp_print_script($selector, $options, $button1, $button2 = false, $button1_fn = '', $button2_fn = '', $isCallBackFunc = false)
{ 
    if( !$isCallBackFunc ) {
    ?>
    <script type="text/javascript">
		//<![CDATA[
            (function ($) {
                <?php 
          }
                ?>
                var dh_ptp_pointer_options = <?php echo json_encode( $options ); ?>, setup;
     
                dh_ptp_pointer_options = $.extend(dh_ptp_pointer_options, {
                    buttons:function (event, t) {
                        button = jQuery('<a id="pointer-close" style="margin-left:5px" class="button-secondary">' + '<?php echo $button1; ?>' + '</a>');
                        button.bind('click.pointer', function () {
                            t.element.pointer('close');
                        });
                        return button;
                    },
                    close:function () {
                    }
                });
     
                setup = function () {
                    $('<?php echo $selector; ?>').pointer(dh_ptp_pointer_options).pointer('open');
                    <?php if ( $button2 ) : ?>
                        jQuery('#pointer-close').after('<a id="pointer-primary" class="button-primary">' + '<?php echo $button2; ?>' + '</a>');
                        jQuery('#pointer-primary').click(function () {
                            <?php echo $button2_fn; ?>
                            $('<?php echo $selector; ?>').pointer('close');
                        });
                        
                        jQuery('#ept_email').keypress(function ( event ) {
                             if ( event.which == 13 ) {
                                <?php echo $button2_fn; ?>
                                $('<?php echo $selector; ?>').pointer('close');
                             }
                            
                        });
                        
                        jQuery('#pointer-close').click(function () {
                            <?php echo $button1_fn; ?>
                            $('<?php echo $selector; ?>').pointer('close');
                        });
                    <?php endif; ?>
                };
 
                $(document).ready(setup);
          <?php if( !$isCallBackFunc ) { ?>
          })(jQuery);
        //]]>
	</script>
    <?php
          }
}
?>