<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\Status */

$this->title = Yii::t('Status', 'Creating');
$this->params['breadcrumbs'][] = ['label' => Yii::t('Status', 'Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="status-create">

    <?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{create}']); ?>

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
