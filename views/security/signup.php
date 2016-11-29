<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $signupForm \app\models\security\SignupForm */

$this->title = Yii::t('app', 'Sign Up');

if (strripos(Url::current(), 'chats') === false) {
    $this->params['breadcrumbs'][] = $this->title;
}
$model = (isset($model))? $model : $signupForm;
?>
<div class="homepage container-fluid">
    <div class="row" id="feedback-form">
        <div class="site-signup">
            <h4><?= Html::encode($this->title) ?></h4>
            <?if(isset($close)):?>
                <a href='javascript:void(0)' class="glyphicon glyphicon-remove close-btn"></a>
            <? endif;?>
            <div class="row">
                <?php $form = ActiveForm::begin([
                    'id'     => 'form-signup-client',
                    'action' => Url::toRoute('security/signup')
                ]); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <div class="form-group div-submit">
                    <?= Html::submitButton('Зарегистрироваться', [
                        'class' => 'btn btn-danger',
                        'name'  => 'signup-button'
                    ]) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>