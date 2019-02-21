<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\Backup */

$this->title = Yii::t('Backup', 'Viewing')  . ': '.  $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('Backup', 'Backups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{undo}', 'model' => $model, 'buttons' => [
    'undo' => ['label' => Html::icon('floppy-remove'), 'url' => ['undo', 'id' => $model->id],
        'linkOptions' => [
            'title' => Yii::t('Backup', 'Undo changes'),
            'data' => [
                'confirm' => Yii::t('Backup', 'Are you sure you want to undo this changes?'),
                'method' => 'post',
                'data-pjax' => 0,
            ],
        ]
    ],
]]); ?>

<div class="backup-view box box-primary">
    <div class="box-body table-responsive no-padding table-wrap">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'action',
                    'value' =>$model->actionT,
                ],
                [
                    'attribute' => 'record_short_class',
                    'value' =>$model->shortClassT,
                ],
//                'record_class',
                'record_id',
                [
                    'attribute' => 'record_name',
                    'value' => function ($model) {
                        if ($model->parent === null)
                            return Html::tag("del", $model->record_name, ['title' => Yii::t('Backup', 'Record deleted')]);
                        $url = \yii\helpers\Url::to(['/' . \yii\helpers\Inflector::camel2id($model->record_short_class) . '/view', 'id' => $model->record_id]);
                        return Html::a($model->record_name, $url, ['title' => Yii::t('Backup', 'Go to parent record'), 'data-pjax' => 0]);

                    },
                    'format' => 'html',
                ],
                'changes:html',
                'note:ntext',
                [
                    'attribute' => 'status_id',
                    'value' => ArrayHelper::getValue($model, 'status.fullName'),
                ],
                [
                    'attribute' => 'user_id',
                    'value' =>  ArrayHelper::getValue($model, 'user.fullName'),
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
