<?php

namespace pravda1979\core\components\core;

use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;
use Yii;
use yii\web\User;
use yii\di\Instance;

/**
 * Access Control Filter (ACF) is a simple authorization method that is best used by applications that only need some simple access control.
 * As its name indicates, ACF is an action filter that can be attached to a controller or a module as a behavior.
 * ACF will check a set of access rules to make sure the current user can access the requested action.
 *
 * To use AccessControl, declare it in the application config as behavior.
 * For example.
 *
 * ~~~
 * 'as access' => [
 *     'class' => 'mdm\admin\components\AccessControl',
 *     'allowActions' => ['site/login', 'site/error']
 * ]
 * ~~~
 *
 * @property User $user
 */
class AccessControl extends ActionFilter
{
    /**
     * @var User User for check access.
     */
    private $_user = 'user';

    /**
     * @var array List of action that not need to check access.
     */
    public $allowActions = [];

    /**
     * Get user
     * @return User
     */
    public function getUser()
    {
        if (!$this->_user instanceof User) {
            $this->_user = Instance::ensure($this->_user, User::className());
        }
        return $this->_user;
    }

    /**
     * Set user
     * @param User|string $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $actionId = '/' . $action->getUniqueId();
        $user = $this->getUser();
//        Yii::warning($user->can($actionId), 'AccessControl: '.$actionId);
        if ($user->can($actionId))
            return true;

        $this->denyAccess($user);
        return false;
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
     * @inheritdoc
     */
    protected function isActive($action)
    {
        $uniqueId = '/'.$action->getUniqueId();
        if ($uniqueId === Yii::$app->getErrorHandler()->errorAction) {
            return false;
        }

//        Yii::warning($uniqueId);
//        Yii::warning($action->controller->hasProperty('allowActions'));
//        Yii::warning($action->controller->allowActions);
        if ($action->controller->hasProperty('allowActions') && in_array($uniqueId, $action->controller->allowActions)) {
            return false;
        }

        return true;
    }


//    /**
//     * Проверка фильтров
//     * @param null $query
//     * @param null $model
//     */
//    public static function checkFilters($action, $query = null, $model = null, $alias = null)
//    {
//        if ($query === null && $model === null)
//            return;
//
//        /** @var \pravda1979\core\Module $module */
//        $module = Yii::$app->getModule('core');
//
//        $authManager = Yii::$app->authManager;
//        $rolesWithRules = Yii::$app->db->createCommand('select name from{{%' . $module->tableNames['auth_item'] . '}} where not rule_name is null')->queryAll();
//        foreach ($rolesWithRules as $row) {
//            $filterRoleName = $row['name'];
//            if (Yii::$app->user->can($filterRoleName)) {
//                $role = $authManager->getRole($filterRoleName);
//                $rule = $authManager->getRule($role->ruleName);
//                $ruleClass = $rule::className();
//                if ($query !== null && $model !== null) {
//                    $ruleClass::checkQuery($query, $model, $alias);
//                } elseif ($model !== null) {
//                    $ruleClass::checkModel($model);
//                }
//            }
//        }
//    }

}