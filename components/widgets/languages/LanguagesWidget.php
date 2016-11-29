<?php namespace app\components\widgets\languages;

use yii\base\Widget;
use app\models\Language;

class LanguagesWidget extends Widget
{
    public $languages;

    public function init() {
        parent::init();
    }

    public function run() {
        $this->registerAssets();
        return $this->render('languages', ['languages' => Language::getList()]);
    }

    public function registerAssets() {
        LanguagesAsset::register($this->getView());
    }
}