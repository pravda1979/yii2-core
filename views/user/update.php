<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\User */

$this->title = Yii::t('User', 'Updating')  . ': '.  $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('User', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('User', 'Updating');
?>
<div class="user-update">

    <?=  \pravda1979\core\widgets\EntryMenu::widget(['template'=>'{index}{create}}{view}{delete}{backup}', 'model' => $model]); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
