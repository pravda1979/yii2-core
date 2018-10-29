<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\User */

$this->title = Yii::t('User', 'Creating');
$this->params['breadcrumbs'][] = ['label' => Yii::t('User', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{create}']); ?>

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
