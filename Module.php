<?php

namespace pravda1979\core;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * core module definition class
 * @property  array $tableNames
 */
class Module extends \yii\base\Module
{
    public $tableNames = [];

    public function getUrlRules()
    {
        $listControllers = 'default|user|status|source-message|message|menu|user-action-log|backup|backup-attribute|options|auth-item';

        return [
            '<controller:(' . $listControllers . ')>' => 'core/<controller>/index',
            '<controller:(' . $listControllers . ')>/<action>' => 'core/<controller>/<action>',
//            '<module>/<controller:(' . $listControllers . ')>' => 'core/<controller>/index',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }

}
