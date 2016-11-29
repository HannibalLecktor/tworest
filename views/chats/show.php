<?php
/* @var $this yii\web\View */
/* @var $chat Array */
use app\components\widgets\chat\ChatWidget;
use yii\helpers\Url;
use yii\web\View;

$this->title = $chat->name;

$this->params['breadcrumbs'] = $breadcrumb;

//$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('$(document).ready(function () {
        $("#footer-line").css("background-color", "inherit");
    })', View::POS_END, 'my-options');
?>
<? if($background):?>
    <div class="parent-city-img" id="chat-city-img">
        <div class="inner">
            <div class="block" style="background: url('<?=Url::to('@upload/' . $background)?>') no-repeat center;">
                <!--<img src="<?/*=Url::to('@upload/' . $background)*/?>" alt=""/>-->
            </div>
        </div>
    </div>
<?endif;?>
<div class="row" id="chat-row">
    <div class="col-md-12" id="col-md-12-main">
        <div class="col-md-1"></div>
        <div class="col-md-10 country-row">
            <div class="row static-content">
                <?= ChatWidget::widget([
                    'chat'   => $chat,
                    'smiles' => $smiles,
                    'users'  => $users,
                ]); ?>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
    <div class="col-md-1 col-xs-1"></div>
</div>
<?if(isset($loginForm)):?>
<div class="login_wrap" id="signin-wrap">
    <?= $this->render('/security/login', ['model' => $loginForm, 'close' => 'close']) ?>
</div>
<?endif;?>
<?if(isset($regForm)):?>
    <div class="login_wrap" id="signup-wrap">
        <?= $this->render('/security/signup', ['model' => $regForm, 'close' => 'close']) ?>
    </div>
<?endif;?>
