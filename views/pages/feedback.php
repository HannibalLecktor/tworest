<?php
use app\components\widgets\locations\LocationsWidget;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var $this yii\web\View
 * @var $locations Array
 */
$this->title = Yii::t('app', 'Feedback');
$this->params['breadcrumbs'][] = $this->title;

$js =<<<JS
    $(function(){
        var mess = $('.bg-success');
        if (mess.length != 0 && $(mess[0]).html() != '')
            window.setTimeout(function(){
                window.location.replace('/')
            }, 2000);
    });
JS;

$this->registerJs($js, View::POS_END);
?>
<div class="homepage container-fluid">
    <div class="row" id="feedback-form">
        <h4><?=$this->title?></h4>
        <? if (isset($successMessage)):?>
            <div class="bg-success well-sm"><?=$successMessage?></div>
            <p></p>
        <? endif;?>
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'text')->textarea(); ?>
        <?= $form->field($model, 'email') ?>
        <div class="form-group div-submit">
            <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-danger']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>