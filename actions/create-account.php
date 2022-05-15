<?php

use TinCan\TCData;
use TinCan\TCException;
use TinCan\TCJSONResponse;
use TinCan\TCMailer;
use TinCan\TCMailTemplate;
use TinCan\TCObject;
use TinCan\TCPendingUser;
use TinCan\TCURL;
use TinCan\TCUser;

/**
 * Tin Can create account handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../tc-config.php';
// Composer autoload.
require TC_BASE_PATH.'/vendor/autoload.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/core/class-tc-mailer.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

require 'class-tc-json-response.php';

$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
// Don't trim password. Spaces are permitted anywhere in the password.
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

$db = new TCData();

try {
  $settings = $db->load_settings();
} catch (TCException $e) {
  echo $e->getMessage();
  exit;
}

$user = new TCUser();

// Validate username.
if (!$user->validate_username($username)) {
  $error = TCUser::ERR_USER;
}
// Validate email.
if (empty($error) && !$user->validate_email($email)) {
  $error = TCUser::ERR_EMAIL;
}
// Validate password.
if (empty($error) && !$user->validate_password($password)) {
  $error = TCUser::ERR_PASSWORD;
}

// Check for existing username / email.
if (empty($error)) {
  $existing_user = $db->load_objects($user, [], [['field' => 'username', 'value' => $username]]);

  if (!empty($existing_user)) {
    $error = TCUser::ERR_USERNAME_EXISTS;
  }
}

if (empty($error)) {
  $existing_user = $db->load_objects($user, [], [['field' => 'email', 'value' => $email]]);

  if (!empty($existing_user)) {
    $error = TCUser::ERR_EMAIL_EXISTS;
  }
}

$saved_user = null;

if (empty($error)) {
  $user->username = $username;
  $user->email = $email;
  $user->password = $user->get_password_hash($password);
  $user->role_id = $settings['default_user_role'];
  $user->created_time = time();
  $user->updated_time = time();

  $saved_user = $db->save_object($user);

  // Verify user has been created.
  if (empty($saved_user)) {
    $error = TCObject::ERR_NOT_SAVED;
  }
}

if (empty($error)) {
  // Successfully created account. Set up account confirmation.
  $pending_user = new TCPendingUser();
  $pending_user->user_id = $user->user_id;
  $pending_user->confirmation_code = $pending_user->generate_confirmation_code();

  $saved_pending_user = $db->save_object($pending_user);

  if (empty($saved_pending_user)) {
    $error = TCObject::ERR_NOT_SAVED;
  }
}

if (empty($error)) {
  $confirmation_url = $settings['base_url'].'/actions/confirm-account.php?code='.$pending_user->confirmation_code;

  // Send password reset code to the user.
  $mailer = new TCMailer();

  // Load email template.
  // TODO: Error handling.
  $mail_template = $db->load_object(new TCMailTemplate(), $settings['mail_confirm_account']);
  $mail_subject = $mail_template->mail_template_name;
  $mail_content = $mailer->tokenize_template($mail_template, ['url' => $confirmation_url]);

  $recipients = [
    ['name' => $user->username, 'email' => $user->email],
  ];

  $mailer->send_mail($settings['site_email_name'],
    $settings['site_email_address'],
    $mail_subject,
    $mail_content,
    $recipients);
}

if (!empty($ajax)) {
  $response = new TCJSONResponse();

  $response->success = (empty($error));
  $response->errors = [$error];

  exit($response->get_output());
} else {
  $destination = '';

  if (empty($error)) {
    // Send user to the create account page with success message.
    $destination = TCURL::create_url($settings['page_create_account'], ['status' => 'sent']);
  } else {
    // Send user back to the create account page with an error.
    $destination = TCURL::create_url($settings['page_create_account'], [
      'username' => $username,
      'email' => $email,
      'error' => $error,
    ]);
  }

  header('Location: '.$destination);
  exit;
}
