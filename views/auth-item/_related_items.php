<?php

use yii\bootstrap\Html;
use pravda1979\core\models\AuthItem;
use yii\bootstrap\ButtonDropdown;

/* @var $items \pravda1979\core\models\AuthItem[] */
?>

<div class="split-dropdown-buttons">
    <?php
    $result = '';
    foreach ($items as $model) {
        echo Html::tag('div',
            ButtonDropdown::widget([
                'encodeLabel' => false,
                'label' => $model->type == AuthItem::$TYPE_ROLE ? Yii::t('role', $model->name) : $model->name,
                'tagName' => 'a',
                'split' => true,
                'dropdown' => [
                    'encodeLabels' => false,
                    'items' => [
                        ['label' => Html::icon('eye-open', ['style' => 'margin-right:10px;']) . Yii::t('AuthItem', 'View'), 'url' => ['/core/auth-item/view', 'id' => $model->name]],
                        ['label' => Html::icon('pencil', ['style' => 'margin-right:10px;']) . Yii::t('AuthItem', 'Update'), 'url' => ['/core/auth-item/update', 'id' => $model->name]],
                    ],
                ],
                'options' => [
                    'href' => ['/core/auth-item/view', 'id' => $model->name],
                    'class' => 'btn btn-sm ' . ($model->type == AuthItem::$TYPE_ROLE ? 'btn-primary' : 'btn-default'),
                ]
            ]),
            ['class' => 'split-button']
        );
    }
    ?>
</div>