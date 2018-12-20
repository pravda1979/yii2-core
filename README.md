Core Module
===========
Include:
- Users
- RBAC
- Messages Translates in database
- Statuses of records
- AdminLTE theme
- Gii template
- Migration template
- Actions (index, create, update, view, delete, autocomplete)
- StringValidator
- EntryMenu widget
- Menu
- Logging users actions
- Backup/restore all changes
- App options, saved in database

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist pravda1979/yii2-core "dev-master"
```

or add

```
"pravda1979/yii2-core": "dev-master"
```

change minimum stability to:
```
"minimum-stability": "dev",
"prefer-stable": true,
```

to the require section of your `composer.json` file.

Add to project config:
----------------------

For Advanced Template
-------------------

Add to `console/config/main.php`

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
        //...
    ],    

Add to `backend/config/main.php`

    'components' => [
        //...
        'user' => [
            // 'identityClass' => 'pravda1979\core\models\User', //this value setted by default from Module, remove it in config or change
            // 'enableAutoLogin' => true, //this value setted by default from Module, remove it in config or change
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        //...
    ],
    //...

Add to `common/config/main.php`

    'language' => 'ru-RU',
    'sourceLanguage' => 'en-US',
    //...
    'modules' => [
    //...
        'core' => [
            'class' => 'pravda1979\core\Module',
            // Change table names if you need, by default = "core_(table_name)"
            // 'tableNames' => [
            //     'auth_item' => 'core_auth_item',
            //     'auth_item_child' => 'core_auth_item_child',
            //     'auth_assignment' => 'core_auth_assignment',
            //     'auth_rule' => 'core_auth_rule',
            //     'user' => 'core_user',
            //     'status' => 'core_status',
            //     'message' => 'core_message',
            //     'source_message' => 'core_source_message',
            //     'backup' => 'core_backup',
            //     'backup_attribute' => 'core_backup_attribute',
            //     'menu' => 'core_menu',
            //     'options' => 'core_options',
            //     'user_action_log' => 'core_user_action_log',
            //     'session' => 'core_session',
            // ],
        ],
    //...
    ],
    //...
        

For Basic Template
-------------------

Add to `config/web.php`

    'language' => 'ru-RU',
    'sourceLanguage' => 'en-US',
    //...
    'modules' => [
    //...
        'core' => [
            'class' => 'pravda1979\core\Module',
            // Change table names if you need, by default = "core_(table_name)"
            // 'tableNames' => [
            //     'auth_item' => 'core_auth_item',
            //     'auth_item_child' => 'core_auth_item_child',
            //     'auth_assignment' => 'core_auth_assignment',
            //     'auth_rule' => 'core_auth_rule',
            //     'user' => 'core_user',
            //     'status' => 'core_status',
            //     'message' => 'core_message',
            //     'source_message' => 'core_source_message',
            //     'backup' => 'core_backup',
            //     'backup_attribute' => 'core_backup_attribute',
            //     'menu' => 'core_menu',
            //     'options' => 'core_options',
            //     'user_action_log' => 'core_user_action_log',
            //     'session' => 'core_session',
            // ],
        ],
    //...
    ],
    //...
    
Add to `config/console.php`

    //...
    'modules' => [
        'core' => [
            'class' => 'pravda1979\core\Module',
        ],
    ],
    //...
    'components' => [
    //...
        'user' => [
            'identityClass' => 'pravda1979\core\models\User',
            //...
        ],
    //...
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
        //...
    ],    
    //...



For changing Gii template, add in `config/web.php`
--------------------------------------------------

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

    
For changing existing view, add in `config/web.php`
----------------------------------------------------

    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@pravda1979/core/views' => '@app/views',
                ],
            ],
        ],
    ],
        

Base classes for Gii template:

```
- pravda1979\core\components\core\ActiveRecord
- pravda1979\core\components\core\ActiveQuery
- pravda1979\core\components\core\DataController
```

                            
Usage
-----

Getting main menu items array:
```
$menuItems = \pravda1979\core\models\Menu::getMenu('menu.main');
```

Login url:
```
'/core/user/login'
```

Logout url:
```
'/core/user/logout'
```

By default, added 3 users:
```
Login: admin
Password: admin
Role: admin
```
```
Login: editor
Password: 123
Role: editor
```
```
Login: viewer
Password: 123
Role: viewer
```
