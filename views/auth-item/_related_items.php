<?php

use yii\bootstrap\Html;
use pravda1979\core\models\AuthItem;
use yii\bootstrap\ButtonDropdown;

/* @var $items [] \pravda1979\core\models\AuthItem */

$result = '';
foreach ($items as $model) {
    echo Html::tag('div',
        ButtonDropdown::widget([
            'encodeLabel' => false,
            'label' => $model->type == AuthItem::$TYPE_ROLE ? Yii::t('role', $model->name) : $model->name,
            'tagName' => 'a',
            'split' => true,
            'dropdown' => [
                'items' => [
                    ['label' => Yii::t('AuthItem', 'View'), 'url' => ['/core/auth-item/view', 'id' => $model->name]],
                    ['label' => Yii::t('AuthItem', 'Update'), 'url' => ['/core/auth-item/update', 'id' => $model->name]],
                ],
            ],
            'options' => [
                'href' => ['/core/auth-item/view', 'id' => $model->name],
                'class' => 'btn btn-sm ' . ($model->type == AuthItem::$TYPE_ROLE ? 'btn-primary' : 'btn-default'),
            ]
        ]),
        ['style' => 'margin: 5px; display: inline-block;']
    );
}
