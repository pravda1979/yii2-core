<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\SourceMessage */

$this->title = Yii::t('SourceMessage', 'Updating')  . ': '.  $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('SourceMessage', 'Source Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('SourceMessage', 'Updating');
?>
<div class="source-message-update">

    <?=  \pravda1979\core\widgets\EntryMenu::widget(['template'=>'{index}{create}}{view}{delete}{backup}', 'model' => $model]); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
