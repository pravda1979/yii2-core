<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\BackupAttribute */

$this->title = Yii::t('BackupAttribute', 'Creating');
$this->params['breadcrumbs'][] = ['label' => Yii::t('BackupAttribute', 'Backup Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="backup-attribute-create">

    <?= \pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{create}']); ?>

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
