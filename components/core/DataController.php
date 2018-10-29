<?php

namespace pravda1979\core\components\core;

use Yii;
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
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ]);
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => 'pravda1979\core\components\actions\IndexAction',
                'searchModelClass' => $this::searchModelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => 'pravda1979\core\components\actions\CreateAction',
                'modelClass' => $this::modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'update' => [
                'class' => 'pravda1979\core\components\actions\UpdateAction',
                'modelClass' => $this::modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => 'pravda1979\core\components\actions\ViewAction',
                'modelClass' => $this::modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'delete' => [
                'class' => 'pravda1979\core\components\actions\DeleteAction',
                'modelClass' => $this::modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
    }

}
