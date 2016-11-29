<?php
use yii\helpers\Html;
/* @var $this yii\web\View */

//$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Hello <?//= Html::encode($user->getHumanName) ?>,</p>

    <p>Ваш новый пароль: <?=$password?></p>

</div>