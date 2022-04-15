<?php
/**
 * Tin Can create account handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../tc-config.php';

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

// TODO: Validate username.
// TODO: Validate email.
// TODO: Validate password.

$saved_user = null;

if (empty($error)) {
  $db = new TCData();

  $settings = $db->load_settings();

  $user = new TCUser();

  $user->username = $username;
  $user->email = $email;
  $user->password = $user->get_password_hash($password);
  $user->role_id = $settings['default_user_role'];
  $user->created_time = time();
  $user->updated_time = time();

  $saved_user = $db->save_object($user);
}

// Verify user has been created.
if (empty($saved_user)) {
  $error = TCObject::ERR_NOT_SAVED;
}

if (empty($error)) {
  // Successfully created account. Create the user's session.
  $session = new TCUserSession();
  $session->create_session($user);
}

if (!empty($ajax)) {
  $response = new TCJSONResponse();

  $response->success = (empty($error));
  $response->errors = [$error];

  exit($response->get_output());
} else {
  $destination = '/index.php?page='.$settings['page_create_account'];

  if (!empty($error)) {
    $destination .= '&error=' . $error;
  }

  header('Location: '.$destination);
  exit;
}
