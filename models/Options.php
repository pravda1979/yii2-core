<?php

namespace pravda1979\core\models;

use Symfony\Component\Debug\Tests\Fixtures\CaseMismatch;
use Yii;
use pravda1979\core\components\validators\StringFilter;

/**
 * This is the model class for table "{{%options}}".
 *
 * @property int $id
 * @property string $category
 * @property string $name
 * @property string $value
 * @property string $note
 * @property int $status_id
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Status $status
 * @property User $user
 */
class Options extends \pravda1979\core\components\core\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%options}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category', 'name', 'value', 'note'], StringFilter::className()],
            [['category', 'name', 'value', 'note', 'updated_at'], 'default', 'value' => null],
            [['category', 'name'], 'required'],
            [['value', 'note'], 'string'],
            [['status_id', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['category', 'name'], 'string', 'max' => 255],
            [['status_id', 'user_id'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
            [['category', 'name'], 'unique', 'targetAttribute' => ['category', 'name']],
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
            'id' => Yii::t('Options', 'ID'),
            'category' => Yii::t('Options', 'Category'),
            'name' => Yii::t('Options', 'Name'),
            'value' => Yii::t('Options', 'Value'),
            'note' => Yii::t('Options', 'Note'),
            'status_id' => Yii::t('Options', 'Status ID'),
            'user_id' => Yii::t('Options', 'User ID'),
            'created_at' => Yii::t('Options', 'Created At'),
            'updated_at' => Yii::t('Options', 'Updated At'),
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
     * @return \pravda1979\core\queries\OptionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \pravda1979\core\queries\OptionsQuery(get_called_class());
    }

    /**
     * @return mixed|string
     */
    public function getFullName()
    {
        return Yii::t('Options', $this->name);
    }

    /**
     * @inheritdoc
     */
    public function getBackupLabels()
    {
        switch ($this->name) {
            case 'app_UseBackups';
            case 'app_UseUserActionLog':
                $labels = ['value' => Yii::$app->formatter->asBoolean($this->value)];
                break;
            case 'app_LTEAdminMenuState':
                $labels = ['value' => OptionsForm::$adminlteLeftMenu[$this->value]];
                break;
            case 'app_Theme':
                $labels = ['value' => OptionsForm::$listThemes[$this->value]];
                break;
            case 'app_LTEAdminSkin':
                $labels = ['value' => OptionsForm::$listSkins[$this->value]];
                break;
            default:
                $labels = [];
        }
        return array_merge(parent::getBackupLabels(), $labels, [
            'name' => $this->fullName,
        ]);

    }

}
