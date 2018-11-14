<?php

namespace pravda1979\core\controllers;

use pravda1979\core\components\core\BackendController;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `core` module
 */
class DefaultController extends BackendController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $result = [
            'as access' => [
                'allowActions' => [
                    '/core/default/delete-cache',
                ]
            ]
        ];
        return ArrayHelper::merge(parent::behaviors(), $result);
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
