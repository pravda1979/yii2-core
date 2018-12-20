<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $directoryAsset string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"><i class="fa fa-home"></i></span><span class="logo-lg">' . Yii::t('yii', 'Home') . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= Yii::$app->user->isGuest ? Yii::t('app', 'Guest') : Yii::$app->user->identity->name ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?= Yii::$app->user->isGuest ? Yii::t('app', 'Guest') : Yii::$app->user->identity->name ?>
                                <small>
                                    <?= Yii::$app->user->isGuest ?
                                        '' :
                                        Yii::t('User', 'Registered {date}', ['date' => Yii::$app->formatter->asDate(Yii::$app->user->identity->created_at)])
                                    ?>
                                </small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <?php if (Yii::$app->user->isGuest) { ?>
                                <div class="pull-right">
                                    <?= Html::a(
                                        Yii::t('LoginForm', 'Sign In'),
                                        ['/core/user/login'],
                                        ['class' => 'btn btn-default btn-flat']
                                    ) ?>
                                </div>
                            <?php } else { ?>
                                <div class="pull-left">
                                    <?= Html::a(
                                        Yii::t('User', 'Profile'),
                                        ['/core/user/profile'],
                                        ['class' => 'btn btn-default btn-flat']
                                    ) ?>
                                </div>
                                <div class="pull-right">
                                    <?= Html::a(
                                        Yii::t('LoginForm', 'Sign Out'),
                                        ['/core/user/logout'],
                                        ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                    ) ?>
                                </div>
                            <?php } ?>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>

    </nav>
</header>
