<?php

namespace pravda1979\core;

use pravda1979\core\assets\CoreAsset;
use Yii;
use yii\base\BootstrapInterface;

/**
 * core module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'pravda1979\core\controllers';

    public $urlRules = [
        '<controller:(user|status|source-message|message)>' => 'core/<controller>/index',
        '<controller:(user|status|source-message|message)>/<action>' => 'core/<controller>/<action>',
    ];


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        CoreAsset::register(Yii::$app->getView());
        Yii::$app->user->loginUrl = ['/user/login'];
    }

    public function bootstrap($application)
    {
        Yii::warning(1);
//        $application->getUrlManager()->addRules($this->urlRules, false);
    }
}
