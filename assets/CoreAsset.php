<?php

namespace pravda1979\core\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class CoreAsset extends AssetBundle
{
    public $sourcePath = '@pravda1979/core/assets';
    public $css = [
        'css/core.css',
    ];
    public $js = [
        'js/user.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
