<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\SourceMessage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="source-message-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('SourceMessage', 'Save'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
