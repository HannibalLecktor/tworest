<?php
use app\components\widgets\locations\LocationsWidget;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $locations Array
 */
$this->title = $content->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-1"></div>
        <div class="col-md-10 static-content"><?=$content->text;?></div>
        <div class="col-md-1"></div>
    </div>
</div>