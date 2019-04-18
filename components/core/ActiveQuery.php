<?php

namespace pravda1979\core\components\core;

use Yii;
use pravda1979\core\models\Status;
use yii\helpers\ArrayHelper;

class ActiveQuery extends \yii\db\ActiveQuery
{

    /**
     * Фильтр для записей со стасуом Status::$FIXED_STATUS_REAL
     * @param null $alias
     * @return $this
     */
    public function real($alias = null)
    {
        $list = Status::find()->where(['fixed_status_id' => Status::$FIXED_STATUS_REAL])->select(['id']);
        return $this->andWhere([($alias == null ? '' : "$alias.") . 'status_id' => $list]);
    }

    /**
     * Фильтр для записей со стасуом Status::$FIXED_STATUS_DRAFT
     * @param null $alias
     * @return $this
     */
    public function draft($alias = null)
    {
        $list = Status::find()->where(['fixed_status_id' => Status::$FIXED_STATUS_DRAFT])->select(['id']);
        return $this->andWhere([($alias == null ? '' : "$alias.") . 'status_id' => $list]);
    }

    /**
     * Фильтр для записей со стасуом Status::$FIXED_STATUS_DELETED
     * @param null $alias
     * @return $this
     */
    public function deleted($alias = null)
    {
        $list = Status::find()->where(['fixed_status_id' => Status::$FIXED_STATUS_DELETED])->select(['id']);
        return $this->andWhere([($alias == null ? '' : "$alias.") . 'status_id' => $list]);
    }

    /**
     * @inheritdoc
     * @return \pravda1979\core\models\Status[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \pravda1979\core\models\Status|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return string
     */
    public function getFullNameSql()
    {
        $class = $this->modelClass;
        return $class::getFullNameSql();
    }

    /**
     * @param array $attributes
     * @return array
     */
    public function getDroupDownAttributes()
    {
        return ['id' => 'id', 'text' => $this->getFullNameSql()];
    }

    /**
     * @return array|Status[]
     */
    public function asDropDownList()
    {
        return $this
            ->select($this->getDroupDownAttributes())
            ->real()
            ->asArray()
            ->all();
    }

    /**
     * @param array $params
     * @return $this
     */
    public function addWhereParams($params = [])
    {
        if ($oldId = ArrayHelper::getValue($params, '::OLD_ID')) {
            $this->orWhere(['id' => $oldId]);
            unset($params['::OLD_ID']);
        }
        if (!empty($params)) {
            $this->andWhere($params);
        }

        return $this;
    }

    /**
     * @param string $term
     * @return $this
     */
    public function addTerm($term = '')
    {
        $term = trim(preg_replace('/\s+/', ' ', $term));
        $qs = explode(" ", $term);
        $text = $this->getFullNameSql();
        foreach ($qs as $q) {
            $this->andWhere(['like', $text, "$q"]);
        }
        return $this;
    }
}
