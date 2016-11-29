<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $feedbackForm app\models\FeedbackForm */
/* @var $form ActiveForm */
?>
<div class="pages-feedback_form">

    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($feedbackForm, 'text') ?>
        <?= $form->field($feedbackForm, 'email') ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- pages-feedback_form -->
