<?php
use app\assets\AppAsset;
use app\components\widgets\languages\LanguagesWidget;
use app\models\PagesSearch;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

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
    <!--[if lt IE 9]>
    <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
    <![endif]-->
    <?php $this->head() ?>
</head>
<body id="main">
<?php $this->beginBody() ?>
<div class="wrap container">
    <div class="row">
        <div class="container">
            <div class="row">
                <nav class="navbar navbar-default" id="navbar-container">
                    <div class="container-fluid">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <a class="navbar-brand" href="/">
                                <div id="img-div">
                                    <img src="/logo.png">
                                </div>
                            </a>
                            <ul class="nav navbar-nav" id="color-blue-header">
                                <? if(Url::to() != Yii::$app->homeUrl):?>
                                    <li id="navbar-breadcrumbs">
                                        <?=Breadcrumbs::widget([
                                            'homeLink' => [
                                                'label' => Yii::t('app', 'Home'),
                                                'url' => Yii::$app->homeUrl,
                                            ],
                                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                        ])
                                        ?>
                                    <li>
                                    <li>
                                        <?=Html::a(
                                            '<span class="glyphicon glyphicon-arrow-left"></span>
                                                <span>' . Yii::t('app', 'Back') . '</span>', Url::previous() == Url::current() ? Url::previous('backurl') : Url::previous()) ?>
                                    </li>
                                <? endif; ?>
                            </ul>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav navbar-right" id="sign-buttons">
                                <? if(\Yii::$app->user->isGuest): ?>
                                    <? if(strripos(Url::to(), 'chats') === false):?>
                                        <li>
                                            <a class="color-blue sign-buttons" id="login_button" href="<?= Url::toRoute('security/login') ?>">
                                                <?=Yii::t('app', 'Sign in')?>
                                            </a>
                                        </li>
                                        <li><a href="javascript:void(0)"> / </a></li>
                                        <li>
                                            <a id='reg-button' class="sign-buttons" href="<?= Url::toRoute('security/signup') ?>">
                                                <?=Yii::t('app', 'Sign up')?>
                                            </a>
                                        </li>
                                    <? endif;?>
                                <? else: ?>
                                    <li>
                                        <a id="button-hi" href="<?= Url::toRoute('personal/update') ?>"><?=Yii::t('app', 'Hello')?>, <?=(is_object(\Yii::$app->user->identity))? \Yii::$app->user->identity->username : '';?></a>
                                    </li>
                                    <li>
                                        <a id="button-logout" href="<?= Url::toRoute('security/logout') ?>">
                                            <?=Yii::t('app', 'Log out')?>
                                        </a>
                                    </li>
                                <? endif ?>
                                <li class="dropdown">
                                    <?= LanguagesWidget::widget() ?>
                                </li>
                            </ul>
                        </div><!-- /.navbar-collapse -->
                    </div><!-- /.container-fluid -->
                </nav>
            </div>
        </div>
    </div>
    <div class="container" id="conteiner-content">
        <?= $content ?>
    </div>
</div>
<div class="container" id="footer-conteainer">
    <? //if(strripos(Url::current(), 'chat') === false):?>
    <div class="container" id="footer-line">
        <? if(\Yii::$app->user->isGuest && strripos(Url::to(), 'chats') !== false): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6" id="col-signin">
                        <a class="color-blue sign-buttons" id="login_button" href="<?= Url::toRoute('security/login') ?>">
                            <?=Yii::t('app', 'Sign in')?>
                        </a>
                    </div>
                    <div class="col-md-6" id="col-signup">
                        <a id='reg-button' class="sign-buttons" href="<?= Url::toRoute('security/signup') ?>">
                            <?=Yii::t('app', 'Sign up')?>
                        </a>
                    </div>
                </div>
            </div>
        <? endif;?>
    </div>
    <? //endif;?>
    <nav class="navbar navbar-default" id="navbar-container-footer">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <ul class="nav navbar-nav navbar-left">
                    <? if ($staticPages = PagesSearch::getListByLanguage()):?>
                        <? foreach ($staticPages as $page):?>
                            <li>
                                <?=Html::a($page->name, Url::toRoute('/' . $page->code));?>
                            </li>
                        <? endforeach;?>
                    <? endif;?>
                    <li>
                        <?=Html::a(Yii::t('app', 'Feedback'), Url::toRoute('/feedback/'));?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
<?# Modal::begin(['id' => 'login_popup']); ?>
<!--<div id='login_popup_content'></div>-->
<?# Modal::end(); ?>
<?Url::remember();?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
