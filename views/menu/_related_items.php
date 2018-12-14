<?php

use yii\bootstrap\Html;
use pravda1979\core\models\AuthItem;
use yii\bootstrap\ButtonDropdown;

/* @var $items[] \pravda1979\core\models\Menu*/
/** @var \pravda1979\core\models\Menu $model */
?>

<div class="split-dropdown-buttons">
    <?php
    $result = '';
    foreach ($items as $model) {
        echo Html::tag('div',
            ButtonDropdown::widget([
                'encodeLabel' => false,
                'label' => Yii::t('menu.main', $model->label),
                'tagName' => 'a',
                'split' => true,
                'dropdown' => [
                    'encodeLabels' => false,
                    'items' => [
                        ['label' => Html::icon('eye-open', ['style' => 'margin-right:10px;']) . Yii::t('Menu', 'View'), 'url' => ['/core/menu/view', 'id' => $model->id]],
                        ['label' => Html::icon('pencil', ['style' => 'margin-right:10px;']) . Yii::t('Menu', 'Update'), 'url' => ['/core/menu/update', 'id' => $model->id]],
                    ],
                ],
                'options' => [
                    'href' => ['/core/menu/view', 'id' => $model->id],
                    'class' => 'btn btn-sm ' . ($model->hasChildren() ? 'btn-primary' : 'btn-default'),

                ]
            ]),
            ['class' => 'split-button']
        );
    }
    ?>
</div>