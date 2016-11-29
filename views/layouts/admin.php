<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Modal;
use app\assets\AppAsset;
use yii\helpers\BaseUrl;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <div class="row">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-5">
                        <a href="<?=Url::to('/')?>">
                            <img src="/logo.png">
                        </a>
                    </div>
                    <div class="col-md-5">
                    </div>
                    <div class="col-md-2">
                        <div class="security_links">
                            <? if(\Yii::$app->user->isGuest): ?>
                                <a id="login_button" href="<?= Url::toRoute('security/login') ?>">Вход</a> /
                                <a href="<?= Url::toRoute('security/signup') ?>">Регистрация</a>
                            <? else: ?>
                                <a href="<?= Url::toRoute('personal/index') ?>">Привет, <?=(is_object(\Yii::$app->user->identity))? \Yii::$app->user->identity->username : '';?></a>
                                <a href="<?= Url::toRoute('security/logout') ?>">Выйти</a>
                            <? endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-3">
                    <div class="list-group">
                            <a class="list-group-item<?=(strpos(BaseUrl::to(), "admin/countries"))? ' active' : '';?>" href="<?=Url::toRoute('admin/countries')?>">
                                <?=Yii::t('app', 'Countries');?>
                            </a>
                            <a class="list-group-item<?=(strpos(BaseUrl::to(), "admin/cities"))? ' active' : '';?>" href="<?=Url::toRoute('admin/cities')?>">
                                <?=Yii::t('app', 'Cities');?>
                            </a>
                            <a class="list-group-item<?=(strpos(BaseUrl::to(), "admin/districts"))? ' active' : '';?>" href="<?=Url::toRoute('admin/districts')?>">
                                <?=Yii::t('app', 'Districts');?>
                            </a>
                            <a class="list-group-item<?=(strpos(BaseUrl::to(), "admin/pages"))? ' active' : '';?>" href="<?=Url::toRoute('admin/pages')?>">
                                <?=Yii::t('app', 'Pages');?>
                            </a>
                            <a class="list-group-item<?=(strpos(BaseUrl::to(), "admin/users"))? ' active' : '';?>" href="<?=Url::toRoute('admin/users')?>">
                                <?=Yii::t('app', 'Users');?>
                            </a>
                            <a class="list-group-item<?=(strpos(BaseUrl::to(), "admin/languages"))? ' active' : '';?>" href="<?=Url::toRoute('admin/languages')?>">
                                <?=Yii::t('app', 'Languages');?>
                            </a>
                            <a class="list-group-item<?=(strpos(BaseUrl::to(), "admin/smiles"))? ' active' : '';?>" href="<?=Url::toRoute('admin/smiles')?>">
                                <?=Yii::t('app', 'Smiles');?>
                            </a>
                    </div>
                </div>
                <div class="col-md-9">
                    <?=Breadcrumbs::widget([
                        'homeLink' => [
                            'label' => Yii::t('yii', 'Admin Page'),
                            'url' => Url::toRoute('admin/countries'),
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ])
                    ?>
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
<?# Modal::begin(['id' => 'login_popup']); ?>
<!--<div id='login_popup_content'></div>-->
<?# Modal::end(); ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
