<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\Message */

$this->title = Yii::t('Message', 'Creating');
$this->params['breadcrumbs'][] = ['label' => Yii::t('Message', 'Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-create">

    <?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{create}']); ?>

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
