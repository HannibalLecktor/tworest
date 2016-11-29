<?php namespace app\controllers;

use yii\web\Controller;

class ServerStatusController extends Controller
{
    public function actionIndex() {
        if(!$this->isMainServerRunning()) {
            $this->startMainServer();
        }

        if(!$this->isFallbackServerRunning()) {
            $this->startFallbackServer();
        }

        die;

    }

    protected function isMainServerRunning() {
        $output = [];
        exec('ps aux | grep -v grep | grep "yii server/run"', $output);

        return count($output);
    }

    protected function isFallbackServerRunning() {
        $output = [];
        exec('ps aux | grep -v grep | grep "yii server/fallback"', $output);

        return count($output);
    }

    protected function startMainServer() {
        $startCommand = 'php ' . \Yii::getAlias('@app') . '/yii server/run 2>&1 & echo $!';
        exec($startCommand);
    }

    protected function startFallbackServer() {
        $startCommand = 'php ' . \Yii::getAlias('@app') . '/yii server/fallback 2>&1 & echo $!';
        exec($startCommand);
    }
}