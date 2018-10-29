<?php
/* @var $this \yii\web\View */
?>
<aside class="main-sidebar">

    <section class="sidebar">
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="<?= Yii::t('app','Search') ?>..."/>
                <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
            </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'encodeLabels' => false,
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => 'Навигация', 'options' => ['class' => 'header']],
                    [
                        'label' => 'Справочники',
                        'icon' => 'book',
                        'url' => '#',
                        'items' => [
                            ['label' => '--Роли', 'url' => '#'],
                            ['label' => '--Торговые сети', 'url' => '#'],
                            ['label' => '--Фирменные магазины', 'url' => '#'],
                            ['label' => '--КВЭДы', 'url' => '#'],
                            ['label' => '--КВЭДы', 'url' => '#'],
                            ['label' => '--КВЭДы', 'url' => '#'],
                            ['label' => '--КВЭДы', 'url' => '#'],
                            ['label' => '--КВЭДы', 'url' => '#'],
                            ['label' => '--КВЭДы', 'url' => '#'],
                            ['label' => '--КВЭДы', 'url' => '#'],
                        ],
                        'visible' => !Yii::$app->user->isGuest,
                    ],
                    [
                        'label' => 'Данные',
                        'icon' => 'files-o',
                        'url' => '#',
                        'items' => [
                            ['label' => '--Файлы', 'url' => '#'],
                            ['label' => '--Торговые объекты', 'url' => '#'],
                            ['label' => '--Архив', 'url' => '#'],
                        ],
                        'visible' => !Yii::$app->user->isGuest,
                    ],
                    [
                        'label' => 'Администрирование',
                        'icon' => 'key',
                        'url' => '#',
                        'items' => [
                            ['label' => Yii::t('menu', 'Users'), 'icon' => 'users', 'url' => [$url='/core/user/index'], 'visible'=>Yii::$app->user->can($url), 'active' => in_array(Yii::$app->controller->getRoute(), ['core/user/index', 'user/view', 'user/update', 'user/create'])],
                            ['label' => Yii::t('menu', 'Statuses'), 'url' => [$url='/core/status/index'], 'visible'=>Yii::$app->user->can($url), 'active' => in_array(Yii::$app->controller->getRoute(), ['status/index', 'status/view', 'status/update', 'status/create'])],
                            ['label' => Yii::t('menu', 'Source Messages'), 'url' => [$url='/core/source-message/index'], 'visible'=>Yii::$app->user->can($url), 'active' => in_array(Yii::$app->controller->getRoute(), ['source-message/index', 'source-message/view', 'source-message/update', 'source-message/create'])],
                            ['label' => Yii::t('menu', 'Messages'), 'url' => [$url='/core/message/index'], 'visible'=>Yii::$app->user->can($url), 'active' => in_array(Yii::$app->controller->getRoute(), ['message/index', 'message/view', 'message/update', 'message/create'])],
                            ['label' => '--Журнал изменений', 'url' => '#'],
                            ['label' => '--Журнал действий пользователей', 'url' => '#'],
                            [
                                'label' => 'Инструменты',
                                'icon' => 'wrench',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Gii', 'url' => ['/gii'], 'visible'=>Yii::$app->user->can('admin')],
                                    ['label' => 'phpMyAdmin', 'url' => '/tools/phpMyAdmin', 'visible'=>Yii::$app->user->can('admin')],
                                ],
                                'visible' => !Yii::$app->user->isGuest,
                            ],
                        ],
                        'visible' => !Yii::$app->user->isGuest,
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
