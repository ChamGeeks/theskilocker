<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 11/21/13
 * Time: 16:27


add_action('media_buttons_context',  'dh_ptp_custom_button');

function dh_ptp_custom_button($context) {

    //path to my icon
    $img = 'penguin.png';

    //our popup's title
    $title = 'An Inline Popup!';

    //append the icon
    $context .= "<a title='{$title}' href='#'>
      <img src='{$img}' /></a>";

    return $context;
}
 */