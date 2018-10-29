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

Add to console/config/main.php

    'components' => [
        //...
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        //...
    ];

Add to backend/config/main.php

    'modules' => [
        //...
        'core' => [
            'class' => 'pravda1979\core\Module',
            'useLteAdminTheme' => true, // or false, if you want to use yii bootsrap theme
        ],
        //...
    ],
    'components' => [
        //...
        'user' => [
            'identityClass' => 'pravda1979\core\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        //...
    ],

        
Usage
-----

