<?php namespace app\components\widgets\locations;

use yii\web\AssetBundle;
use yii\web\View;

class LocationsAsset extends AssetBundle
{
    public $css = [
        'css/locations.css',
    ];

    public $js = [
        'js/locations.js'
    ];
    public $depends = [
        'app\assets\VueAsset',
        'app\assets\Select2Asset'
    ];
    public function init() {
        $this->sourcePath = __DIR__.'/assets/';
        parent::init();
    }
}