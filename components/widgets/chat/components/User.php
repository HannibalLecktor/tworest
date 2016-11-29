<?php namespace app\components\widgets\chat\components;

use Yii;
use yii\db\ActiveQuery;

/**
 * @property mixed $id
 * @property string $username
 */
class User
{
    public $id;
    public $username;
    public $avatar;
    private $rid;
    private $chat;
    private $privateChats = [];

    /**
     * @return mixed
     */
    public function getPrivateChats() {
        return $this->privateChats;
    }

    /**
     * @param mixed $privateChats
     */
    public function addPrivateChat($privateChat) {
        $this->privateChats[] = $privateChat;
    }

    /** @var string */
    private $modelClassName = null;

    /**
     * @param $id
     * @param string $modelClassName default null
     * @param array $props array of properties for non auth chat users
     */
    public function __construct($id = null, $modelClassName = null, array $props = []) {
        $this->id = $id;
        $this->modelClassName = $modelClassName;
        $this->init($props);
    }

    /**
     * Restore user attributes from cache or load it from
     * repository
     *
     * @access private
     * @param array $props
     * @return void
     */
    private function init(array $props = []) {
        $defaultAttrs = [
            'id'       => 'guest'.mt_rand(),
            'username' => 'guest'
        ];
        if($this->modelClassName) {
            $attrs = call_user_func_array([$this->modelClassName, 'findOne'], ['id' => $this->id]);
            if($attrs) {
                $attrs = $attrs->toArray();
            }
        } else {
            $attrs = $props;
        }

        Yii::configure($this, $attrs ?: $defaultAttrs);
    }

    /**
     * Get user id
     *
     * @param string
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get user resource id
     *
     * @access public
     * @return string
     */
    public function getRid() {
        return $this->rid;
    }

    /**
     * Set user resource id
     *
     * @access public
     * @param $rid
     * @return void
     */
    public function setRid($rid) {
        $this->rid = $rid;
    }

    /**
     * Get user chat room
     *
     * @access public
     */
    public function getChat() {
        return $this->chat;
    }

    /**
     * Set chat room for user
     *
     * @access public
     * @return void
     */
    public function setChat($chat) {
        $this->chat = $chat;
        $this->chat->addUser($this);
    }
}
