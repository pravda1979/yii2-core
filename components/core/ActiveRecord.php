<?php

namespace pravda1979\core\components\core;

use Yii;
use yii\db\Expression;
use pravda1979\core\models\Status;
use yii\helpers\ArrayHelper;

/**
 * @property string $fullName
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getFullNameSql()
    {
        return 'name';
    }

    /**
     * @return mixed|string
     */
    public function getFullName()
    {
        if ($this->hasAttribute('name'))
            return $this->name;
        if ($this->hasAttribute('id'))
            return $this->id;
        return 'Не указан аттрибут для fullName!';
    }

    /**
     * Заполняем обязательные поля и присваиваем значения по умолчанию
     *
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->hasAttribute('status_id') && empty($this->status_id)) {
                $this->status_id = Status::getDefaultValue(Status::$FIXED_STATUS_REAL);
            }
            if ($insert) {
                if ($this->hasAttribute('created_at'))
                    $this->created_at = new Expression('Now()');
                if ($this->hasAttribute('updated_at'))
                    $this->updated_at = new Expression('Now()');
                if ($this->hasAttribute('user_id'))
                    $this->user_id = Yii::$app->user->id;
            } else {
                Yii::warning($this->dirtyAttributes,'$this->dirtyAttributes');
                if (count($this->dirtyAttributes) > 0 && $this->hasAttribute('updated_at')) {
                    $this->updated_at = new Expression('Now()');
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array $params
     * @param array $select
     * @param array $order
     * @return array|string
     */
    public static function getList($params = [], $select = [], $order = [])
    {
        $result = static::getListNoMap($params, $select, $order);
        return ArrayHelper::map($result, 'id', 'label');
    }

    /**
     * @param array $params
     * @param array $select
     * @param array $order
     * @return array|Status[]
     */
    public static function getListNoMap($params = [], $select = [], $order = [])
    {
        $sqlQuery = static::getListQuery($params, $select, $order);
        return $sqlQuery->asArray()->all();
    }

    /**
     * @param array $params
     * @param array $select
     * @param array $order
     * @return \yii\db\ActiveQuery
     */
    public static function getListQuery($params = [], $select = [], $order = [])
    {
        if (empty($select))
            $select = ['id', 'label' => static::getFullNameSql()];
        if (empty($order))
            $order = [static::getFullNameSql() => SORT_ASC];

        $sqlQuery = static::find()
            ->orderBy($order)
            ->andFilterWhere($params)
            ->select($select)
            ->real();

        return $sqlQuery;
    }

    /**
     * @inheritdoc
     * @return ActiveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ActiveQuery(get_called_class());
    }
}