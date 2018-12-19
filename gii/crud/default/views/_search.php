<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search">
    <div class="modal fade <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-modal-dialog search-modal-dialog" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">

            <?= "<?php " ?>$form = ActiveForm::begin([
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
<?php if ($generator->enablePjax): ?>
                        'options' => [
                            'data-pjax' => 1
                        ],
<?php endif; ?>
                    ],
                ],
            ]); ?>

            <div class="modal-content">

                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only"><?= "<?= Yii::t('app', 'Close') ?>" ?></span>
                    </button>
                    <h4 class="modal-title"><?= "<?= Yii::t('" . StringHelper::basename($generator->modelClass) . "', 'Search') ?>" ?></h4>
                </div>

                <div class="modal-body">

<?php
$count = 0;
foreach ($generator->getColumnNames() as $attribute) {
    echo "                    <?= " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";

//    if (++$count < 6) {
/*        echo "                <?= " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";*/
//    } else {
/*        echo "                <?php // echo " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";*/
//    }
}
?>
                </div>

                <div class="modal-footer">
                    <?= "<?= " ?>Html::submitButton(Html::tag('span', Html::tag('span', ' ' . Yii::t('app', 'Find')), ['class' => 'fa fa-search']), ['class' => 'btn btn-primary']) ?>
                    <?= "<?= " ?>Html::a(Html::tag('span', ' ' . Yii::t('app', 'Reset filter'), ['class' => 'fa fa-ban']), ['/' . Yii::$app->controller->route], ['class' => 'btn btn-warning', 'data-pjax' => 0]) ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= "<?= " ?> Yii::t('app', 'Cancel') ?></button>
                </div>

            <?= "<?php " ?>ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
