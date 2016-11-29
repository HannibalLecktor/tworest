<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SmilesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Smiles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="smile-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Smile', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'sym',
            [
                'format' => 'image',
                'attribute' => 'image',
                'value' => function ($data) {
                    return $data->getImageUrl('image');
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
