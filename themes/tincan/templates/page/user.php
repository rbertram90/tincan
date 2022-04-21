<?php

use TinCan\TCData;
use TinCan\TCTemplate;

  /**
   * User page template.
   *
   * @since 0.01
   *
   * @author Dan Ruscoe danruscoe@protonmail.com
   */
  $page = $data['page'];
  $settings = $data['settings'];
  $user = $data['user'];

  $user_id = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_NUMBER_INT);

  $db = new TCData();

  $profile_user = $db->load_user($user_id);

  $avatar = $profile_user->avatar;

  // TODO: Error handling for missing user (404).

  $avatar_image = (!empty($avatar)) ? '/uploads/avatars/'.$profile_user->avatar : '/assets/images/default-profile.png';

  TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => $profile_user, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $profile_user->username; ?></h1>
  <div class="profile-image">
    <img src="<?php echo $avatar_image; ?>" />
    <?php if ($user->can_edit_user($profile_user)) { ?>
      <div><a href="/?page=<?php echo $settings['page_user_avatar']; ?>">Change avatar</a></div>
    <?php } ?>
  </div>
  <div class="joined">Joined: <?php echo date($settings['date_format'], $profile_user->created_time); ?></div>