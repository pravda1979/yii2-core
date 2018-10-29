<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \pravda1979\core\gii\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>
use pravda1979\core\models\Status;
use pravda1979\core\models\User;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>

<?= "<?= " ?>$this->render('_search', ['model' => $searchModel]) ?>
<?= "<?= " ?>\pravda1979\core\widgets\EntryMenu::widget(['template' => '{create}{search}{reset}']); ?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index box box-primary">
<?= $generator->enablePjax ? "    <?php Pjax::begin(); ?>\n" : ''
?>    <div class="box-body table-responsive">
<?php if(!empty($generator->searchModelClass)): ?>
<?= "        <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif;

if ($generator->indexWidgetType === 'grid'):
    echo "        <?= " ?>GridView::widget([
            'dataProvider' => $dataProvider,
            <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n            'layout' => \"{items}\\n{summary}\\n{pager}\",\n            'columns' => [\n" : "'layout' => \"{items}\\n{summary}\\n{pager}\",\n            'columns' => [\n"; ?>
                // ['class' => 'yii\grid\SerialColumn'],

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if ($name == 'id') {
            echo "                // '" . $name . "',\n";
        }elseif (++$count < 6) {
            echo "                '" . $name . "',\n";
        } else {
            echo "                // '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $rem=(++$count < 6)?'':'//';
        $row = $generator->generateIndexColumn($column, $rem);
        echo $row;
    }
}
?>

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
<?php else: ?>
        <?= "<?= " ?>ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView' => function ($model, $key, $index, $widget) {
                return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
            },
        ]) ?>
<?php endif; ?>
    </div>
<?= $generator->enablePjax ? "    <?php Pjax::end(); ?>\n" : '' ?>
</div>
