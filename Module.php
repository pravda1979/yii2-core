<?php

namespace pravda1979\core;

use Yii;

/**
 * core module definition class
 */
class Module extends \yii\base\Module
{
    public $skin = 'skin-blue';

    public function getUrlRules()
    {
        $listControllers = 'default|user|status|source-message|message|menu|user-action-log|backup|backup-attribute|options';

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
