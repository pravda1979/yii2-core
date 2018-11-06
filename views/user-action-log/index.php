<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;
use pravda1979\core\models\UserActionLog;

/* @var $this yii\web\View */
/* @var $searchModel pravda1979\core\searches\UserActionLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('UserActionLog', 'User Action Logs');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{search}{reset}']); ?>

<div class="user-action-log-index box box-primary">
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
                    'attribute' => 'controller',
                    'value' => 'controllerT',
                    'filter' => UserActionLog::getControllerList(),
                ],
                [
                    'attribute' => 'action',
                    'value' => 'actionT',
                    'filter' => UserActionLog::getActionList(),
                ],
                'route',
                'method',
                'user_ip',
                // 'url:ntext',
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
                'created_at:datetime',
                // 'updated_at:datetime',

                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
