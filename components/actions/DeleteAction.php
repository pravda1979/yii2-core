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
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class DeleteAction extends \pravda1979\core\components\core\Action
{
    public $modelClass = '';
    public $scenario = 'delete';

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
        $model->scenario = $this->scenario;

//        if ($this->checkAccess) {
//            call_user_func($this->checkAccess, $this->id, $model);
//        }

        $label = $model->fullName;

        try {
            $isDeleted = $model->delete();
        } catch (Exception $e) {
            $isDeleted = false;
            Yii::$app->getSession()->addFlash('error', $e->getMessage());
            return $this->controller->goBack(Url::to(['view', 'id' => $model->primaryKey]));
        }

        if (!$isDeleted) {
            Yii::$app->getSession()->addFlash('error', Html::errorSummary($model, ['header' => '']));
        } else {
            Yii::$app->getSession()->addFlash('success', Yii::t('app', 'Record "{label}" has been deleted successfully.', ['label' => $label]));
        }

        if (Yii::$app->getUser()->getReturnUrl() == Url::to(['view', 'id' => $id]))
            Url::remember(['index']);

        return $this->controller->goBack(\yii\helpers\Url::to(['index']));
    }

}