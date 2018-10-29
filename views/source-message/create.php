<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\SourceMessage */

$this->title = Yii::t('SourceMessage', 'Creating');
$this->params['breadcrumbs'][] = ['label' => Yii::t('SourceMessage', 'Source Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-message-create">

    <?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{create}']); ?>

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
