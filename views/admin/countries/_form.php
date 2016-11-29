<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Country */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="country-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <p class="bg-danger"><?=\Yii::t('app', 'Images should be jpg, png')?></p>
    <? if($model->flag):?>
        <img src="<?=\Yii::getAlias('@upload/') . $model->flag;?>" alt="..." class="img-thumbnail">
    <? endif; ?>
    <?= $form->field($model, 'flag')->fileInput() ?>
    <? if($model->capital_image):?>
        <img src="<?=\Yii::getAlias('@upload/') . $model->capital_image;?>" alt="..." class="img-thumbnail">
    <? endif;?>
    <?= $form->field($model, 'capital_image')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
