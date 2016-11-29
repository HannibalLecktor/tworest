<?php namespace app\components\widgets\locations;

use yii\base\Widget;

class LocationsWidget extends Widget
{
    public $locations;

    public function init() {
        parent::init();
    }

    public function run() {
        $this->registerAssets();
        return $this->render('locations', ['locations' => $this->locations]);
    }

    public function registerAssets() {
        LocationsAsset::register($this->getView());
    }
}