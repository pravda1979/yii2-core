<?php

namespace pravda1979\core\controllers;

use pravda1979\core\components\core\BackendController;
use Yii;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `core` module
 */
class DefaultController extends BackendController
{
    public function allowAction()
    {
        return [
            '/core/default/delete-cache',
        ];
    }

    /**
     * Очищает весь кэш
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function actionDeleteCache()
    {
        if (Yii::$app->user->can('admin')) {
            Yii::$app->cache->flush();
            Yii::$app->getSession()->addFlash('success', "Кэш очищен.");

            return $this->goBack(Yii::$app->homeUrl);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }
}
