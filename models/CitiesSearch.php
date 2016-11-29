<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * CitiesSearch represents the model behind the search form about `app\models\admin\Cities`.
 */
class CitiesSearch extends City
{
    /**
     * @inheritdoc
     */
    public $country;
    public function rules()
    {
        return [
            [['id', 'country_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'country'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = City::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'country_id' => $this->country_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        $dataProvider->setSort([
           'attributes' => [
               'country' => [
                   'asc' => ['countries.name' => SORT_ASC],
                   'desc' => ['countries.name' => SORT_DESC],
                   'label' => 'Country Name'
               ],
               'name',
           ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            /**
             * The following line will allow eager loading with country data
             * to enable sorting by country on initial loading of the grid.
             */
            $query->joinWith('country');
            return $dataProvider;
        }

        $query->joinWith(['country'=>function (ActiveQuery $q) {
            $q->where("countries.name ILIKE '%" .
                $this->country . "%'");
        }]);

        return $dataProvider;
    }
}
