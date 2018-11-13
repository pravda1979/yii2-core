<?php

namespace pravda1979\core\models;

use Yii;
use pravda1979\core\components\validators\StringFilter;

/**
 * This is the model class for table "{{%backup_attribute}}".
 *
 * @property int $id
 * @property int $backup_id
 * @property string $attribute
 * @property string $old_value
 * @property string $new_value
 * @property string $old_label
 * @property string $new_label
 * @property string $note
 * @property int $status_id
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Backup $backup
 * @property Status $status
 * @property User $user
 */
class BackupAttribute extends \pravda1979\core\components\core\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        /** @var \pravda1979\core\Module $module */
        $module = Yii::$app->getModule('core');
        return  "{{%" . $module->tableNames["backup_attribute"] . "}}";
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute', 'old_value', 'new_value', 'old_label', 'new_label', 'note'], StringFilter::className()],
            [['backup_id', 'attribute', 'old_value', 'new_value', 'old_label', 'new_label', 'note', 'updated_at'], 'default', 'value' => null],
            [['backup_id', 'status_id', 'user_id'], 'integer'],
            [['old_value', 'new_value', 'old_label', 'new_label', 'note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['attribute'], 'string', 'max' => 255],
            [['backup_id', 'status_id', 'user_id'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
            [['backup_id'], 'exist', 'skipOnError' => true, 'targetClass' => Backup::className(), 'targetAttribute' => ['backup_id' => 'id']],
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
            'id' => Yii::t('BackupAttribute', 'ID'),
            'backup_id' => Yii::t('BackupAttribute', 'Backup ID'),
            'attribute' => Yii::t('BackupAttribute', 'Attribute'),
            'old_value' => Yii::t('BackupAttribute', 'Old Value'),
            'new_value' => Yii::t('BackupAttribute', 'New Value'),
            'old_label' => Yii::t('BackupAttribute', 'Old Label'),
            'new_label' => Yii::t('BackupAttribute', 'New Label'),
            'note' => Yii::t('BackupAttribute', 'Note'),
            'status_id' => Yii::t('BackupAttribute', 'Status ID'),
            'user_id' => Yii::t('BackupAttribute', 'User ID'),
            'created_at' => Yii::t('BackupAttribute', 'Created At'),
            'updated_at' => Yii::t('BackupAttribute', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBackup()
    {
        return $this->hasOne(Backup::className(), ['id' => 'backup_id'])->inverseOf('backupAttributes');
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
     * {@inheritdoc}
     * @return \pravda1979\core\queries\BackupAttributeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \pravda1979\core\queries\BackupAttributeQuery(get_called_class());
    }

    public function getAttributeName()
    {
        return $this->backup->getParentModel()->getAttributeLabel($this->attribute);
    }

    public function getChanges()
    {
        require_once(Yii::getAlias('@pravda1979/core/components/core/FineDiff.php'));
        $from_text = $this->getOld();
        $to_text = $this->getNew();
        $opcodes = \FineDiff::getDiffOpcodes($from_text, $to_text, \FineDiff::$wordGranularity);
        $text = \FineDiff::renderDiffToHTMLFromOpcodes($from_text, $opcodes);
        return $text;
    }

    public function getOld()
    {
        $type = null;
        if (!empty($this->backup->getParentModel()->getTableSchema()->getColumn($this->attribute)))
            $type = $this->backup->getParentModel()->getTableSchema()->getColumn($this->attribute)->type;

        //        Yii::trace($type ,'$type ');
        $val = $this->old_label ? $this->old_label : $this->old_value;

        if ($type === 'date')
            $val = Yii::$app->formatter->asDate($val);
        if ($type === 'time')
            $val = Yii::$app->formatter->asTime($val, 'short');
        if ($type === 'datetime' || $type === 'timestamp')
            $val = Yii::$app->formatter->asDatetime($val);
        return $val;
    }

    public function getNew()
    {
        $type = null;
        if (!empty($this->backup->getParentModel()->getTableSchema()->getColumn($this->attribute)))
            $type = $this->backup->getParentModel()->getTableSchema()->getColumn($this->attribute)->type;
//        Yii::trace($type ,'$type ');

        $val = $this->new_label ? $this->new_label : $this->new_value;

        if ($type === 'date')
            $val = Yii::$app->formatter->asDate($val);
        if ($type === 'time')
            $val = Yii::$app->formatter->asTime($val, 'short');
        if ($type === 'datetime' || $type === 'timestamp')
            $val = Yii::$app->formatter->asDatetime($val);

        return $val;
    }
}
