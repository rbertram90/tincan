<?php

use TinCan\controllers\TCBoardController;
use TinCan\template\TCURL;

/**
 * Tin Can board update handler.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$board_id = filter_input(INPUT_POST, 'board_id', FILTER_SANITIZE_NUMBER_INT);
$board_group_id = filter_input(INPUT_POST, 'board_group_id', FILTER_SANITIZE_NUMBER_INT);
$board_name = trim(filter_input(INPUT_POST, 'board_name', FILTER_SANITIZE_STRING));

$controller = new TCBoardController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

$controller->edit_board($board_id, $board_group_id, $board_name);

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the board groups page.
    $destination = TCURL::create_admin_url($controller->get_setting('admin_page_boards'));
} else {
    // Send user back to the edit board group page with an error.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_edit_board'),
        [
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
