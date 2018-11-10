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

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if ($app->hasModule('core') && ($module = $app->getModule('core')) instanceof Module) {
            /** @var Module $module */
            $module = $app->getModule('core');

            $this->loadParams($module);

            Yii::$app->user->loginUrl = ['/core/user/login'];

            // Check AuthManager
            $authManager = Yii::$app->get('authManager', false);
            if (!$authManager) {
                Yii::$app->set('authManager', [
                    'class' => DbManager::className(),
                    'cache' => 'cache',
                ]);
            } else if (!($authManager instanceof ManagerInterface)) {
                throw new InvalidConfigException('You have wrong authManager configuration');
            }

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

                Yii::$app->assetManager->bundles = [
                    'dmstr\web\AdminLteAsset' => [
                        'skin' => ArrayHelper::getValue(Yii::$app->params, 'app_LTEAdminSkin', 'skin-blue'),
                    ],
                ];
            }

        }
    }

    public function loadParams($module)
    {
        $data = (new Query())->from(Options::tableName())->select(['name', 'value'])->all();

        foreach ($data as $item) {
            $key = $item['name'];
            Yii::$app->params[$key] = $item['value'];
        }
    }

}