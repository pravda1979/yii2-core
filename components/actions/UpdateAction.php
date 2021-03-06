<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 26.10.2018
 * Time: 15:50
 */

namespace pravda1979\core\components\actions;

use pravda1979\core\components\core\ActiveRecord;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;

class UpdateAction extends \pravda1979\core\components\core\Action
{
    public $modelClass = '';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->modelClass === null) {
            throw new InvalidConfigException('The "modelClass" property must be set.');
        }
    }

    public function run($id)
    {
        /** @var ActiveRecord $model */

        $model = $this->findModel($id);
//        $model->setDefaultValues();

//        if ($this->checkAccess) {
//            call_user_func($this->checkAccess, $this->id, $model);
//        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!$model->save(false)) {
                Yii::$app->getSession()->addFlash('error', Html::errorSummary($model, ['header' => '']));
            } else {
                $label = Html::a($model->fullName, ['view', 'id' => $model->primaryKey]);
                Yii::$app->getSession()->addFlash('success', Yii::t('app', 'Record "{label}" has been updated successfully.', ['label' => $label]));
                return $this->controller->goBack(\yii\helpers\Url::to(['view', 'id' => $model->primaryKey]));
            }
        }

        return $this->controller->render('update', [
            'model' => $model,
        ]);
    }
}