<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $searchModel pravda1979\core\searches\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('Message', 'Messages');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{create}{search}{reset}']); ?>

<div class="message-index box box-primary">
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
//                'language',
                [
                    'attribute' => 'category',
                    'value' => 'sourceMessage.translatedCategory',
                    'filter' => \pravda1979\core\models\SourceMessage::getListCategories(),
                ],
                [
                    'attribute' => 'message',
                    'value' => 'sourceMessage.message',
                ],
                'translation:ntext',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
