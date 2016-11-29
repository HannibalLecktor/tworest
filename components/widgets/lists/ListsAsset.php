<?php namespace app\components\widgets\lists;

use yii\web\AssetBundle;
use yii\web\View;

class ListsAsset extends AssetBundle
{
    public $css = [
        'css/lists.css'
    ];

    public $js = [
        'js/lists.js'
    ];
    public $depends = [
        'app\assets\VueAsset',
    ];
    public function init() {
        $this->sourcePath = __DIR__.'/assets/';
        parent::init();
    }
}