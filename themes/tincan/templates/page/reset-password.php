<?php

use TinCan\TCTemplate;

  /**
   * Reset password page template.
   *
   * @since 0.07
   *
   * @author Dan Ruscoe danruscoe@protonmail.com
   */
  $page = $data['page'];
  $settings = $data['settings'];

  $error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

  TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => null, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<?php
  if (!empty($error)) {
    TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error], 'page' => $page]);
  }
?>

<form id="log-in" action="/actions/reset-password.php" method="POST">
  <div class="fieldset">
    <label for="email">Email address</label>
    <div class="field">
      <input class="text-input" type="text" name="email" />
    </div>
  </div>

  <input type="hidden" name="ajax" value="" />

  <div class="fieldset button">
    <input type="submit" name="reset_password" value="Reset password" />
  </div>
</form>