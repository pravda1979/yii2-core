<?php

namespace pravda1979\core\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Options form
 */
class OptionsForm extends Model
{
    private $_oldAttributes = [];
    public static $listThemes = ['lteadmin' => 'AdminLTE', 'basic' => 'Bootstrap 3'];
    public static $adminlteLeftMenu = [1 => "Свернуто", 0 => "Развернуто"];
    public static $listSkins = [
        'skin-blue' => 'blue',
        'skin-blue-light' => 'blue-light',
        'skin-yellow' => 'yellow',
        'skin-yellow-light' => 'yellow-light',
        'skin-green' => 'green',
        'skin-green-light' => 'green-light',
        'skin-purple' => 'purple',
        'skin-purple-light' => 'purple-light',
        'skin-red' => 'red',
        'skin-red-light' => 'red-light',
        'skin-black' => 'black',
        'skin-black-light' => 'black-light',
    ];

    public $app_LTEAdminMenuState = 1;
    public $app_LTEAdminSkin = 1;
    public $app_UseBackups = 1;
    public $app_UseUserActionLog = 1;
    public $app_Theme = 1;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['app_Theme', 'app_LTEAdminSkin',], 'string'],
            [['app_LTEAdminMenuState', 'app_UseBackups', 'app_UseUserActionLog'], 'boolean'],
            [['app_LTEAdminMenuState', 'app_UseBackups', 'app_UseUserActionLog'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_LTEAdminMenuState' => Yii::t('Options', 'app_LTEAdminMenuState'),
            'app_LTEAdminSkin' => Yii::t('Options', 'app_LTEAdminSkin'),
            'app_UseBackups' => Yii::t('Options', 'app_UseBackups'),
            'app_UseUserActionLog' => Yii::t('Options', 'app_UseUserActionLog'),
            'app_Theme' => Yii::t('Options', 'app_Theme'),
        ];
    }

    public function init()
    {
        parent::init();
        foreach ($this->attributes as $key => $value) {
            $this->$key = ArrayHelper::getValue(Yii::$app->params, $key);
        }
        $this->validate();
        $this->_oldAttributes = $this->attributes;
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        foreach ($this->attributes as $attribute => $value) {
            if ($this->_oldAttributes[$attribute] != $value) {
                $model = Options::find()->where(['name' => $attribute])->one();
                if ($model === null) {
                    $model = new Options();
                    $model->name = $attribute;
                    $name = explode("_", $attribute);
                    $model->category = $name[0];
                }
                $model->value = $value;
                if (!$model->save()) {
                    $this->addError($attribute, Html::errorSummary($model), ['header' => '']);
                    return false;
                }
            }
        }
        return true;
    }

    public static function getItem($key)
    {
        $newForm = new static();
        return $newForm->$key;
    }
}
