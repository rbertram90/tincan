<?php
/**
 * Interface for Tin Can database access objects (DAOs).
 *
 * @package Tin Can
 * @since 0.01
 */

 /**
  * @since 0.01
  */
interface TCDB {

  /**
   * @since 0.01
   */
  protected function open_connection();

  /**
   * @since 0.01
   */
  protected function close_connection();

}
