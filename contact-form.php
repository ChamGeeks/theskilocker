<?php
require 'vendor/autoload.php';

// Function for filtering input values.
function fix_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function validate_form() {
  $inputs = array('full_name' => '', 'email' => '', 'message' => '');

  if( isset($_POST['simple']) && !empty($_POST['simple']) ) {
    return array( 'error', 'An error occurred. Please try again.' );
  }

  foreach ($inputs as $key => $value) {
    if (array_key_exists($key, $_POST)) {
      $inputs[$key] = fix_input($_POST[$key]);
    } else {
      return array( 'error', 'Please fill in all fields.');
    }
  }

  if (!filter_var($inputs['email'], FILTER_VALIDATE_EMAIL)) {
    return array('error', 'This ('. $inputs['email'] .') email address is invalid.');
  }

  $msg = 'Message from '. $inputs['full_name'] .' through the skilocker form. <br>'
    . $inputs['message'] .'<br>'. $inputs['email'];

  $mail = new PHPMailer(true);
  try {
    $mail->isHTML();
    $mail->addAddress('pwallberg@gmail.com');
    $mail->Subject = 'The ski locker';
    $mail->Body = $msg;
    $mail->send();
    return array('success', 'The message was sent!');
  } catch (Exception $e) {
    return array('error', 'The message could not be sent.');
  }
}

$message = validate_form();
