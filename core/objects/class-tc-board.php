<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

class TCBoard extends TCObject {

  /**
   * @since 0.01
   */
  public $board_id;

  /**
   * @since 0.01
   */
  public $board_name;

  /**
   * @since 0.01
   */
  public $board_group_id;

  /**
   * @since 0.01
   */
  public $created_time;

  /**
   * @since 0.01
   */
  public $updated_time;

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_primary_key() {
    return 'board_id';
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_table() {
    return 'tc_boards';
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_fields() {
    return array(
      'board_name',
      'board_group_id',
      'created_time',
      'updated_time'
    );
  }

}
