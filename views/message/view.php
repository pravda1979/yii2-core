<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\Message */

$this->title = Yii::t('Message', 'Viewing') . ': ' . $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('Message', 'Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{update}{delete}{backup}', 'model' => $model]); ?>

<div class="message-view box box-primary">
    <div class="box-body table-responsive no-padding table-wrap">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'sourceMessage.category',
                    'value' => ArrayHelper::getValue($model, 'sourceMessage.translatedCategory'),
                ],
                'sourceMessage.message',
//                'language',
                'translation:ntext',
            ],
        ]) ?>
    </div>
</div>
