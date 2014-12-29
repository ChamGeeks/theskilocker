<?php

// import Mixpanel
require ( PTP_PLUGIN_PATH . '/includes/libraries/mixpanel/Mixpanel.php');

/**
 * Tracks an event using the Mixpanel API if the user opted into tracking.
 * Example: dh_ptp_track_event("add to cart clicked", array("label" => "sign-up"));
 * 
 * @param  string $dh_ptp_event      [The name of the event that is being fired.]
 * @param  array $dh_ptp_properties  [Additional properties to track. (optional)]
 */
function dh_ptp_track_event($dh_ptp_event, $dh_ptp_properties = array())
{
    // only track events if the user agreed
    $dh_ptp_usage_tracking = get_option('dh_ptp_allow_tracking');
    if ($dh_ptp_usage_tracking == 'yes') 
    {
        // get the Mixpanel class instance
                                     
        $mp = Mixpanel::getInstance("1064083f4aaf3eed31d0fdf1c308365c");
        
        // Set user id: site url =  (url + site name) encoded
        $user_id = base64_encode(site_url().' '.get_bloginfo('name'));    
        
        // associate user to all subsequent track calls
        $mp->identify($user_id);
        
        // track the event
        $mp->track($dh_ptp_event, $dh_ptp_properties);
    }
}



?>