<?php namespace app\assets;

use yii\web\AssetBundle;

class UndoAsset extends AssetBundle
{
    public $sourcePath = '@npm/undo.js';
    public $js = [
        'undo.js',
    ];
}