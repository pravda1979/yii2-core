<?php

namespace pravda1979\core;

use pravda1979\core\assets\CoreAsset;
use pravda1979\core\assets\LTEAdminAsset;
use pravda1979\core\models\Options;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\base\Theme;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\rbac\ManagerInterface;
use yii\console\Application as ConsoleApplication;


class Bootstrap implements BootstrapInterface
{
    private $_tableNames = [
        'auth_item' => 'core_auth_item',
        'auth_item_child' => 'core_auth_item_child',
        'auth_assignment' => 'core_auth_assignment',
        'auth_rule' => 'core_auth_rule',
        'user' => 'core_user',
        'status' => 'core_status',
        'message' => 'core_message',
        'source_message' => 'core_source_message',
        'backup' => 'core_backup',
        'backup_attribute' => 'core_backup_attribute',
        'menu' => 'core_menu',
        'options' => 'core_options',
        'user_action_log' => 'core_user_action_log',
        'session' => 'core_session',
    ];

    public function getTableNames()
    {
        return $this->_tableNames;
    }

    public function setTableNames(array $tableNames)
    {
        return ArrayHelper::merge($this->_tableNames, $tableNames);
    }

    public function bootstrap($app)
    {
        if ($app->hasModule('core') && ($module = $app->getModule('core')) instanceof Module) {
            /** @var Module $module */
            $module = $app->getModule('core');

            $module->tableNames = ArrayHelper::merge($this->_tableNames, $module->tableNames);

            // Check AuthManager
            $authManager = Yii::$app->get('authManager', false);
            if (!$authManager) {
                Yii::$app->set('authManager', [
                    'class' => DbManager::className(),
                    'cache' => 'cache',
                    'ruleTable' => $module->tableNames['auth_rule'],
                    'itemTable' => $module->tableNames['auth_item'],
                    'itemChildTable' => $module->tableNames['auth_item_child'],
                    'assignmentTable' => $module->tableNames['auth_assignment'],
                ]);
            } else if ($authManager instanceof ManagerInterface) {
                Yii::$app->set('authManager', [
                    'ruleTable' => $module->tableNames['auth_rule'],
                    'itemTable' => $module->tableNames['auth_item'],
                    'itemChildTable' => $module->tableNames['auth_item_child'],
                    'assignmentTable' => $module->tableNames['auth_assignment'],
                ]);
            } else if (!($authManager instanceof ManagerInterface)) {
                throw new InvalidConfigException('You have wrong authManager configuration');
            }

            if (Yii::$app instanceof ConsoleApplication) {

            } else {
                $this->loadParams();

                $app->get('i18n')->translations['app'] = [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable' => '{{%' . $module->tableNames['source_message'] . '}}',
                    'messageTable' => '{{%' . $module->tableNames['message'] . '}}',
                    'forceTranslation' => true,
                    'on missingTranslation' => ['pravda1979\core\components\core\TranslationEventHandler', 'addMissingTranslation'],
                ];

                if (!isset($app->get('i18n')->translations['*'])) {
                    $app->get('i18n')->translations['*'] = [
                        'class' => 'yii\i18n\DbMessageSource',
                        'sourceMessageTable' => '{{%' . $module->tableNames['source_message'] . '}}',
                        'messageTable' => '{{%' . $module->tableNames['message'] . '}}',
                        'forceTranslation' => true,
                        'on missingTranslation' => ['pravda1979\core\components\core\TranslationEventHandler', 'addMissingTranslation'],
                    ];
                }

                Yii::$container->set('yii\web\User', [
                    'enableAutoLogin' => true,
                    'loginUrl' => ['/core/user/login'],
                    'identityClass' => 'pravda1979\core\models\User',
                ]);

                Yii::$app->set('session', [
                    'class' => 'yii\web\DbSession',
                    'useTransparentSessionID' => true,
                    'sessionTable' => '{{%' . $module->tableNames['session'] . '}}',
                    'writeCallback' => function ($session) {
                        return ['user_id' => Yii::$app->user->id];
                    },
                    // 'db' => 'mydb',  // ID компонента для взаимодействия с БД. По умолчанию 'db'.
                ]);

                // Register assets
                CoreAsset::register(Yii::$app->getView());

                // Register rules for route
                $app->getUrlManager()->addRules($module->urlRules, false);

                //Set LTE Admin theme
                if (Yii::$app->params['app_Theme'] == 'lteadmin') {
                    LTEAdminAsset::register(Yii::$app->getView());
//                Yii::$app->layoutPath = Yii::getAlias('@pravda1979/core/views/layouts');
//                Yii::$app->layout = 'main';

                    Yii::$app->view->theme = new Theme([
//                    'basePath' => '@webroot/themes/' . $theme,
//                    'baseUrl' => '@web/themes/' . $theme,
                        'pathMap' => [
                            '@app/views' => '@pravda1979/core/views',
//                        '@common/modules' => '@app/themes/' . $theme . '/modules',
//                        '@common/widgets' => '@app/themes/' . $theme . '/widgets',
//                        'baseUrl' => '@web/../themes/' . $theme,
                        ]]);

                    Yii::$app->assetManager->bundles['dmstr\web\AdminLteAsset'] = [
                        'skin' => ArrayHelper::getValue(Yii::$app->params, 'app_LTEAdminSkin', 'skin-blue'),
                    ];
                }
            }
        }
    }

    public function loadParams()
    {
        $data = (new Query())->from(Options::tableName())->select(['name', 'value'])->all();
        foreach ($data as $item) {
            $key = $item['name'];
            Yii::$app->params[$key] = $item['value'];
        }
    }

}