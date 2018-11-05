<?php
/* @var $this \yii\web\View */
?>
<aside class="main-sidebar">

    <section class="sidebar">
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="<?= Yii::t('app', 'Search') ?>..."/>
                <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
            </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'activateParents' => true,
                'encodeLabels' => false,
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => \pravda1979\core\models\Menu::getMenu('menu.main'),
            ]
        ); ?>

    </section>

</aside>
