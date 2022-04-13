<?php
/**
 * Header template.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

  $settings = $data['settings'];
  $user = $data['user'];
?>

<html>
  <head>
    <title><?=$settings['forum_name']?></title>
    <?php if (!empty($settings['theme'])) {
      include TC_BASE_PATH . "/themes/{$settings['theme']}/header-include.php";
    } ?>
  </head>
  <body class="tincan <?=$data['page_template']?>">
    <div id="header">
      <h1><?=$settings['forum_name']?></h1>
      <div id="main-navigation">
        <ul class="navigation">
          <li><a href="/">Home</a></li>
          <?php if (empty($user)) { ?>
            <li><a href="/?page=<?=$settings['page_create_account']?>">Create Account</a></li>
            <li><a href="/?page=<?=$settings['page_log_in']?>">Log In</a></li>
          <?php } else { ?>
            <li><a href="/?page=<?=$settings['page_log_out']?>">Log Out</a></li>
          <?php } ?>
          <?php if (!empty($user) && $user->can_perform_action(TCUser::ACT_ACCESS_ADMIN)) { ?>
            <li><a href="/admin">Admin</a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
    <!-- Start content -->
    <div id="content">
