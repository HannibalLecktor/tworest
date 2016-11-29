<?php
use app\components\widgets\locations\LocationsWidget;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $locations Array
 */
$this->title = 'Главная Страница';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="homepage container-fluid">
    <div>
        <div id="district-count"><?=Yii::t('app', '{dCount} districts in {cCount} countries', ['dCount' => $districtCount, 'cCount' => $countryCount]);?></div>
        <?= LocationsWidget::widget(['locations' => $locations]) ?>
    </div>
</div>
