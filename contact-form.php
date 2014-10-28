<?php


// Function for filtering input values.
function fix_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


function validate_form() {

  if( isset($_POST['full_name']) ) {

    $inputs = array('full_name' => '', 'email' => '', 'message' => '');

    if( isset($_POST['simple']) && !empty($_POST['simple']) ) {
      return array( 'error', 'An error occured try to refresh the page and try again' );
    }

    foreach ($inputs as $key => $value) {
      if( isset($_POST[$key]) && $input = fix_input($_POST[$key]) ) {
        $inputs[$key] = $input;
      } else {
        return array( 'error', 'You have to fill in all fields.');
      }
    }

    if (!filter_var($inputs['email'], FILTER_VALIDATE_EMAIL)) {
      return array('error', 'This ('. $inputs['email'] .') email address is considered invalid.');
    }

    $msg = 'Message from '. $inputs['name'] .' through the skilocker form. <br>'
      . $inputs['message'] .'<br>'. $inputs['email'];

    if(mail("pwallberg@gmail.com", 'The ski locker', $msg )) {
      return array('success', 'The message was sent!');
    } else {
      return array('error', 'The message could not be sent.');
    }
  }
}

$message = validate_form();
