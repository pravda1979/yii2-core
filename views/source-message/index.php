<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $searchModel pravda1979\core\searches\SourceMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('SourceMessage', 'Source Messages');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{create}{search}{reset}']); ?>

<div class="source-message-index box box-primary">
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
                'category',
                'message:ntext',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
