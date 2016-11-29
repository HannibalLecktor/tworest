<?php
/* @var $this yii\web\View */
/** @var $user app\models\security\User */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Profile settings');
$this->params['breadcrumbs'] = $breadcrumbs;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
    <div class="col-md-1"></div>
    <div class="col-md-10 static-content">
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?#= $form->errorSummary($user); ?>

<?= $form->field($user, 'username')->input('username', ['maxlength' => true]) ?>
<?= $form->field($user, 'first_name')->input('first_name', []) ?>
<?= $form->field($user, 'last_name')->input('last_name', []) ?>
<?= $form->field($user, 'email')->input('email', []) ?>
<?= $form->field($user, 'district_id')->dropDownList($user->districtList, array('prompt'=>Yii::t('app', 'No selected'))) ?>
<p><label class="control-label"><?=\Yii::t('app', 'Avatar')?></label></p>
<? if($user->avatar): ?>
    <?= Html::img($user->getImageUrl('avatar')); ?>
<? endif; ?>
<p></p>
<? #= $form->field($user, 'avatar')->widget('maxmirazh33\image\Widget');?>
<?= $form->field($user, 'avatar')->label(\Yii::t('app', 'Change the avatar'))->fileInput() ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-danger']) ?>
</div>

<?php ActiveForm::end(); ?>

<div class="site-changepassword">

    <p><?= Yii::t('app', 'Please fill out the following fields to change password :') ?></p>

    <?php $form = ActiveForm::begin([
        'id'          => 'changepassword-form',
        'options'     => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template'     => "{label}\n<div class=\"col-lg-3\">
                        {input}</div>\n<div class=\"col-lg-5\">
                        {error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
        'action'      => Url::toRoute('/personal/change-password')
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'newpass', [
        'inputOptions' => ['class' => 'form-control'],
    ])->passwordInput() ?>

    <?= $form->field($model, 'repeatnewpass', [
        'inputOptions' => ['class' => 'form-control'],
    ])->passwordInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-11">
            <?= Html::submitButton(Yii::t('app', 'Change password'), [
                'class' => 'btn btn-danger'
            ]) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
    </div>
        <div class="col-md-1"></div>
    </div>
</div>
