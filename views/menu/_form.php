<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;
use pravda1979\core\models\Menu;


/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'menu_id')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'parent_id')->dropDownList(Menu::getList(), ['prompt' => '']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'use_url_helper')->dropDownList([1 => "Да", 0 => "Нет"]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'visible')->dropDownList(Menu::getListVisible(), ['prompt' => '']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'linkOptions')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'position')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'level')->textInput() ?>
            </div>
        </div>

        <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'status_id')->dropDownList(Status::getListWithGroup(), ['prompt' => '']) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('Menu', 'Save'), ['class' => 'btn btn-success btn-flat btn-block']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
