<?php

namespace pravda1979\core\components\core;

use Yii;
use pravda1979\core\models\Status;

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
}
