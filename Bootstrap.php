<?php

namespace pravda1979\core;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if ($app->hasModule('core') && ($module = $app->getModule('core')) instanceof Module) {
            $module = $app->getModule('core');
            $app->getUrlManager()->addRules($module->urlRules, false);
        }
    }
}