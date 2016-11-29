<?php namespace app\components\widgets\chat;

use app\models\MessageImage;
use app\models\security\LoginForm;
use yii\base\Widget;

class ChatWidget extends Widget
{
    public $chat;
    public $smiles;
    public $users;
    public $private = false;

    public function init() {
        parent::init();
    }

    public function run() {
        $this->registerAssets();

        $data = [
            'chat'   => $this->chat,
            'smiles' => $this->smiles,
            'users'  => $this->users,
            'model'  => new MessageImage
        ];

        return $this->render($this->private ? 'private' : 'chat', $data);
    }

    public function registerAssets() {
        ChatAsset::register($this->getView());
    }
}