<?php

namespace pravda1979\core\models;

use Yii;
use pravda1979\core\components\validators\StringFilter;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * This is the model class for table "{{%backup}}".
 *
 * @property int $id
 * @property string $action
 * @property string $record_short_class
 * @property string $record_class
 * @property int $record_id
 * @property string $record_name
 * @property string $note
 * @property int $status_id
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Status $status
 * @property User $user
 */
class Backup extends \pravda1979\core\components\core\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%backup}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action', 'record_short_class', 'record_class', 'record_name', 'note'], StringFilter::className()],
            [['action', 'record_short_class', 'record_class', 'record_id', 'record_name', 'note', 'updated_at'], 'default', 'value' => null],
            [['record_id', 'status_id', 'user_id'], 'integer'],
            [['note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['action', 'record_short_class', 'record_class', 'record_name'], 'string', 'max' => 255],
            [['record_id', 'status_id', 'user_id'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
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
            'id' => Yii::t('Backup', 'ID'),
            'action' => Yii::t('Backup', 'Action'),
            'record_short_class' => Yii::t('Backup', 'Record Short Class'),
            'record_class' => Yii::t('Backup', 'Record Class'),
            'record_id' => Yii::t('Backup', 'Record ID'),
            'record_name' => Yii::t('Backup', 'Record Name'),
            'note' => Yii::t('Backup', 'Note'),
            'status_id' => Yii::t('Backup', 'Status ID'),
            'user_id' => Yii::t('Backup', 'User ID'),
            'created_at' => Yii::t('Backup', 'Created At'),
            'updated_at' => Yii::t('Backup', 'Updated At'),
            'changes' => Yii::t('Backup', 'Changes'),
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBackupAttributes()
    {
        return $this->hasMany(BackupAttribute::className(), ['backup_id' => 'id'])->inverseOf('backup');
    }

    /**
     * {@inheritdoc}
     * @return \pravda1979\core\queries\BackupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \pravda1979\core\queries\BackupQuery(get_called_class());
    }

    public function getShortClassT()
    {
        $name = Yii::t($this->record_short_class, Inflector::camel2words($this->record_short_class));
        return $name;
    }

    public function getActionT()
    {
        $name = Yii::t('actions', $this->action);
        return $name;
    }

    /**
     * @return ActiveQuery null
     */
    public function getParent()
    {
        if ($this->record_class) {
            $parentClass = $this->record_class;
            return $this->hasOne($parentClass::className(), ['id' => 'record_id']);
        } else
            return null;
    }

    /**
     * @return ActiveRecord
     */
    public function getParentModel()
    {
        $parent = $this->parent;
        if (empty($parent)) {
            if ($this->record_class) {
                $parentClass = $this->record_class;
                $parent = new $parentClass;
            } else {
                $parent = new ActiveRecord();
            }
        }
        return $parent;
    }

    public function getChanges()
    {
        return Yii::$app->controller->renderPartial('/backup/changes', [
            'changes' => $this->backupAttributes,
        ]);
    }

    public function getOldValues()
    {
        $oldAttributes = BackupAttribute::find()->select(['attribute', 'old_value'])->where(['backup_id' => $this->id])->asArray()->all();
        $result = ArrayHelper::map($oldAttributes, 'attribute', 'old_value');
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getFullName()
    {
        return "$this->shortClassT \"$this->record_name\", $this->actionT";
    }
}
