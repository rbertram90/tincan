<?php

use TinCan\TCTemplate;

/**
 * Log in page template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
  $page = $data['page'];

  $error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<?php
  if (!empty($error)) {
    TCTemplate::render('form-errors', ['errors' => [$error], 'page' => $page]);
  }
?>

<form id="log-in" action="/actions/log-in.php" method="POST">
  <div class="fieldset">
    <label for="username">Username</label>
    <div class="field">
      <input class="text-input" type="text" name="username" />
    </div>
  </div>

  <div class="fieldset">
    <label for="password">Password</label>
    <div class="field">
      <input class="text-input" type="password" name="password" />
    </div>
  </div>

  <input type="hidden" name="ajax" value="" />

  <div class="fieldset button">
    <input type="submit" name="log_in" value="Log in" />
  </div>
</form>
