<?php namespace app\assets;

use yii\web\AssetBundle;

class Select2Asset extends AssetBundle
{
    public $sourcePath = '@bower/select2/dist';
    public $js = [
        'js/select2.min.js',
    ];
    public $css = [
        'css/select2.min.css',
    ];
}