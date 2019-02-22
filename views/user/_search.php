<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\searches\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">
    <div class="modal fade user-modal-dialog search-modal-dialog" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scroll modal-lg">

            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        'label' => 'col-sm-3',
                        'offset' => 'col-sm-offset-3',
                        'wrapper' => 'col-sm-9',
                        'error' => '',
                        'hint' => '',
                        'options' => [
                            'data-pjax' => 1
                        ],
                    ],
                ],
            ]); ?>

            <div class="modal-content">

                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span
                                class="sr-only"><?= Yii::t('app', 'Close') ?></span>
                    </button>
                    <h4 class="modal-title"><?= Yii::t('User', 'Search') ?></h4>
                </div>

                <div class="modal-body modal-body-scroll">

                    <?= $form->field($model, 'id')->textInput() ?>

                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

                    <?php //echo $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

                    <?php //echo $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>

                    <?php //echo $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'user_state')->dropDownList(['1' => 'Да', '0' => 'Нет'], ['prompt' => '']) ?>

                    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'status_id')->dropDownList(Status::getListWithGroup(), ['prompt' => '']) ?>

                    <?= $form->field($model, 'user_id')->dropDownList(User::getList(), ['prompt' => '']) ?>

                    <?= $form->field($model, 'created_at')->textInput() ?>

                    <?= $form->field($model, 'updated_at')->textInput() ?>

                </div>

                <div class="modal-footer">
                    <?= Html::submitButton(Html::tag('span', '', ['class' => 'glyphicon glyphicon-search']) . ' ' . Yii::t('app', 'Find'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-ban-circle']) . ' ' . Yii::t('app', 'Reset filter'), ['/' . Yii::$app->controller->route], ['class' => 'btn btn-warning', 'data-pjax' => 0]) ?>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
