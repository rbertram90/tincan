<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

class TCUser extends TCObject {

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_table() {
    return 'tc_users';
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_fields() {
    return array(
      'user_id'
    );
  }

}
