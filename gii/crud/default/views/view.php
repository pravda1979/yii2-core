<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \pravda1979\core\gii\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString('Viewing') ?>  . ': '.  $model->fullName;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= "<?= " ?>\pravda1979\core\widgets\EntryMenu::widget(['template' => '{index}{create}{update}{delete}{backup}', 'model' => $model]); ?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view box box-primary">
    <div class="box-body table-responsive no-padding table-wrap">
        <?= "<?= " ?>DetailView::widget([
            'model' => $model,
            'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "                '" . $name . "',\n";
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $row = $generator->generateViewColumn($column);
        echo $row;
    }
}
?>
            ],
        ]) ?>
    </div>
</div>
