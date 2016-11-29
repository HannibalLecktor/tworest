<?php
/**
 * Created by PhpStorm.
 * User: Khadeev Fanis
 * Date: 10/10/15
 * Time: 21:53
 */

namespace app\components;

use yii\web\UrlRuleInterface;
use yii\base\Object;

class StaticPageRule extends Object implements UrlRuleInterface
{
    public $enablePrettyUrl;
    public $showScriptName;
    public $enableStrictParsing;
    public $rules;

    public function createUrl($manager, $route, $params)
    {
        if ($route === 'car/index') {
            if (isset($params['manufacturer'], $params['model'])) {
                return $params['manufacturer'] . '/' . $params['model'];
            } elseif (isset($params['manufacturer'])) {
                return $params['manufacturer'];
            }
        }
        return false;  // this rule does not apply
    }

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        if (preg_match('%^(\w+)(/(\w+))?$%', $pathInfo, $matches)) {
            // check $matches[1] and $matches[3] to see
            // if they match a manufacturer and a model in the database
            // If so, set $params['manufacturer'] and/or $params['model']
            // and return ['car/index', $params]
        }
        return false;  // this rule does not apply
    }
}