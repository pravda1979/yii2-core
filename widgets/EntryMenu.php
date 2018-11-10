<?php

namespace pravda1979\core\widgets;

use pravda1979\core\models\Backup;
use Yii;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Inflector;

class EntryMenu extends \yii\bootstrap\Widget
{
    public $template = '{create}{search}{reset}{view}{update}{delete}{backup}';
    public $buttons = [];
    public $controller;
    public $buttonOptions = [];
    public $brandLabel;
    public $model;

    public function init()
    {
        parent::init();
        $this->initDefaultButtons();

        if (!$this->brandLabel)
            $this->brandLabel = Yii::t('app', 'Actions');

        return $this->renderWidget();
    }

    public function renderWidget()
    {
        NavBar::begin([
            'brandLabel' => $this->brandLabel,
            'brandUrl' => null,
            'innerContainerOptions' => ['class' => 'container-fluid'],
        ]);

        echo Nav::widget([
            'activateItems' => false,
            'encodeLabels' => false,
            'options' => ['class' => 'navbar-nav'],
            'items' => $this->buttons,
        ]);

        NavBar::end();
    }

    protected function getClassName()
    {
        if ($this->controller !== null)
            return $this->controller;

        return Yii::$app->controller->id;
    }

    protected function initDefaultButtons()
    {
        $modelName = Inflector::id2camel($this->getClassName());
//        $modelName = 'app';

        $name = 'create';
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = ['label' => Html::tag('span', Html::tag('span', ' ' . Yii::t($modelName, 'Create'), ['class' => 'visible-xs-inline text-muted']), ['class' => 'glyphicon glyphicon-plus text-green']), 'url' => ['create'],
                'linkOptions' => ['title' => Yii::t($modelName, 'Create')]
            ];
        }

        $name = 'search';
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = ['label' => Html::tag('span', Html::tag('span', ' ' . Yii::t($modelName, 'Search'), ['class' => 'visible-xs-inline']), ['class' => 'glyphicon glyphicon-search']), 'url' => null,
                'linkOptions' => [
                    'title' => Yii::t($modelName, 'Search'),
                    'style' => 'cursor: pointer',
                    'data-toggle' => 'modal',
                    'data-target' => '.' . $this->getClassName() . '-modal-dialog',
                ]
            ];
        }

        $name = 'reset';
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = ['label' => Html::tag('span', Html::tag('span', ' ' . Yii::t('app', 'Reset filter'), ['class' => 'visible-xs-inline']), ['class' => 'glyphicon glyphicon-ban-circle']), 'url' => ['/' . Yii::$app->controller->route],
                'linkOptions' => [
                    'title' => Yii::t('app', 'Reset filter'),
                    'data-pjax' => 1,
                ]
            ];
        }

        $name = 'view';
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = ['label' => Html::tag('span', Html::tag('span', ' ' . Yii::t($modelName, 'View'), ['class' => 'visible-xs-inline']), ['class' => 'glyphicon glyphicon-eye-open']), 'url' => ['view', 'id' => $this->model->id],
                'linkOptions' => ['title' => Yii::t($modelName, 'View')]
            ];
        }

        $name = 'index';
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = ['label' => Html::tag('span', Html::tag('span', ' ' . Yii::t($modelName, 'Index'), ['class' => 'visible-xs-inline']), ['class' => 'glyphicon glyphicon-list']), 'url' => ['index'],
                'linkOptions' => ['title' => Yii::t($modelName, 'Index')]
            ];
        }

        $name = 'update';
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = ['label' => Html::tag('span', Html::tag('span', ' ' . Yii::t($modelName, 'Update'), ['class' => 'visible-xs-inline text-muted']), ['class' => 'glyphicon glyphicon-pencil text-blue']), 'url' => ['update', 'id' => $this->model->id],
                'linkOptions' => ['title' => Yii::t($modelName, 'Update')]
            ];
        }

        $name = 'delete';
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = ['label' => Html::tag('span', Html::tag('span', ' ' . Yii::t($modelName, 'Delete'), ['class' => 'visible-xs-inline text-muted']), ['class' => 'glyphicon glyphicon-trash text-red']), 'url' => ['delete', 'id' => $this->model->id],
                'linkOptions' => [
                    'title' => Yii::t($modelName, 'Delete'),
                    'data' => [
                        'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]
            ];
        }

        $name = 'backup';
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            if (Yii::$app->user->can('/core/backup/history')) {
                $countBackup = Backup::find()->where(['record_id' => $this->model->id, 'record_class' => $this->model->className()])->count();

                $this->buttons[$name] = ['label' => Html::tag('span', Html::tag('span', ' ' . Yii::t('Backup', 'History'), ['class' => 'visible-xs-inline']), ['class' => 'glyphicon glyphicon-time']). " <span class=\"label label-primary\">$countBackup</span>",
                    'url' => ['/core/backup/history', 'BackupSearch' => ['record_id' => $this->model->id, 'record_class' => $this->model->className()]],
                    'linkOptions' => ['title' => Yii::t('Backup', 'History'), 'class' => ($countBackup ? '' : ' hidden')]
                ];
            } else {
                $this->buttons[$name] = ['label' => '', 'linkOptions' => ['class' => 'hidden']];
            }
        }

//        $name = 'set-active';
//        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
//            $this->buttons[$name] = ['label' =>Html::icon('fa fa-ok'), 'url' => ['set-active', 'id' => $this->model->id],
//                'linkOptions' => [
//                    'class' => 'btn-set-active ' . (($this->model->status->fixed_status_id === Status::$FIXED_STATUS_DRAFT && !Yii::$app->user->identity->isRequireModeration) ? '' : 'hidden'),
//                    'title' => Yii::t('app', 'Set Active'),
//                    'data' => [
//                        'confirm' => Yii::t('app', 'Are you sure you want to set active this item?'),
//                        'method' => 'post',
//                    ],
//                ]
//            ];
//        }

        $new_btn = [];
        preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use (&$new_btn) {
            $name = $matches[1];
            $new_btn[$name] = $this->buttons[$name];
        }, $this->template);

        $this->buttons = $new_btn;
    }
}
