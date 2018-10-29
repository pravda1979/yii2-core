<?php

namespace pravda1979\core;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Yii::warning(2);

//        if ($application->hasModule('user') && ($module = $application->getModule('user')) instanceof Module) {
//        if (($module = $application->getModule('core')) instanceof Module) {
        $module = $app->getModule('core');
        Yii::warning($module, '$module');
        $app->getUrlManager()->addRules($module->urlRules, false);
//        }
    }
}