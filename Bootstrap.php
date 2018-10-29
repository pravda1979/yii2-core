<?php

namespace pravda1979\core;

use pravda1979\core\assets\LTEAdminAsset;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Theme;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if ($app->hasModule('core') && ($module = $app->getModule('core')) instanceof Module) {
            /** @var Module $module */
            $module = $app->getModule('core');

            // Register rules for route
            $app->getUrlManager()->addRules($module->urlRules, false);

            //Set LTE Admin theme
            if ($module->useLteAdminTheme) {
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
                        'skin' => $module->skin,
                    ],
                ];
            }

        }
    }
}