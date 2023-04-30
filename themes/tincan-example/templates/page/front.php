<?php

use TinCan\TCBoard;
use TinCan\TCBoardGroup;
use TinCan\TCData;
use TinCan\TCTemplate;
use TinCan\TCURL;
use TinCan\TCUser;

/**
 * Front page template.
 *
 * @since 0.11
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$user = $data['user'];

if (!empty($user)) {
    ?>
  <div>Thanks for using the forum, <?php echo $user->username; ?>!</div>
<?php
}

$db = new TCData();

$settings = $db->load_settings();

$board_groups = $db->load_objects(new TCBoardGroup());

TCTemplate::render('header', $settings['theme'], ['page_title' => null, 'page_template' => 'front', 'settings' => $settings, 'user' => $user]);

if (!empty($board_groups)) {
    foreach ($board_groups as $group) {
        $board_conditions = [
          [
            'field' => 'board_group_id',
            'value' => $group->board_group_id,
          ],
        ];

        $boards = $db->load_objects(new TCBoard(), [], $board_conditions);

        $data = [
          'settings' => $settings,
          'board_group' => $group,
          'boards' => $boards,
        ];

        TCTemplate::render('board-group', $settings['theme'], $data);
    }
} else {
    $log_in_url = TCURL::create_url($settings['page_log_in']); ?>
  <div class="message-box">
    <p>Nothing here yet!</p>
    <?php if (empty($user)) { ?>
    <p>If you are the administrator, you can <a href="<?php echo $log_in_url; ?>">log in and create some boards!</p>
    <?php } elseif ($user->can_perform_action(TCUser::ACT_ACCESS_ADMIN)) { ?>
      <p>You are the administrator! You can <a href="/admin">create some boards!</p>
    <?php } ?>
  </div>
<?php
}
