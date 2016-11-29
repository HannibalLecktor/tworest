<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\City */
/* @var $form yii\widgets\ActiveForm */
/* @var $countries array */
?>

<div class="cities-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <p class="bg-danger"><?=\Yii::t('app', 'Images should be jpg, png')?></p>
    <? if($model->image):?>
        <img src="<?=\Yii::getAlias('@upload/') . $model->image;?>" alt="..." class="img-thumbnail">
    <? endif; ?>
    <?= $form->field($model, 'image')->fileInput() ?>
    <?= $form->field($model, 'country_id')->dropDownList($countries) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
