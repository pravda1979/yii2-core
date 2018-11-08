<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\Backup */

$this->title = Yii::t('Backup', 'Viewing')  . ': '.  $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('Backup', 'Backups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}', 'model' => $model]); ?>

<div class="backup-view box box-primary">
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'action',
                'record_short_class',
                'record_class',
                'record_id',
                'record_name',
                'changes:html',
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
