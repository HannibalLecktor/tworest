<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\security\LoginForm */

$this->title = Yii::t('app', 'Login');

if (strripos(Url::current(), 'chats') === false) {
    $this->params['breadcrumbs'][] = $this->title;
}
?>
<div class="homepage container-fluid">
    <div class="row" id="feedback-form">
        <div class="site-login">
            <h4><?= Html::encode($this->title) ?></h4>
            <?if(isset($close)):?>
                <a href='javascript:void(0)' class="glyphicon glyphicon-remove close-btn"></a>
            <? endif;?>
            <?php $form = ActiveForm::begin([
                'id'     => 'login-form',
                'action' => Url::toRoute('security/login')
                //'options'     => ['class' => 'form-horizontal'],
                /*'fieldConfig' => [
                    'template'     => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-3 control-label'],
                ]*/
            ]); ?>

            <?= $form->field($model, 'usernameOrEmail') ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>

            <div class="form-group">
                <div class="">
                    <?= Html::a("Забыл пароль", Url::to("security/reset-password"), ['id' => 'forgot_password_link']) ?>
                </div>
            </div>

            <div class="form-group div-submit">
                <div class="1">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-danger', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
