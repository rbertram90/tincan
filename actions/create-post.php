<?php

require '../tc-config.php';

require TC_BASE_PATH . '/includes/include-db.php';
require TC_BASE_PATH . '/includes/include-objects.php';
require TC_BASE_PATH . '/includes/include-template.php';
require TC_BASE_PATH . '/includes/include-user.php';

require 'class-tc-json-response.php';

$thread_id = filter_input(INPUT_POST, 'thread_id', FILTER_SANITIZE_NUMBER_INT);
$post_content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_STRING);
$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$db = new TCData();

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Check user has permission to create a new post.
if (empty($user) || !$user->can_perform_action(TCUser::ACT_CREATE_POST)) {
    $errors['user'] = TCUser::ERR_NOT_AUTHORIZED;
}

// Check this post can be created in the given thread.
if (empty($errors)) {
    $thread = $db->load_object(new TCThread(), $thread_id);

    // TODO: Thread validation.
    if (empty($thread)) {
        $errors['thread'] = TCObject::ERR_NOT_SAVED;
    }
}

// TODO: Validate post content.
$new_post = null;

if (empty($errors)) {
    $post = new TCPost();
    $post->user_id = $user->user_id;
    $post->thread_id = $thread->thread_id;
    $post->content = $post_content;
    $post->created_time = time();
    $post->updated_time = time();

    $new_post = $db->save_object($post);

    if (empty($new_post)) {
        $errors['post'] = TCObject::ERR_NOT_SAVED;
    }
}

if (!empty($ajax)) {
    $response = new TCJSONResponse();

    $response->success = (empty($errors));
    $response->errors = $errors;

    exit($response->get_output());
} else {
    $settings = $db->load_settings();

    // Calculate the total pages in this thread so the user can be sent
    // directly to their new post.
    $conditions = array(
      array('field' => 'thread_id', 'value' => $thread_id)
    );

    $total_posts = $db->count_objects(new TCPost(), $conditions);
    $total_pages = TCPagination::calculate_total_pages($total_posts, $settings['posts_per_page']);

    $destination = '/index.php?page=' . $settings['page_thread']
    . '&thread=' . $thread_id
    . '&start_at=' . $total_pages
    . '#post-' . $new_post->post_id;

    if (!empty($errors)) {
        // TODO: Create a utility class for this.
        foreach ($errors as $name => $value) {
            $destination .= "&{$name}={$value}";
        }
    }

    header('Location: ' . $destination);
    exit;
}