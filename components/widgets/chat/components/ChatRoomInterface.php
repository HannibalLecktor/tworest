<?php namespace app\components\widgets\chat\components;

interface ChatRoomInterface
{
    /**
     * Set chat room unique id
     *
     * @access public
     * @param $uid
     * @return void
     */
    public function setUid($uid);

    /**
     * Get chat room unique id
     *
     * @access public
     * @return string
     */
    public function getUid();
}