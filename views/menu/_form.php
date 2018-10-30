<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'menu_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'linkOptions')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'position')->textInput() ?>

        <?= $form->field($model, 'level')->textInput() ?>

        <?= $form->field($model, 'parent_id')->textInput() ?>

        <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'status_id')->dropDownList(Status::getListWithGroup(), ['prompt' => '']) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('Menu', 'Save'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
