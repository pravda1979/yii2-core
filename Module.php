<?php

namespace pravda1979\core;

use Yii;
use pravda1979\core\assets\CoreAsset;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;
use yii\rbac\ManagerInterface;


/**
 * core module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'pravda1979\core\controllers';

    public $useLteAdminTheme = true;
    public $skin = 'skin-blue';

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

        Yii::$app->user->loginUrl = ['/user/login'];

        // Check AuthManager
        $authManager = Yii::$app->get('authManager', false);
        if (!$authManager) {
            Yii::$app->set('authManager', [
                'class' => DbManager::className(),
            ]);
        } else if (!($authManager instanceof ManagerInterface)) {
            throw new InvalidConfigException('You have wrong authManager configuration');
        }

        // Register assets
        CoreAsset::register(Yii::$app->getView());
    }
}
