<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\Message */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?php //echo $form->field($model, 'language')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'translation')->textarea(['rows' => 6]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('Message', 'Save'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
