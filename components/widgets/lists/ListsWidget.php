<?php namespace app\components\widgets\lists;

use yii\base\Widget;

class ListsWidget extends Widget
{
    public $list;
    public $link;
    public $linkName;

    public function init() {
        parent::init();
    }

    public function run() {
        $this->registerAssets();
        return $this->render('lists', [
            'list' => $this->list,
            'link' => $this->link,
            'linkName' => $this->linkName,
        ]);
    }

    public function registerAssets() {
        ListsAsset::register($this->getView());
    }
}