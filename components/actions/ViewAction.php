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
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class ViewAction extends \pravda1979\core\components\core\Action
{
    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass;
    public $scenario = 'view';

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

        Url::remember();
        return $this->controller->render('view', [
            'model' => $model,
        ]);
    }
}