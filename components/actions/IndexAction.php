<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 26.10.2018
 * Time: 15:50
 */

namespace pravda1979\core\components\actions;

use pravda1979\core\components\core\Action;
use Yii;
use yii\base\InvalidConfigException;

class IndexAction extends Action
{
    public $searchModelClass = '';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->searchModelClass === null) {
            throw new InvalidConfigException('The "searchModelClass" property must be set.');
        }
    }

    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $searchModel = new $this->searchModelClass;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}