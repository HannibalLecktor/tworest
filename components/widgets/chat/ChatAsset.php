<?php namespace app\components\widgets\chat;

use yii\web\AssetBundle;

class ChatAsset extends AssetBundle
{
    public $publishOptions = [
        'forceCopy' => true,
    ];

    public $css = [
        'css/chat.css',
        'css/jquery.emojiarea.css'
    ];

    public $js = [
        'js/chat.js',
        'js/moment-with-locales.js',
        'js/vue-moment.min.js',
        'js/jquery.emojiarea.js'
    ];
    public $depends = [
        'app\assets\VueAsset',
        'app\assets\FancyboxAsset',
        'app\assets\WebSocketAsset',
        'app\assets\JsCookieAsset'
    ];
    public function init() {
        $this->sourcePath = __DIR__.'/assets/';
        parent::init();
    }
}