<?php

namespace pravda1979\core\models;

use Yii;
use pravda1979\core\components\validators\StringFilter;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property int $id
 * @property string $menu_id
 * @property string $label
 * @property string $icon
 * @property string $url
 * @property string $linkOptions
 * @property int $position
 * @property int $level
 * @property int $parent_id
 * @property string $note
 * @property int $status_id
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Status $status
 * @property User $user
 */
class Menu extends \pravda1979\core\components\core\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_id', 'label', 'icon', 'url', 'linkOptions', 'note'], StringFilter::className()],
            [['menu_id', 'label', 'icon', 'url', 'linkOptions', 'position', 'level', 'parent_id', 'note', 'updated_at'], 'default', 'value' => null],
            [['menu_id', 'label'], 'required'],
            [['linkOptions', 'note'], 'string'],
            [['position', 'level', 'parent_id', 'status_id', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['menu_id', 'label', 'icon', 'url'], 'string', 'max' => 255],
            [['position', 'level', 'parent_id', 'status_id', 'user_id'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
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
            'id' => Yii::t('Menu', 'ID'),
            'menu_id' => Yii::t('Menu', 'Menu ID'),
            'label' => Yii::t('Menu', 'Label'),
            'icon' => Yii::t('Menu', 'Icon'),
            'url' => Yii::t('Menu', 'Url'),
            'linkOptions' => Yii::t('Menu', 'Link Options'),
            'position' => Yii::t('Menu', 'Position'),
            'level' => Yii::t('Menu', 'Level'),
            'parent_id' => Yii::t('Menu', 'Parent ID'),
            'note' => Yii::t('Menu', 'Note'),
            'status_id' => Yii::t('Menu', 'Status ID'),
            'user_id' => Yii::t('Menu', 'User ID'),
            'created_at' => Yii::t('Menu', 'Created At'),
            'updated_at' => Yii::t('Menu', 'Updated At'),
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
     * {@inheritdoc}
     * @return \pravda1979\core\queries\MenuQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \pravda1979\core\queries\MenuQuery(get_called_class());
    }
}
