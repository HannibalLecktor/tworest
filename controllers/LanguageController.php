<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Cookie;

class LanguageController extends Controller
{
    public function actionSet() {
        if(array_key_exists('lang', $_GET) && in_array($_GET['lang'], Yii::$app->cache->get('supportedLanguages'))) {
            Yii::$app->language = $_GET['lang'];
            $langCookie = new Cookie([
                'name'   => 'lang',
                'value'  => Yii::$app->language,
                'expire' => time() + 86400 * 365,
            ]);
            Yii::$app->response->cookies->add($langCookie);
        }

        $this->goBack();
    }
}
