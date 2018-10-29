<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\Status */

$this->title = Yii::t('Status', 'Viewing')  . ': '.  $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('Status', 'Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{create}{update}{delete}{backup}', 'model' => $model]); ?>

<div class="status-view box box-primary">
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'fixed_status_id',
                'is_default:boolean',
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
