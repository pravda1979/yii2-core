<?php

namespace pravda1979\core\searches;

use pravda1979\core\models\Message;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pravda1979\core\models\AuthItem;
use yii\db\Expression;

/**
 * AuthItemSearch represents the model behind the search form of `pravda1979\core\models\AuthItem`.
 */
class AuthItemSearch extends AuthItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'rule_name', 'data'], 'safe'],
            [['type', 'created_at', 'updated_at'], 'integer'],
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
        $query = AuthItem::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['type' => SORT_ASC, 'name' => SORT_ASC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        // Get translations for roles, which like $this->name
        if (!empty($this->name)) {
            $nameT = Message::find()
                ->alias('t')
                ->joinWith('sourceMessage sourceMessage', false)
                ->andFilterWhere(['sourceMessage.category' => 'role'])
                ->andFilterWhere(['like', 't.translation', $this->name])
                ->select('sourceMessage.message')
                ->column();

            $query->andFilterWhere([
                'or',
                ['name' => $nameT],
                ['like', 'name', $this->name],
            ]);
        }

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'rule_name', $this->rule_name])
            ->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }
}
