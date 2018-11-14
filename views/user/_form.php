<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'password', ['enableClientValidation' => false])->passwordInput() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'password_repeat', ['enableClientValidation' => false])->passwordInput() ?>
            </div>
        </div>

        <?php //echo $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

        <?php //echo $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>

        <?php //echo $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'user_state')->dropDownList(['1' => 'Да', '0' => 'Нет'], ['prompt' => '']) ?>

        <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'status_id')->dropDownList(Status::getListWithGroup(), ['prompt' => '']) ?>

        <?php echo $form->field($model, 'userRights')->dropDownList(User::getListUserRights(), ['size' => 10, 'multiple' => 'multiple', 'prompt' => '', 'style' => 'display: block', 'id' => 'hidden_input_user_rights']) ?>

        <?= \lesha724\bootstraptree\TreeView::widget([
            'htmlOptions' => [
                'id' => 'tree_input_user_rights',
            ],
            'options' => [
                'data' => User::getRbacRules($model->id),
                'multiSelect' => true,
//                'levels' => 2
            ],
            'events' => [
                'onNodeSelected' => 'function(event, data) {selectRbacItem(data.itemId);}',
                'onNodeUnselected' => 'function(event, data) {unselectRbacItem(data.itemId);}',
                'onRendered' => 'function(event, data) {updateRbacInfo("#rbacInDb"); updateRbacInfo();}',
            ]
        ]); ?>

        <div class="row">
            <div class="col-md-6">
                <div style="text-align: center"><?= Html::label('Было') ?></div>
                <div id="rbacInDb"></div>
            </div>
            <div class="col-md-6">
                <div style="text-align: center"><?= Html::label('Текущий выбор') ?></div>
                <div id="rbacInForm"></div>
            </div>
        </div>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('User', 'Save'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
