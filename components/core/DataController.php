<?php

namespace pravda1979\core\components\core;

use Yii;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class DataController extends BackendController
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
            'autocomplete' => [
                'class' => 'pravda1979\core\components\actions\AutocompleteTextAction',
                'modelClass' => $this::modelClass,
                'maxResultCount' => 100,
            ],
        ];
    }

}
