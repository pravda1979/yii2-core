<?php

namespace pravda1979\core\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pravda1979\core\models\Message;
use yii\db\Expression;

/**
 * MessageSearch represents the model behind the search form of `pravda1979\core\models\Message`.
 */
class MessageSearch extends Message
{
    public $category;
    public $message;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['language', 'translation', 'category', 'message'], 'safe'],
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
        $query = Message::find();

        // add conditions that should always apply here

        $query->alias('t');
        $query->joinWith('sourceMessage source', false);

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

        // sorting data
        $dataProvider->sort->attributes['category'] = [
            'asc' => ['source.category' => SORT_ASC],
            'desc' => ['source.category' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['message'] = [
            'asc' => ['source.message' => SORT_ASC],
            'desc' => ['source.message' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            't.id' => $this->id,
            'source.category' => $this->category,
        ]);

        $query->andFilterWhere(['like', 't.language', $this->language])
            ->andFilterWhere(['like', 't.translation', $this->translation])
            ->andFilterWhere(['like', 'source.message', $this->message]);

        return $dataProvider;
    }
}
