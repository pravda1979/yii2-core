<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\User */

$this->title = Yii::t('User', 'Updating profile')  . ': '.  $model->fullName;
$this->params['breadcrumbs'][] = Yii::t('User', 'Updating profile');
?>
<div class="user-profile">

    <div class="user-form box box-primary">
        <?php $form = ActiveForm::begin(); ?>
        <div class="box-body table-responsive">

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'password', ['enableClientValidation' => false])->passwordInput()->label(Yii::t('User', 'New Password')) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'password_repeat', ['enableClientValidation' => false])->passwordInput()->label(Yii::t('User', 'Repeat New Password')) ?>
                </div>
            </div>

            <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

        </div>
        <div class="box-footer">
            <?= $form->field($model, 'current_password', ['enableClientValidation' => false])->passwordInput() ?>
            <?= Html::submitButton(Yii::t('User', 'Save profile'), ['class' => 'btn btn-success btn-flat']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
