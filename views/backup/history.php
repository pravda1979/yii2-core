<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;
use pravda1979\core\models\UserActionLog;

/* @var $this yii\web\View */
/* @var $searchModel pravda1979\core\searches\BackupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \pravda1979\core\components\core\ActiveRecord */

$modelClass = basename($model->className());
$this->title = Yii::t('Backup', 'Index') . ': ' . Yii::t($modelClass, $modelClass) . " \"$model->fullName\"";
$this->params['breadcrumbs'][] = ['label' => Yii::t('Backup', 'Backups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{view}', 'model' => $model, 'buttons' => [
    'view' => [
        'label' => Html::tag('span', Html::tag('span', ' ' . Yii::t($modelClass, 'View'), ['class' => 'visible-xs-inline']), ['class' => 'glyphicon glyphicon-eye-open']),
        'url' => $url = \yii\helpers\Url::to(['/' . \yii\helpers\Inflector::camel2id($modelClass) . '/view', 'id' => $model->id]),
        'linkOptions' => ['title' => Yii::t($modelClass, 'View')],
    ],
]]); ?>


<div class="backup-index box box-primary">
    <?php Pjax::begin(); ?>
    <?= $this->render('_search', ['model' => $searchModel]) ?>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                // ['class' => 'yii\grid\SerialColumn'],

                // 'id',
                [
                    'attribute' => 'action',
                    'value' => 'actionT',
                    'filter' => UserActionLog::getActionList(),
                ],
//                [
//                    'attribute' => 'record_short_class',
//                    'value' => 'shortClassT',
//                    'filter' => UserActionLog::getControllerList(),
//                ],
//                [
//                    'attribute' => 'record_name',
//                    'value' => function ($data) {
//                        if ($data->parent === null)
//                            return Html::tag("del", $data->record_name, ['title' => Yii::t('Backup', 'Record deleted')]);
//                        $url = \yii\helpers\Url::to(['/' . \yii\helpers\Inflector::camel2id($data->record_short_class) . '/view', 'id' => $data->record_id]);
////                        if ($data->record_class == \pravda1979\core\models\Options::className())
////                            $url = ['/core/options/all'];
//                        return Html::a($data->record_name, $url, ['title' => Yii::t('Backup', 'Go to parent record'), 'data-pjax' => 0]);
//
//                    },
//                    'format' => 'html',
//                ],
//                'record_id',
//                'record_class',
                'changes:html',
                // 'note:ntext',
                // [
                //     'attribute' => 'status_id',
                //     'value' => 'status.fullName',
                //     'filter' => Status::getListWithGroup(),
                // ],
                [
                    'attribute' => 'user_id',
                    'value' => 'user.fullName',
                    'filter' => User::getList(),
                ],
//                'created_at:datetime',
                'updated_at:datetime',

                [
                    'class' => 'yii\grid\ActionColumn', 'template' => '{view} {undo}', 'buttons' => [
                    'undo' => function ($url, $model) {
                        return Html::a(Html::icon('floppy-remove'), ['undo', 'id' => $model->id], [
                            'title' => Yii::t('Backup', 'Undo changes'),
                            'data' => [
                                'confirm' => Yii::t('Backup', 'Are you sure you want to undo this changes?'),
                                'method' => 'post',
                                'data-pjax' => 0,
                            ],
                        ]);
                    },
                ]
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
