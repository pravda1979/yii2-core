<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $searchModel pravda1979\core\searches\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('User', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{create}{search}{reset}']); ?>

<div class="user-index box box-primary">
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
                'username',
                'auth_key',
                'password_hash',
                'password_reset_token',
               // 'email:email',
               // 'name',
               // 'user_state',
               // 'note:ntext',
                // [
                //     'attribute' => 'status_id',
                //     'value' => 'status.fullName',
                //     'filter' => Status::getListWithGroup(),
                // ],
                // [
                //     'attribute' => 'user_id',
                //     'value' => 'user.fullName',
                //     'filter' => User::getList(),
                // ],
                // 'created_at:datetime',
                // 'updated_at:datetime',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
