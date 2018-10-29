<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \pravda1979\core\gii\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString('Creating') ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">

    <?= "<?= " ?>\pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{create}']); ?>

    <?= "<?= " ?>$this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
