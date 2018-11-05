<?php

namespace pravda1979\core\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pravda1979\core\models\Menu;
use yii\db\Expression;

/**
 * MenuSearch represents the model behind the search form of `pravda1979\core\models\Menu`.
 */
class MenuSearch extends Menu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'use_url_helper', 'visible', 'position', 'level', 'parent_id', 'status_id', 'user_id'], 'integer'],
            [['menu_id', 'label', 'icon', 'url', 'linkOptions', 'note', 'created_at', 'updated_at'], 'safe'],
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
        $query = Menu::find();

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
            'use_url_helper' => $this->use_url_helper,
            'visible' => $this->visible,
            'position' => $this->position,
            'level' => $this->level,
            'parent_id' => $this->parent_id,
            'status_id' => $this->status_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'menu_id', $this->menu_id])
            ->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'linkOptions', $this->linkOptions])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', new Expression('DATE_FORMAT(created_at, "%d.%m.%Y %k:%i:%s")'), $this->created_at])
            ->andFilterWhere(['like', new Expression('DATE_FORMAT(updated_at, "%d.%m.%Y %k:%i:%s")'), $this->updated_at]);

        return $dataProvider;
    }
}
