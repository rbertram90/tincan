<?php
/**
 * Page template for page editing.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

$page = $data['page'];

$object_id = filter_input(INPUT_GET, 'object', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?=$page->page_title?></h1>

<?php

$db = new TCData();

$object = $db->load_object(new TCPage(), $object_id);
?>

<form action="/admin/actions/update-object.php" method="POST">
  <label for="page_title">Page Title</label>
  <input type="text" name="page_title" value="<?=$object->page_title?>" />
  <input type="hidden" name="object_type" value="page" />
  <input type="hidden" name="object_id" value="<?=$object->page_id?>" />
  <input type="submit" value="Update Page" />
</form>
