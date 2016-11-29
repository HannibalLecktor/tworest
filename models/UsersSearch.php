<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\security\User;
use yii\db\ActiveQuery;

/**
 * UsersSearch represents the model behind the search form about `app\models\admin\Users`.
 */
class UsersSearch extends User
{
    public $district;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'district_id', 'created_at', 'updated_at'], 'integer'],
            [
                [
                    'username',
                    'first_name',
                    'last_name',
                    'phone',
                    'auth_key',
                    'password_hash',
                    'password_reset_token',
                    'email',
                    'district'
                ],
                'safe'
            ],
            [['is_admin'], 'boolean'],
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
        $query = User::find();

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
            'status' => $this->status,
            'district_id' => $this->district_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_admin' => $this->is_admin,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email]);

        $dataProvider->setSort([
            'attributes' => [
                'district' => [
                    'asc'   => ['districts.name' => SORT_ASC],
                    'desc'  => ['districts.name' => SORT_DESC],
                    'label' => 'District'
                ],
                'username',
                'first_name',
                'last_name',
                'phone',
                'is_admin'
            ]
        ]);

        if(!($this->load($params) && $this->validate())) {
            /**
             * The following line will allow eager loading with city data
             * to enable sorting by city on initial loading of the grid.
             */
            $query->joinWith(['district']);

            return $dataProvider;
        }

        $query->joinWith([
            'district' => function (ActiveQuery $q) {
                $q->where("districts.name ILIKE '%" .
                    $this->district . "%'");
            }
        ]);

        return $dataProvider;
    }
}
