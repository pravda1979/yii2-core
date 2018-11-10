<?php

namespace pravda1979\core\components\core;

use pravda1979\core\components\behaviors\UserActionLogBehavior;
use pravda1979\core\models\User;
use Yii;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

class BackendController extends Controller
{
    public function allowAction()
    {
        return [
            '/site/error',
            '/core/user/login',
            '/core/user/logout',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $result = [];
        if (Yii::$app->params['app_UseUserActionLog']) {
            $result['userActionLog'] = [
                'class' => UserActionLogBehavior::className(),
                // Disabling log for some actions
                'excludeActions' => ['autocomplete'],
            ];
        }

        return array_merge(parent::behaviors(), $result);
    }

    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param  User $user the current user
     * @throws ForbiddenHttpException if the user is already logged in.
     */
    protected function denyAccess($user)
    {
        if ($user->getIsGuest()) {
            $user->loginRequired();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Checks the privilege of the current user.
     *
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
     *
     * @param string $action the ID of the action to be executed
     * @param object $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params
     * @return bool
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        $actionId = '/' . Yii::$app->requestedRoute;
        $user = Yii::$app->user;

//        Yii::warning($actionId);
//        Yii::warning(Yii::$app->controller->allowAction());

        if (Yii::$app->controller->hasMethod('allowAction') && in_array($actionId, Yii::$app->controller->allowAction())) {
            return true;
        }

        if ($user->can($actionId)) {
            return true;
        }

        $this->denyAccess($user);
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            return $this->checkAccess($action);
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        // Remember current url for goBack() function after create/update/delete record
        if (!$action instanceof Action && !in_array($action->getUniqueId(), ['/core/default/delete-cache', 'core/options/index'])) {
            Url::remember();
        }

        return parent::afterAction($action, $result);
    }
}
