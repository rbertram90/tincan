<?php
/**
 * Represents a forum page.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

class TCPage extends TCObject
{
    /**
     * @since 0.01
     */
    public $page_id;

    /**
     * @since 0.01
     */
    protected $page_title;

    /**
     * @since 0.01
     */
    protected $template;

    /**
     * @since 0.01
     */
    protected $created_time;

    /**
     * @since 0.01
     */
    protected $updated_time;

    /**
     * @see TCObject::get_primary_key()
     *
     * @since 0.01
     */
    public function get_primary_key()
    {
        return 'page_id';
    }

    /**
     * @see TCObject::get_db_table()
     *
     * @since 0.01
     */
    public function get_db_table()
    {
        return 'tc_pages';
    }

    /**
     * @see TCObject::get_db_fields()
     *
     * @since 0.01
     */
    public function get_db_fields()
    {
        return array(
          'page_title',
          'template',
          'created_time',
          'updated_time'
        );
    }
}
