<?php namespace app\assets;

use yii\web\AssetBundle;

class FancyboxAsset extends AssetBundle
{
    public $sourcePath = '@bower/fancybox/source';
    public $js = [
        'jquery.fancybox.pack.js',
    ];
    public $css = [
        'jquery.fancybox.css'
    ];
}