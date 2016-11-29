<?php
/* @var $this yii\web\View */
/* @var $chat Array */
use app\components\widgets\chat\ChatWidget;
use yii\web\View;
$this->registerJs('$(document).ready(function () {
        $("#footer-line").css("background-color", "inherit");
    })', View::POS_END, 'my-options');
?>
<div class="row" id="chat-row">
    <div class="col-md-12" id="col-md-12-main">
        <div class="col-md-1"></div>
        <div class="col-md-10 country-row">
            <div class="row static-content">
                <?= ChatWidget::widget([
                    'chat'    => $chat,
                    'smiles'  => $smiles,
                    'users'   => $users,
                    'private' => true
                ]); ?>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
    <div class="col-md-1 col-xs-1"></div>
</div>