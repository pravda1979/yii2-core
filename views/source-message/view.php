<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\SourceMessage */

$this->title = Yii::t('SourceMessage', 'Viewing') . ': ' . $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('SourceMessage', 'Source Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{create}{update}{delete}{backup}', 'model' => $model]); ?>

<div class="source-message-view box box-primary">
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'category',
                'message:ntext',
                [
                    'label' => 'ru-RU',
                    'format' => 'html',
                    'value' => 'test' . Html::a('  Изменить', ['/message/update', 'id' => $model->id]),
                ],
            ],
        ]) ?>
    </div>
</div>
