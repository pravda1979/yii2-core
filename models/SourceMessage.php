<?php

namespace pravda1979\core\models;

use Yii;
use pravda1979\core\components\validators\StringFilter;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%source_message}}".
 *
 * @property int $id
 * @property string $category
 * @property string $message
 *
 * @property Message[] $messages
 */
class SourceMessage extends \pravda1979\core\components\core\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%source_message}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category', 'message'], StringFilter::className()],
            [['category', 'message'], 'default', 'value' => null],
            [['category', 'message'], 'required'],
            [['message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('SourceMessage', 'ID'),
            'category' => Yii::t('SourceMessage', 'Category'),
            'message' => Yii::t('SourceMessage', 'Message'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \pravda1979\core\queries\SourceMessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \pravda1979\core\queries\SourceMessageQuery(get_called_class());
    }

    /**
     * Возвращает список всех существующих категорий для списка
     *
     * @param bool $translate
     * @return array
     */
    public static function getListCategories($translate = true)
    {
        $list = ArrayHelper::getColumn(self::find()->select('category')->distinct()->asArray()->all(), 'category');

        $result = [];
        foreach ($list as $item) {
            $result[$item] = $translate ? Yii::t($item, $item) : $item;
        }
        asort($result);
        return $result;
    }

    public function getTranslatedCategory()
    {
        return Yii::t($this->category, $this->category);
    }

}
