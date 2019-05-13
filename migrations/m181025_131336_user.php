<?php

use pravda1979\core\components\migration\Migration;

class m181025_131336_user extends Migration
{
    public $table_name = 'user';
    public $route = 'core/user';
    public $parents = [
        'User: editor' => 'User: viewer',
        'User: admin' => 'User: editor',
        'admin' => ['editor', 'User: admin'],
        'editor' => ['viewer'],
    ];
    public $actions = [
        'User: viewer' => [
            'index',
            'view',
            'autocomplete',
        ],
        'User: editor' => [
            'create',
            'update',
            'send-new-password',
        ],
        'User: admin' => [
            'delete',
        ],
    ];
    public $modelNames = [
        'singular' => 'Пользователь',
        'plural' => 'Пользователи',
        'accusative' => 'пользователя', // Винительный падеж (кого, что)
        'genitive' => 'пользователя', // Родительный падеж (кого, чего)
    ];

    /**
     * @inheritdoc
     */
    public function getTranslates($translates = [])
    {
        $translates = [
            'ru-RU' => [
                'User' => [
                    'Index' => 'Список пользователей',
                    'Name' => 'Ф.И.О.',
                    'Username' => 'Логин',
                    'Email' => 'Email',
                    'User State' => 'Пользователь активен',
                    'Auth Key' => 'Ключ авторизации',
                    'Password Hash' => 'Хэш пароля',
                    'Password Reset Token' => 'Токен сброса пароля',
                    'User Rights' => 'Права доступа',
                    'Profile' => 'Профиль',
                    'Registered {date}' => 'Дата регистрации: {date}',
                    'Password' => 'Пароль',
                    'Password Repeat' => 'Подтверждение пароля',
                    'New Password' => 'Новый пароль',
                    'Repeat New Password' => 'Подтверждение нового пароля',
                    'Current Password' => 'Текущий пароль',
                    'Incorrect current password' => 'Неверно указан Ваш текущий пароль',
                    'Updating profile' => 'Редактирование профиля',
                    'Save profile' => 'Сохранить профиль',
                    'Are you sure you want to generate new password for this user?' => 'Вы уверены, что хотите сгенерировать новый пароль для этого пользователя?',
                    'Send New Password' => 'Выслать новый пароль',
                    'For user "{label}" has been generated new password successfully.' => 'Для пользователя "{label}" был сгенерирован новый пароль и успешно отправлен на email.',
                ],
                'actions' => [
                    'profile' => 'Профайл',
                ],
            ],
        ];
        return parent::getTranslates($translates);
    }

    public function safeUp()
    {
        $tableOptions = '';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8';
        }

        /** @var \pravda1979\core\Module $module */
        $module = Yii::$app->getModule('core');

        $this->createTable("{{%" . $module->tableNames[$this->table_name] . "}}", [
            'id' => $this->primaryKey(),

            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'name' => $this->string(255),
            'user_state' => $this->integer()->notNull(),

            'note' => $this->text(),
            'status_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey("{{%fk_" . "user_id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[user_id]]", "{{%" . $module->tableNames['user'] . "}}", "[[id]]");

        $this->batchInsert('{{%' . $module->tableNames['user'] . '}}', ['id', 'username', 'name', 'email', 'auth_key', 'password_hash', 'user_state', 'status_id', 'user_id', 'created_at', 'updated_at',], [
            [
                1,
                'admin',
                'Администратор',
                'admin@example.com',
                Yii::$app->security->generateRandomString(),
                Yii::$app->security->generatePasswordHash('admin'),
                1,
                1,
                1,
                new \yii\db\Expression('NOW()'),
                new \yii\db\Expression('NOW()'),
            ],
            [
                2,
                'editor',
                'Редактор',
                'editor@example.com',
                Yii::$app->security->generateRandomString(),
                Yii::$app->security->generatePasswordHash('123'),
                1,
                1,
                1,
                new \yii\db\Expression('NOW()'),
                new \yii\db\Expression('NOW()'),
            ],
            [
                3,
                'viewer',
                'Наблюдатель',
                'viewer@example.com',
                Yii::$app->security->generateRandomString(),
                Yii::$app->security->generatePasswordHash('123'),
                1,
                1,
                1,
                new \yii\db\Expression('NOW()'),
                new \yii\db\Expression('NOW()'),
            ],
        ]);

        $admin = $this->getRole('admin');
        $editor = $this->getRole('editor');
        $viewer = $this->getRole('viewer');

        $authManager = Yii::$app->authManager;

        if ($authManager->getAssignment('admin', 1) === null)
            $authManager->assign($admin, 1);
        if ($authManager->getAssignment('editor', 2) === null)
            $authManager->assign($editor, 2);
        if ($authManager->getAssignment('viewer', 3) === null)
            $authManager->assign($viewer, 3);

        $this->createTranslates();
        $this->createRbac();
    }

    public function safeDown()
    {
        /** @var \pravda1979\core\Module $module */
        $module = Yii::$app->getModule('core');
        $this->dropTable("{{%" . $module->tableNames[$this->table_name] . "}}");
        $this->deleteTranslates();
        $this->deleteRbac();
    }
}