<?php

namespace pravda1979\core\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pravda1979\core\models\BackupAttribute;
use yii\db\Expression;

/**
 * BackupAttributeSearch represents the model behind the search form of `pravda1979\core\models\BackupAttribute`.
 */
class BackupAttributeSearch extends BackupAttribute
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'backup_id', 'status_id', 'user_id'], 'integer'],
            [['attribute', 'old_value', 'new_value', 'old_label', 'new_label', 'note', 'created_at', 'updated_at'], 'safe'],
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
        $query = BackupAttribute::find();

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
            'backup_id' => $this->backup_id,
            'status_id' => $this->status_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'attribute', $this->attribute])
            ->andFilterWhere(['like', 'old_value', $this->old_value])
            ->andFilterWhere(['like', 'new_value', $this->new_value])
            ->andFilterWhere(['like', 'old_label', $this->old_label])
            ->andFilterWhere(['like', 'new_label', $this->new_label])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', new Expression('DATE_FORMAT(created_at, "%d.%m.%Y %k:%i:%s")'), $this->created_at])
            ->andFilterWhere(['like', new Expression('DATE_FORMAT(updated_at, "%d.%m.%Y %k:%i:%s")'), $this->updated_at]);

        return $dataProvider;
    }
}
