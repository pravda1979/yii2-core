<?php

namespace pravda1979\core\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pravda1979\core\models\Status;
use yii\db\Expression;

/**
 * StatusSearch represents the model behind the search form of `pravda1979\core\models\Status`.
 */
class StatusSearch extends Status
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fixed_status_id', 'is_default', 'status_id', 'user_id'], 'integer'],
            [['name', 'note', 'created_at', 'updated_at'], 'safe'],
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
        $query = Status::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'fixed_status_id' => $this->fixed_status_id,
            'is_default' => $this->is_default,
            'status_id' => $this->status_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', new Expression('DATE_FORMAT(created_at, "%d.%m.%Y %k:%i:%s")'), $this->created_at])
            ->andFilterWhere(['like', new Expression('DATE_FORMAT(updated_at, "%d.%m.%Y %k:%i:%s")'), $this->updated_at]);

        return $dataProvider;
    }
}
