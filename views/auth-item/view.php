<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;
use pravda1979\core\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\AuthItem */

$this->title = Yii::t('AuthItem', 'Viewing') . ': ' . $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('AuthItem', 'Auth Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{create}{update}{delete}{backup}', 'model' => $model]); ?>

<div class="auth-item-view box box-primary">
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'parentItems',
                    'value' => $this->render('_related_items', ['items' => $model->getParents()->orderBy(['type' => SORT_ASC])->all()]),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'type',
                    'value' => $model->typeName,
                ],
                [
                    'attribute' => 'name',
                    'value' => $model->nameT,
                    'format' => 'html',
                ],
                'description:ntext',
                'rule_name',
                'data',
                [
                    'attribute' => 'childrenItems',
                    'value' => $this->render('_related_items', ['items' => $model->getChildren()->orderBy(['type' => SORT_ASC])->all()]),
                    'format' => 'raw',
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
