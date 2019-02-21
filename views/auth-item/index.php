<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $searchModel pravda1979\core\searches\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('AuthItem', 'Auth Items');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{create}{search}{reset}']); ?>

<div class="auth-item-index box box-primary">
    <?php Pjax::begin(); ?>
    <?= $this->render('_search', ['model' => $searchModel]) ?>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\ActionColumn'],
                // ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'name',
                    'value' => 'nameT'
                ],
                [
                    'attribute' => 'type',
                    'value' => function ($data) {
                        return $data->typeName;
                    },
                    'filter' => \pravda1979\core\models\AuthItem::getListTypes()
                ],
                'description:ntext',
//                'rule_name',
//                'data',
                // 'created_at',
                // 'updated_at',

            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
