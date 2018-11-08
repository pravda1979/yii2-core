<?php

namespace pravda1979\core\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pravda1979\core\models\Backup;
use yii\db\Expression;

/**
 * BackupSearch represents the model behind the search form of `pravda1979\core\models\Backup`.
 */
class BackupSearch extends Backup
{
    public $changes;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'record_id', 'status_id', 'user_id'], 'integer'],
            [['changes', 'action', 'record_short_class', 'record_class', 'record_name', 'note', 'created_at', 'updated_at'], 'safe'],
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
        $query = Backup::find();

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
            'record_id' => $this->record_id,
            'status_id' => $this->status_id,
            'user_id' => $this->user_id,
            'record_short_class' => $this->record_short_class,
        ]);

        $query->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'record_class', $this->record_class])
            ->andFilterWhere(['like', 'record_name', $this->record_name])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', new Expression('DATE_FORMAT(created_at, "%d.%m.%Y %k:%i:%s")'), $this->created_at])
            ->andFilterWhere(['like', new Expression('DATE_FORMAT(updated_at, "%d.%m.%Y %k:%i:%s")'), $this->updated_at]);

        if ($this->changes) {
            $query->joinWith(['backupAttributes backupAttributes']);
            $query->andFilterWhere(['or',
                ['like', 'backupAttributes.old_value', $this->changes],
                ['like', 'backupAttributes.new_value', $this->changes],
                ['like', 'backupAttributes.old_label', $this->changes],
                ['like', 'backupAttributes.new_label', $this->changes],
            ]);
        }

        return $dataProvider;
    }
}
