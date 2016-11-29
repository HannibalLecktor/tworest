<?php namespace app\assets;

use yii\web\AssetBundle;

class WebSocketAsset extends AssetBundle
{
    public $sourcePath = '@bower/web-socket-js';
    public $js = [
        'swfobject.js',
        'web_socket.js'
    ];
}