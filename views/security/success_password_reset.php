<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset-success">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <p>
                Письмо с дальнейшими инструкциями отправлено вам на почту
            </p>
            <?= Html::a('Войти в личный кабинет', Url::toRoute('security/login'), ['id' => 'success_reset_login_link']) ?>
        </div>
    </div>
</div>