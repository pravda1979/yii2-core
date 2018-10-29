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
use yii\web\NotFoundHttpException;

class ViewAction extends \pravda1979\core\components\core\Action
{
    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass;

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

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return $this->controller->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Status model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ($this->modelClass)::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('User', 'The requested page does not exist.'));
    }
}