<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;
use pravda1979\core\models\Menu;

/* @var $this yii\web\View */
/* @var $searchModel pravda1979\core\searches\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('Menu', 'Menus');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{create}{search}{reset}']); ?>

<div class="menu-index box box-primary">
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

                // 'id',
                'menu_id',
                [
                    'attribute' => 'parent_id',
                    'value' => 'parentLabel',
                    'filter' => Menu::getListExistParents(),
                ],
                [
                    'attribute' => 'visible',
                    'value' => 'visibleName',
                    'filter' => Menu::getListVisible(),
                ],
                [
                    'attribute' => 'label',
                    'value' => function ($data) {
                        return Yii::t('menu.main', $data->label);
                    },
                ],
                [
                    'attribute' => 'icon',
                    'format' => 'html',
                    'value' => function ($data) {
                        return Html::tag('span', ' ' . $data->icon, ['class' => 'fa fa-' . $data->icon, 'title' => $data->icon]);
                    },
                ],
                'url',
                // 'use_url_helper:boolean',
                // 'linkOptions:ntext',
                // 'position',
                // 'level',
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

            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
