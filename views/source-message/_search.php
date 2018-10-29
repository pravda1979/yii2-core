<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model pravda1979\core\searches\SourceMessageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="source-message-search">
    <div class="modal fade source-message-modal-dialog search-modal-dialog" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">

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
                    <h4 class="modal-title"><?= Yii::t('SourceMessage', 'Search') ?></h4>
                </div>

                <div class="modal-body">

                    <?= $form->field($model, 'id')->textInput() ?>

                    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

                </div>

                <div class="modal-footer">
                    <?= Html::submitButton(Html::tag('span', Html::tag('span', ' ' . Yii::t('app', 'Find')), ['class' => 'fa fa-search']), ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Html::tag('span', ' ' . Yii::t('app', 'Reset filter'), ['class' => 'fa fa-ban']), ['/' . Yii::$app->controller->route], ['class' => 'btn btn-warning', 'data-pjax' => 0]) ?>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>