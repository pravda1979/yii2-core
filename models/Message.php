<?php

namespace pravda1979\core\models;

use Yii;
use pravda1979\core\components\validators\StringFilter;

/**
 * This is the model class for table "{{%message}}".
 *
 * @property int $id
 * @property string $language
 * @property string $translation
 *
 * @property SourceMessage $sourceMessage
 */
class Message extends \pravda1979\core\components\core\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        /** @var \pravda1979\core\Module $module */
        $module = Yii::$app->getModule('core');
        return "{{%" . $module->tableNames["message"] . "}}";
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['language', 'translation'], StringFilter::className()],
            [['translation'], 'default', 'value' => null],
            [['id', 'language'], 'required'],
            [['id'], 'integer'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16],
            [['id'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
            [['id', 'language'], 'unique', 'targetAttribute' => ['id', 'language']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => SourceMessage::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('Message', 'ID'),
            'language' => Yii::t('Message', 'Language'),
            'translation' => Yii::t('Message', 'Translation'),
            'category' => Yii::t('SourceMessage', 'Category'),
            'message' => Yii::t('SourceMessage', 'Message'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessage()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id'])->inverseOf('messages');
    }

    /**
     * {@inheritdoc}
     * @return \pravda1979\core\queries\MessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \pravda1979\core\queries\MessageQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function getFullNameSql()
    {
        return 'translation';
    }

    /**
     * @return mixed|string
     */
    public function getFullName()
    {
        return $this->translation;
    }
}
