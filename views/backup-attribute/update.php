<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\BackupAttribute */

$this->title = Yii::t('BackupAttribute', 'Updating')  . ': '.  $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('BackupAttribute', 'Backup Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('BackupAttribute', 'Updating');
?>
<div class="backup-attribute-update">

    <?=  \pravda1979\core\widgets\EntryMenu::widget(['template'=>'{index}{create}}{view}{delete}{backup}', 'model' => $model]); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
