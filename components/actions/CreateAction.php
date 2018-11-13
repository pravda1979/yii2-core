<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 26.10.2018
 * Time: 15:50
 */

namespace pravda1979\core\components\actions;

use pravda1979\core\components\core\Action;
use pravda1979\core\components\core\ActiveRecord;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

class CreateAction extends Action
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

    public function run()
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClass;

//        if ($this->checkAccess) {
//            call_user_func($this->checkAccess, $this->id);
//        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!$model->save(false)) {
                Yii::$app->getSession()->addFlash('error', Html::errorSummary($model, ['header' => '']));
            }else{
                return $this->controller->goBack(['view', 'id' => $model->id]);
            }
        }

        return $this->controller->render('create', [
            'model' => $model,
        ]);
    }
}