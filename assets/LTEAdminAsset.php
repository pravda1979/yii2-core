<?php

namespace pravda1979\core\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class LTEAdminAsset extends AssetBundle
{
    public $sourcePath = '@pravda1979/core/assets';
    public $css = [
        'css/core_lteadmin.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
