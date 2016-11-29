<?php namespace app\components\widgets\languages;

use yii\web\AssetBundle;
use yii\web\View;

class LanguagesAsset extends AssetBundle
{
    public $css = [
        'css/languages.css'
    ];

    public $js = [
        'js/languages.js'
    ];
    public $depends = [
        'app\assets\VueAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
    public function init() {
        $this->sourcePath = __DIR__.'/assets/';
        parent::init();
    }
}