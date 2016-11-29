<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Smile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="smile-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'sym')->textInput(['maxlength' => true]) ?>
    <? if($model->image): ?>
        <?= $model->image ?>
        <?= Html::img($model->getImageUrl('image')) ?>
    <? endif; ?>
    <?= $form->field($model, 'image')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
