<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\User */

$this->title = Yii::t('User', 'Viewing')  . ': '.  $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('User', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{create}{update}{delete}{backup}{send-new-password}', 'model' => $model, 'buttons' => [
    'send-new-password' => ['label' => \yii\bootstrap\Html::icon('envelope'), 'url' => ['send-new-password', 'id' => $model->id],
        'linkOptions' => [
            'title' => Yii::t('User', 'Send New Password'),
            'data' => [
                'confirm' => Yii::t('User', 'Are you sure you want to generate new password for this user?'),
                'method' => 'post',
                'data-pjax' => 0,
            ],
        ]
    ],
]]); ?>

<div class="user-view box box-primary">
    <div class="box-body table-responsive no-padding table-wrap">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'username',
//                'auth_key',
//                'password_hash',
//                'password_reset_token',
                'email:email',
                'name',
                'user_state:boolean',
                'note:ntext',
                [
                    'attribute' => 'status_id',
                    'value' => ArrayHelper::getValue($model, 'status.fullName'),
                ],
                [
                    'attribute' => 'userRights',
                    'value' =>  ArrayHelper::getValue($model, 'userRightsAsString'),
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
