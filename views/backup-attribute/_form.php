<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\BackupAttribute */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="backup-attribute-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'backup_id')->textInput() ?>

        <?= $form->field($model, 'attribute')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'old_value')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'new_value')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'old_label')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'new_label')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'status_id')->dropDownList(Status::getListWithGroup(), ['prompt' => '']) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('BackupAttribute', 'Save'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
