<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Cities */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cities-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'country',
                'value' => Html::a($model->country->name, Url::toRoute(['admin/countries/view', 'id' => $model->country_id])),
                'format' => 'html'
            ],
            [
                'attribute' => 'image',
                'format' => 'image',
                'value' => \Yii::getAlias('@upload/' . $model->image), ['class' => 'img-thumbnail', 'alt' => $model->name],
                'format' => ['image']
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
