<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class DistrictsSearch extends District
{
    public $city;
    public $country;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'city_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'city'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = District::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if(!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'         => $this->id,
            'city_id'    => $this->city_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        $dataProvider->setSort([
            'attributes' => [
                'city' => [
                    'asc'   => ['city.name' => SORT_ASC],
                    'desc'  => ['city.name' => SORT_DESC],
                    'label' => 'City'
                ],
                'name',
            ]
        ]);

        if(!($this->load($params) && $this->validate())) {
            /**
             * The following line will allow eager loading with city data
             * to enable sorting by city on initial loading of the grid.
             */
            $query->joinWith(['city']);

            return $dataProvider;
        }

        $query->joinWith([
            'city' => function (ActiveQuery $q) {
                $q->where("cities.name ILIKE '%" . $this->city . "%'");
            }
        ]);

        return $dataProvider;
    }

    public static function getCountByCountry($countryId)
    {
        $locations = Country::find()
            ->select('districts.id')
            ->innerJoinWith([
                'cities' => function (ActiveQuery $q) {
                    $q->innerJoinWith('districts');
                }
            ])->indexBy('id')
            ->where(['countries.id' => $countryId])
            ->asArray()
            ->count();

        return $locations;
    }

    public static function getCountByCity($cityId)
    {
        $locations = District::find()->where(['city_id' => $cityId])->count();

        return $locations;
    }
}
