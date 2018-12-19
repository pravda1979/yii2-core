<?php

namespace pravda1979\core\components\core;

use pravda1979\core\components\behaviors\BackupBehavior;
use Yii;
use yii\caching\DbDependency;
use yii\db\Expression;
use pravda1979\core\models\Status;
use yii\helpers\ArrayHelper;

/**
 * @property string $fullName
 * @property array $backupLabels
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        if (Yii::$app->params['app_UseBackups']) {
            $rules = [
                'backup' => ['class' => BackupBehavior::className()],
            ];
        } else {
            $rules = [];
        }

        return array_merge(parent::behaviors(), $rules);
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
        if ($this->hasAttribute(static::primaryKey()))
            return $this->{static::primaryKey()};
        return 'Attribute name for "fullName" property not set!';
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
        if (empty($params) && empty($select) && empty($order)) {
            $key = static::className() . ".getList";
            $dependency = new DbDependency(['sql' => 'select max(updated_at) from ' . static::tableName()]);
            $result = Yii::$app->cache->getOrSet($key, function () use ($params, $select, $order) {
                $result = static::getListNoMap($params, $select, $order);
                return ArrayHelper::map($result, 'id', 'label');
            }, null, $dependency);
            return $result;
        } else {
            $result = static::getListNoMap($params, $select, $order);
            return ArrayHelper::map($result, 'id', 'label');
        }
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

    /**
     * Возвращает значения для связанных полей вместо их id
     * @return array
     */
    public function getBackupLabels()
    {
        $result = [];
        if ($this->hasAttribute('user_id'))
            $result['user_id'] = ArrayHelper::getValue($this, 'user.fullName');
        if ($this->hasAttribute('user_id'))
            $result['status_id'] = ArrayHelper::getValue($this, 'status.fullName');

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['undo'] = $scenarios['backup'] = $scenarios['create'] = $scenarios['update'] = $scenarios['delete'] = $scenarios['view'] = $scenarios['default'];
        return $scenarios;
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteCachedList();
    }

    /**
     * Delete all cached lists by key names
     * @param array $keys
     */
    public function deleteCachedList($keys = ['getList'])
    {
        if (!is_array($keys))
            $keys = [$keys];

        foreach ($keys as $key) {
            $key = static::className() . "." . $key;
            if (Yii::$app->cache->exists($key))
                Yii::$app->cache->delete($key);
        }
    }
}
