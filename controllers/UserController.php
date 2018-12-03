<?php

namespace pravda1979\core\controllers;

use pravda1979\core\models\LoginForm;
use pravda1979\core\models\User;
use Yii;
use pravda1979\core\components\core\DataController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends DataController
{
    const modelClass = 'pravda1979\core\models\User';
    const searchModelClass = 'pravda1979\core\searches\UserSearch';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionProfile()
    {
        if (Yii::$app->user->isGuest)
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));

        $model = User::findOne(Yii::$app->user->id);
        $model->scenario = 'profile';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!$model->save(false)) {
                Yii::$app->getSession()->addFlash('error', Html::errorSummary($model, ['header' => '']));
            } else {
                return $this->goBack();
            }
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }
}
