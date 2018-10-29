Core Module
===========
Include:
- Users
- Messages Translates in database
- Statuses of records
- LTE Admin template
- Gii template
- Migration template
- Actions (index, create, update, view, delete)
- StringValidator
- EntryMenu widget
- checkAccess

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist pravda1979/yii2-core "*"
```

or add

```
"pravda1979/yii2-core": "*"
```

to the require section of your `composer.json` file.

Add to project config:
----------------------

For Advanced Template
-------------------

Add to `console/config/main.php`

    'components' => [
        //...
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    'controllerMap' => [
        //...
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'templateFile' => '@pravda1979/core/components/migration/template.php',
            'migrationPath' => [
                '@pravda1979/core/migrations',
                '@console/migrations',
                '@yii/rbac/migrations',
            ],
        ],
    ],    

Add to `backend/config/main.php`

    'modules' => [
        //...
        'core' => [
            'class' => 'pravda1979\core\Module',
            'useLteAdminTheme' => true, // or false, if you want to use yii bootsrap theme
            'skin' => 'skin-blue', // default
        ],
    ],
    'components' => [
        //...
        'user' => [
            'identityClass' => 'pravda1979\core\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
    ],

Add to `common/config/main.php`

    'language' => 'ru-RU',
    'sourceLanguage' => 'en-US',
    //...
    'components' => [
        //...
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable' => '{{%source_message}}',
                    'messageTable' => '{{%message}}',
                    // 'enableCaching' => true,
                    // 'cachingDuration' => 60*60*24,
                    'forceTranslation' => true,
                    'on missingTranslation' => ['pravda1979\core\components\core\TranslationEventHandler', 'addMissingTranslation'],
                ],
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable' => '{{%source_message}}',
                    'messageTable' => '{{%message}}',
                    // 'enableCaching' => true,
                    // 'cachingDuration' => 60*60*24,
                    'forceTranslation' => true,
                    'on missingTranslation' => ['pravda1979\core\components\core\TranslationEventHandler', 'addMissingTranslation'],
                ],
            ],
        ],
    ],
        

For Basic Template
-------------------

Add to `config/web.php`

    'language' => 'ru-RU',
    'sourceLanguage' => 'en-US',
    //...
    'components' => [
        //...
        'user' => [
            'identityClass' => 'pravda1979\core\models\User',
            'enableAutoLogin' => true,
        ],
        //...
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable' => '{{%source_message}}',
                    'messageTable' => '{{%message}}',
                    // 'enableCaching' => true,
                    // 'cachingDuration' => 60*60*24,
                    'forceTranslation' => true,
                    'on missingTranslation' => ['pravda1979\core\components\core\TranslationEventHandler', 'addMissingTranslation'],
                ],
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable' => '{{%source_message}}',
                    'messageTable' => '{{%message}}',
                    // 'enableCaching' => true,
                    // 'cachingDuration' => 60*60*24,
                    'forceTranslation' => true,
                    'on missingTranslation' => ['pravda1979\core\components\core\TranslationEventHandler', 'addMissingTranslation'],
                ],
            ],
        ],
    ],
    'modules' => [
        //...
        'core' => [
            'class' => 'pravda1979\core\Module',
            'useLteAdminTheme' => true, // or false, if you want to use yii bootsrap theme
            'skin' => 'skin-blue', // default
        ],
    ],
    
Add to `config/console.php`

    'components' => [
        //...
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    //...
    'controllerMap' => [
        //...
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'templateFile' => '@pravda1979/core/components/migration/template.php',
            'migrationPath' => [
                '@pravda1979/core/migrations',
                '@app/migrations',
                '@yii/rbac/migrations',
            ],
        ],
    ],    



For change view, add in config
-----------------------------

    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@pravda1979/core/views' => '@app/views',
                ],
            ],
        ],
    ],
        

For add Gii template, add in config
-----------------------------

    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
        'generators' => [
            'crud' => [
                'class' => 'pravda1979\core\gii\crud\Generator',
                'templates' => [
                    'adminlte' => '@pravda1979/core/gii/crud/adminlte',
                ]
            ],
            'model' => [
                'class' => 'pravda1979\core\gii\model\Generator',
            ],
        ],
    ];
Base classes for Gii template:

```
- pravda1979\core\components\core\ActiveRecord
- pravda1979\core\components\core\ActiveQuery
- pravda1979\core\components\core\DataController
```

                            
Usage
-----

