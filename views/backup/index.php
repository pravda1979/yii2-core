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

$this->title = Yii::t('Backup', 'Backups');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{search}{reset}']); ?>

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
                [
                    'attribute' => 'record_short_class',
                    'value' => 'shortClassT',
                    'filter' => UserActionLog::getControllerList(),
                ],
//                'record_id',
//                'record_class',
                'record_name',
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
                                'toggle' => 'tooltip',
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
