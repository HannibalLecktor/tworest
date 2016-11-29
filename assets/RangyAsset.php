<?php namespace app\assets;

use yii\web\AssetBundle;

class RangyAsset extends AssetBundle
{
    public $sourcePath = '@bower/rangy';
    public $js = [
        'rangy-core.min.js',
        'rangy-selectionsaverestore.js'
    ];
}