<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \pravda1979\core\gii\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString('Updating') ?>  . ': '.  $model->fullName;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = <?= $generator->generateString('Updating') ?>;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-update">

    <?= "<?= " ?> \pravda1979\core\widgets\EntryMenu::widget(['template'=>'{index}{create}}{view}{delete}{backup}', 'model' => $model]); ?>

    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
