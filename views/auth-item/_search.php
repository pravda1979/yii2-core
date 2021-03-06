<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\searches\AuthItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-item-search">
    <div class="modal fade auth-item-modal-dialog search-modal-dialog" role="dialog">
        <div class="modal-dialog modal-dialog-scroll modal-lg">

            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'action' => ['/' . Yii::$app->requestedRoute],
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
                        <span aria-hidden="true">&times;</span><span class="sr-only"><?= Yii::t('app', 'Close') ?></span>
                    </button>
                    <h4 class="modal-title"><?= Yii::t('AuthItem', 'Search') ?></h4>
                </div>

                <div class="modal-body modal-body-scroll">

                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'type')->dropDownList(\pravda1979\core\models\AuthItem::getListTypes(), ['prompt' => '']) ?>

                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'rule_name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'data')->textInput() ?>

                    <?= $form->field($model, 'created_at')->textInput() ?>

                    <?= $form->field($model, 'updated_at')->textInput() ?>

                </div>

                <div class="modal-footer">
                    <?= Html::submitButton(Html::tag('span', '', ['class' => 'glyphicon glyphicon-search']) . ' ' . Yii::t('app', 'Find'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-ban-circle']) . ' ' . Yii::t('app', 'Reset filter'), ['/' . Yii::$app->controller->route], ['class' => 'btn btn-warning', 'data-pjax' => 0]) ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=  Yii::t('app', 'Cancel') ?></button>
                </div>

            <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
