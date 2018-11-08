<?php

namespace pravda1979\core\controllers;

use pravda1979\core\components\core\BackendController;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `core` module
 */
class DefaultController extends BackendController
{
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

            if (Yii::$app->request->referrer == Yii::$app->request->absoluteUrl) {
                return $this->goBack();
            } else {
                return $this->redirect(Yii::$app->request->referrer);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }
}
