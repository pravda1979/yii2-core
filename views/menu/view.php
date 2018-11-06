<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\Menu */

$this->title = Yii::t('Menu', 'Viewing') . ': ' . $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('Menu', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{create}{update}{delete}{backup}', 'model' => $model]); ?>

<div class="menu-view box box-primary">
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'menu_id',
                'label',
                [
                    'attribute' => 'icon',
                    'format' => 'html',
                    'value' => Html::tag('span', ' ' . $model->icon, ['class' => 'fa fa-' . $model->icon, 'title' => $model->icon]),
                ],
                'url',
                'use_url_helper:boolean',
                [
                    'attribute' => 'visible',
                    'value' => $model->getVisibleName(),
                ],
                'linkOptions:ntext',
                'position',
                'level',
                [
                    'attribute' => 'parent_id',
                    'value' => $model->getParentLabel(),
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
