<?php

namespace pravda1979\core\components\core;

use pravda1979\core\components\behaviors\UserActionLogBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class BackendController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $result = [
            'as access' => [
                'class' => 'pravda1979\core\components\core\AccessControl',
                'allowActions' => [
                    '/core/user/login',
                    '/core/user/logout',
                    'site/error',
                ]
            ]
        ];

        if (Yii::$app->params['app_UseUserActionLog']) {
            $result['userActionLog'] = [
                'class' => UserActionLogBehavior::className(),
                // Disabling log for some actions
                'excludeActions' => ['autocomplete'],
            ];
        }

        return ArrayHelper::merge(parent::behaviors(), $result);
    }


    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        // Remember current url for goBack() function after create/update/delete record
        if (!$action instanceof Action && !in_array($action->getUniqueId(), ['core/default/delete-cache', 'core/options/index'])) {
            Url::remember();
        }

        return parent::afterAction($action, $result);
    }
}
