<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use pravda1979\core\models\OptionsForm;

/* @var $this yii\web\View */
/* @var $model OptionsForm */
/* @var $form ActiveForm */

$this->title = Yii::t('Options', 'Options');
$this->params['breadcrumbs'][] = $this->title;
?>

    <?php $form = ActiveForm::begin([
        'method' => 'post',
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

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Ведение журналов</h3>
    </div>
    <div class="box-body">
        <?= $form->field($model, 'app_UseBackups')->dropDownList([1 => "Да", 0 => "Нет"]) ?>

        <?= $form->field($model, 'app_UseUserActionLog')->dropDownList([1 => "Да", 0 => "Нет"]) ?>
    </div>
</div>


<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Тема оформления</h3>
    </div>
    <div class="box-body">
        <?= $form->field($model, 'app_Theme')->dropDownList(OptionsForm::$listThemes, ['onchange' => 'if(this.value=="lteadmin") { $("#lteadmin").show("slow") } else { $("#lteadmin").hide("slow")}']) ?>

        <div id="lteadmin" style="display: <?= $model->app_Theme == 'lteadmin' ? 'block' : 'none' ?>">

            <?= $form->field($model, 'app_LTEAdminMenuState')->dropDownList(OptionsForm::$adminlteLeftMenu) ?>

            <?= $form->field($model, 'app_LTEAdminSkin')->dropDownList(OptionsForm::$listSkins) ?>

        </div>
    </div>
</div>


        <?= Html::submitButton(Yii::t('Options', 'Save'), ['class' => 'btn btn-success btn-flat btn-block']) ?>
    <?php ActiveForm::end(); ?>

