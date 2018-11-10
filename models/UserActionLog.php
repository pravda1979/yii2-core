<?php

namespace pravda1979\core\models;

use Yii;
use pravda1979\core\components\validators\StringFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * This is the model class for table "{{%user_action_log}}".
 *
 * @property int $id
 * @property string $controller
 * @property string $action
 * @property string $route
 * @property string $method
 * @property string $user_ip
 * @property string $url
 * @property string $note
 * @property int $status_id
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Status $status
 * @property User $user
 */
class UserActionLog extends \pravda1979\core\components\core\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_action_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['controller', 'action', 'route', 'method', 'user_ip', 'url', 'note'], StringFilter::className()],
            [['controller', 'action', 'route', 'method', 'user_ip', 'url', 'note', 'updated_at'], 'default', 'value' => null],
            [['url', 'note'], 'string'],
            [['status_id', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['controller', 'action', 'route', 'method', 'user_ip'], 'string', 'max' => 255],
            [['status_id', 'user_id'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
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
            'id' => Yii::t('UserActionLog', 'ID'),
            'controller' => Yii::t('UserActionLog', 'Controller'),
            'action' => Yii::t('UserActionLog', 'Action'),
            'route' => Yii::t('UserActionLog', 'Route'),
            'method' => Yii::t('UserActionLog', 'Method'),
            'user_ip' => Yii::t('UserActionLog', 'User IP'),
            'url' => Yii::t('UserActionLog', 'Url'),
            'note' => Yii::t('UserActionLog', 'Note'),
            'status_id' => Yii::t('UserActionLog', 'Status ID'),
            'user_id' => Yii::t('UserActionLog', 'User ID'),
            'created_at' => Yii::t('UserActionLog', 'Created At'),
            'updated_at' => Yii::t('UserActionLog', 'Updated At'),
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
     * @return \pravda1979\core\queries\UserActionLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \pravda1979\core\queries\UserActionLogQuery(get_called_class());
    }

    public function getFullName()
    {
        $name = $this->user_id ? $this->user->username . ' - ' : '';
        $name .= $this->getControllerT() . ': ' . $this->getActionT();
        return $name;
    }

    public function getControllerT()
    {
        $name = Yii::t($this->controller, Inflector::camel2words($this->controller));
        return $name;
    }

    public function getActionT()
    {
        $name = Yii::t('actions', $this->action);
        return $name;
    }

    public static function getActionList()
    {

        $actions = SourceMessage::find()->select('message')->where(['and', ['category' => 'actions'], ['<>', 'message', 'actions']])->asArray()->all();
        $actions = ArrayHelper::getColumn($actions, 'message');

        $result = [];
        foreach ($actions as $action) {
            $result[$action] = Yii::t('actions', $action);
        }
        asort($result);
        return $result;
    }

    public static function getControllerList()
    {
        $models = SourceMessage::find()->select('message')->where(['and', ['category' => 'models'], ['<>', 'message', 'models']])->asArray()->all();
        $models = ArrayHelper::getColumn($models, 'message');

        $result = [];
        foreach ($models as $model) {
            $result[$model] = Yii::t('models', $model);
        }
        asort($result);
        return $result;
    }
}
