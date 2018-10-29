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

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{create}{update}{delete}{backup}', 'model' => $model]); ?>

<div class="user-view box box-primary">
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'username',
                'auth_key',
                'password_hash',
                'password_reset_token',
                'email:email',
                'name',
                'user_state',
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
