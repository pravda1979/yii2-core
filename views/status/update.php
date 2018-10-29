<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\Status */

$this->title = Yii::t('Status', 'Updating')  . ': '.  $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('Status', 'Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('Status', 'Updating');
?>
<div class="status-update">

    <?=  \pravda1979\core\widgets\EntryMenu::widget(['template'=>'{index}{create}}{view}{delete}{backup}', 'model' => $model]); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
