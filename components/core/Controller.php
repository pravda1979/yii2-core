<?php

namespace pravda1979\core\components\core;

use pravda1979\core\models\User;
use Yii;
use yii\web\ForbiddenHttpException;

class Controller extends \yii\web\Controller
{

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
        $actionId = Yii::$app->requestedRoute;
//        Yii::warning(Yii::$app->requestedRoute);
        $user = Yii::$app->user;

        if ($user->can('/' . $actionId)) {
            return true;
        }

        $this->denyAccess($user);
        return false;
    }
}
