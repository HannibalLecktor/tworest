<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $passwordResetForm \app\models\security\PasswordResetRequestForm */
$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="homepage container-fluid">
    <div class="row" id="feedback-form">
<div class="site-request-password-reset">
    <div class="row">
        <div class="col-lg-12">
            <h4>Восстановление пароля</h4>
            <? if (isset($successMessage)):?>
                <div class="bg-success well-sm"><?=$successMessage?></div>
                <p></p>
            <? endif;?>
            <?php $form = ActiveForm::begin([
                'id'          => 'login-form',
                'options'     => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-3 control-label'],
                ]
            ]); ?>
            <?= $form->field($passwordResetForm, 'email') ?>
            <div class="form-group div-submit">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-danger']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
        </div>
    </div>