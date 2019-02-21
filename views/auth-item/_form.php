<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;
use kartik\select2\Select2;
use pravda1979\core\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-item-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'type')->dropDownList(AuthItem::getListTypes(), ['onchange' => 'if(this.value==1) { $("#children_items").show("slow") } else { $("#children_items").hide("slow")}', 'prompt' => '']) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'rule_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'data')->textInput() ?>

        <?= $form->field($model, 'childrenItems', ['options' => ['id' => 'children_items', 'style' => $model->type == 1 ? '' : 'display:none']])->widget(Select2::classname(), [
            'data' => AuthItem::getList(),
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => true,
            ],
        ]); ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('AuthItem', 'Save'), ['class' => 'btn btn-success btn-flat btn-block']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
