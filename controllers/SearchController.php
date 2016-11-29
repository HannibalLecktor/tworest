<?php

namespace app\controllers;

use app\models\Country;
use app\models\District;
use Yii;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SearchController extends Controller
{
    public $layout = 'main';

    public function actionIndex() {
        $countryId = Yii::$app->request->get('country', 0);
        $cityId = Yii::$app->request->get('city', 0) ?: 0;
        $districtId = Yii::$app->request->get('district', 0) ?: 0;

        if(!$countryId) throw new NotFoundHttpException;

        $countriesQuery = Country::find();
        $countriesQuery->joinWith([
            'cities' => function (ActiveQuery $citiesQuery) use ($cityId, $districtId) {
                $citiesQuery->onCondition(['cities.id' => $cityId]);
                $citiesQuery->joinWith([
                    'districts' => function (ActiveQuery $districtsQuery) use ($districtId) {
                        $districtsQuery->onCondition(['districts.id' => $districtId]);
                    }
                ]);
            }
        ]);

        if($countryId) $countriesQuery->where(['countries.id' => $countryId]);

        $country = $countriesQuery->one()->toArray();
        if(!$country) throw new NotFoundHttpException;

        $districtsQuery = District::find()->innerJoinWith([
            'city' => function (ActiveQuery $citiesQuery) use ($cityId, $countryId) {
                if($cityId) $citiesQuery->onCondition(['cities.id' => $cityId]);
                $citiesQuery->innerJoinWith([
                    'country' => function (ActiveQuery $countriesQuery) use ($countryId) {
                        $countriesQuery->onCondition(['countries.id' => $countryId]);
                    }
                ]);
            }
        ])->filterWhere(['districts.id' => $districtId === 0 ? "" : $districtId]);

        $districtCount = $districtsQuery->count();
        $pages = new Pagination(['totalCount' => $districtCount, 'pageSize' => Yii::$app->params['pageSize']]);
        $districtPaginator = $districtsQuery
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $districtPaginator = ArrayHelper::toArray($districtPaginator);

        return $this->render('index', compact('country', 'districtPaginator', 'pages', 'districtCount'));
    }
}