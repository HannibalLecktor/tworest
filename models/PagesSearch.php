<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * PagesSearch represents the model behind the search form about `app\models\admin\Pages`.
 */
class PagesSearch extends Page
{
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'language_id', 'sort'], 'integer'],
            [['name', 'text', 'language'], 'safe'],
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
        $query = Page::find();

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
            'id'          => $this->id,
            'language_id' => $this->language_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'text', $this->text]);

        $dataProvider->setSort([
            'attributes' => [
                'language' => [
                    'asc'   => ['languages.name' => SORT_ASC],
                    'desc'  => ['languages.name' => SORT_DESC],
                    'label' => 'Language'
                ],
                'name',
            ]
        ]);

        if(!($this->load($params) && $this->validate())) {
            /**
             * The following line will allow eager loading with city data
             * to enable sorting by city on initial loading of the grid.
             */
            $query->joinWith(['language']);

            return $dataProvider;
        }

        $query->joinWith([
            'language' => function (ActiveQuery $q) {
                $q->where("languages.name ILIKE '%" .
                    $this->language . "%'");
            }
        ]);

        return $dataProvider;
    }

    public static function getListByLanguage () {
        $page = Page::find()
            ->select('pages.name, pages.code')
            ->innerJoinWith([
                'language' => function ($query) {
                    $query->onCondition(['languages.code' => \Yii::$app->language]);
                }])
            ->orderBy('sort')
            ->all();
        if (!$page)
            return false;
        else {
            return $page;
        }
    }
}
