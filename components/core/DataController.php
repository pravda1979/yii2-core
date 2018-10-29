<?php

namespace pravda1979\core\components\core;

use Yii;
use pravda1979\core\components\core\Controller;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class DataController extends Controller
{
    const modelClass = '';
    const searchModelClass = '';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => 'pravda1979\core\components\actions\IndexAction',
                'searchModelClass' => $this::searchModelClass,
            ],
            'create' => [
                'class' => 'pravda1979\core\components\actions\CreateAction',
                'modelClass' => $this::modelClass,
            ],
            'update' => [
                'class' => 'pravda1979\core\components\actions\UpdateAction',
                'modelClass' => $this::modelClass,
            ],
            'view' => [
                'class' => 'pravda1979\core\components\actions\ViewAction',
                'modelClass' => $this::modelClass,
            ],
            'delete' => [
                'class' => 'pravda1979\core\components\actions\DeleteAction',
                'modelClass' => $this::modelClass,
            ],
        ];
    }

}
