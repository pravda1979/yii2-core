<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\UserActionLog */

$this->title = Yii::t('UserActionLog', 'Viewing') . ': ' . $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('UserActionLog', 'User Action Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{backup}', 'model' => $model]); ?>

<div class="user-action-log-view box box-primary">
    <div class="box-body table-responsive no-padding table-wrap">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'controller',
                    'value' => $model->getControllerT(),
                ],
                [
                    'attribute' => 'action',
                    'value' => $model->getActionT(),
                ],
                'route',
                'method',
                'user_ip',
                [
                    'attribute' => 'url',
                    'value' => \yii\helpers\Url::to($model->url, true),
                    'format' => 'url',
                ],
                'note:ntext',
                [
                    'attribute' => 'status_id',
                    'value' => ArrayHelper::getValue($model, 'status.fullName'),
                ],
                [
                    'attribute' => 'user_id',
                    'value' => ArrayHelper::getValue($model, 'user.fullName'),
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
