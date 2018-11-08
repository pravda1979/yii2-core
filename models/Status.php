<?php

namespace pravda1979\core\models;

use Yii;
use pravda1979\core\components\validators\StringFilter;
use yii\caching\DbDependency;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%status}}".
 *
 * @property int $id
 * @property string $name
 * @property int $fixed_status_id
 * @property int $is_default
 * @property string $note
 * @property int $status_id
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Status $status
 * @property Status[] $statuses
 * @property User $user
 * @property User[] $users
 *
 * @property string $fixedStatusName
 */
class Status extends \pravda1979\core\components\core\ActiveRecord
{
    public static $FIXED_STATUS_DELETED = 1;
    public static $FIXED_STATUS_DRAFT = 10;
    public static $FIXED_STATUS_REAL = 100;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%status}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'note'], StringFilter::className()],
            [['note', 'updated_at'], 'default', 'value' => null],
            [['name', 'fixed_status_id'], 'required'],
            [['fixed_status_id', 'is_default', 'status_id', 'user_id'], 'integer'],
            [['note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['fixed_status_id', 'is_default', 'status_id', 'user_id'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
            [['name'], 'unique'],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('Status', 'ID'),
            'name' => Yii::t('Status', 'Name'),
            'fixed_status_id' => Yii::t('Status', 'Fixed Status ID'),
            'is_default' => Yii::t('Status', 'Is Default'),
            'note' => Yii::t('Status', 'Note'),
            'status_id' => Yii::t('Status', 'Status ID'),
            'user_id' => Yii::t('Status', 'User ID'),
            'created_at' => Yii::t('Status', 'Created At'),
            'updated_at' => Yii::t('Status', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatuses()
    {
        return $this->hasMany(Status::className(), ['status_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['status_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \pravda1979\core\queries\StatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \pravda1979\core\queries\StatusQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function getBackupLabels()
    {
        return array_merge(parent::getBackupLabels(), [
            'fixed_status_id' => $this->fixedStatusName,
            'is_default' => Yii::$app->formatter->asBoolean($this->is_default),
        ]);
    }

    /**
     * Наименование фиксированного статуса
     * @return mixed
     */
    public function getFixedStatusName()
    {
        $types = $this->getListFixedStatus();
        return $types[$this->fixed_status_id];
    }

    /**
     * Список фиксированных статусов
     * @return array
     */
    public static function getListFixedStatus()
    {
        return [
            static::$FIXED_STATUS_REAL => Yii::t('app', 'Active record'),
            static::$FIXED_STATUS_DRAFT => Yii::t('app', 'Draft record'),
            static::$FIXED_STATUS_DELETED => Yii::t('app', 'Deleted record'),
        ];
    }

    /**
     * Убираем отметку is_default на всех статусах, если у текущей записи is_default=1
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->is_default == true && $this->status->fixed_status_id === static::$FIXED_STATUS_REAL) {
            $this->updateAll(['is_default' => 0], 'fixed_status_id=' . $this->fixed_status_id . ' and id <> ' . $this->id);
        }
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $this->is_default = false;
            return $this->checkIsDefault();
        } else {
            return false;
        }
    }

    /**
     * Проверяет имеется ли статус по дефолту по указанному фиксированному статусу
     * @return bool
     */
    public function checkIsDefault()
    {
        if ($this->isNewRecord) return true;

        if (!$this->is_default) {
            $default = $this->find()->real()->andWhere(['fixed_status_id' => $this->fixed_status_id, 'is_default' => 1]);
            if ($this->status->fixed_status_id === static::$FIXED_STATUS_REAL)
                $default->andWhere(['<>', 'id', $this->id]);

            if (empty($default->one())) {
                $this->addError('is_default', Yii::t('Status', 'The fixed status "{statusName}" does not have a default value', [
                    'statusName' => $this->getFixedStatusName(),
                ]));
                return false;
            }
        }

        return true;
    }

    /**
     * Возвращает значение по умолчанию для указанного фиксированного статуса
     *
     * @param $fixedStatus
     * @return int
     */
    public static function getDefaultValue($fixedStatus)
    {
        $default = static::find()
            ->select('id')
            ->andWhere([
                'fixed_status_id' => $fixedStatus,
                'is_default' => true,
            ])
            ->one();
        return $default ? $default->id : null;
    }

    public static function getListWithGroup($params = [])
    {
        $key = 'Status.getListWithGroup';
        $result = Yii::$app->cache->getOrSet($key, function () use ($params) {
            $select = ['id', 'label' => 'name', 'group' => new Expression('CASE fixed_status_id when ' . static::$FIXED_STATUS_REAL . ' then "Активно" else "Не активно" END')];
            $order = ['fixed_status_id' => SORT_DESC, 'name' => SORT_ASC];

            $sqlQuery = self::find()
                ->orderBy($order)
                ->andFilterWhere($params)
                ->select($select)
                ->real()
                ->asArray();

            return ArrayHelper::map($sqlQuery->all(), 'id', 'label', 'group');
        });
        return $result;
    }
}
