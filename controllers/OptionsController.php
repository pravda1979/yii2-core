<?php

namespace pravda1979\core\controllers;

use pravda1979\core\components\core\BackendController;
use pravda1979\core\models\OptionsForm;
use Yii;
use yii\helpers\Html;

/**
 * OptionsController implements the CRUD actions for Options model.
 */
class OptionsController extends BackendController
{
    public function actionIndex()
    {
        $model = new OptionsForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save(false)) {
                Yii::$app->getSession()->addFlash('success', Yii::t('Options', 'Options was saved successfully.'));
                return $this->goBack(['index']);
            } else {
                Yii::$app->getSession()->addFlash('error', Html::errorSummary($model, ['header' => '']));
            }
        }

        return $this->render('form_options', [
            'model' => $model,
        ]);
    }
}
