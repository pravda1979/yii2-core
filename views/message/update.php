<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\Message */

$this->title = Yii::t('Message', 'Updating')  . ': '.  $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('Message', 'Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id, 'language' => $model->language]];
$this->params['breadcrumbs'][] = Yii::t('Message', 'Updating');
?>
<div class="message-update">

    <?=  \pravda1979\core\widgets\EntryMenu::widget(['template'=>'{index}{create}}{view}{delete}{backup}', 'model' => $model]); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
