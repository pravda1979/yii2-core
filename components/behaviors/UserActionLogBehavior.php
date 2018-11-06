<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 06.11.2018
 * Time: 17:58
 */

namespace pravda1979\core\components\behaviors;

use pravda1979\core\models\UserActionLog;
use Yii;

use yii\base\Behavior;
use yii\helpers\Inflector;
use yii\web\Controller;

class UserActionLogBehavior extends Behavior
{
    public $excludeActions = [];

    public function events()
    {
        return [
            Controller::EVENT_AFTER_ACTION => 'afterAction',
        ];
    }

    /**
     * Запись в лог информации о действии пользователя
     *
     * @param $event
     * @return mixed
     */
    public function afterAction($event)
    {
        $action = $event->action;
        $result = $event->result;

        // Отключаем логирование для автокомплита и др.
        if (in_array($action->id, $this->excludeActions))
            return $result;

        $model = new UserActionLog();

        $model->controller = Inflector::id2camel($action->controller->id);
        $model->action = $action->id;
        $model->route = $action->uniqueId;
        $model->method = Yii::$app->request->method;
        $model->user_ip = Yii::$app->request->userIP;
        $model->url = Yii::$app->request->url;
        $model->save();

        return $result;
    }
}