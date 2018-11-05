<?php

use pravda1979\core\components\migration\Migration;

class m181025_131336_user extends Migration
{
    public $table_name = 'user';
    public $route = 'core/user';
    public $parents = [
        'User: editor' => 'User: viewer',
        'User: admin' => 'User: editor',
        'admin' => 'User: admin',
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

        if ($this->db->schema->getTableSchema("{{%$this->table_name}}", true) === null) {
            $this->createTable("{{%$this->table_name}}", [
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

            $this->addForeignKey("{{%fk_" . "user_id" . "_$this->table_name}}", "{{%$this->table_name}}", "[[user_id]]", "{{%user}}", "[[id]]");

            $this->batchInsert('{{%user}}', ['id', 'username', 'name', 'email', 'auth_key', 'password_hash', 'user_state', 'status_id', 'user_id', 'created_at', 'updated_at',], [
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
                    Yii::$app->security->generatePasswordHash('editor'),
                    1,
                    1,
                    1,
                    new \yii\db\Expression('NOW()'),
                    new \yii\db\Expression('NOW()'),
                ],
                [
                    3,
                    'viewer',
                    'Зритель',
                    'viewer@example.com',
                    Yii::$app->security->generateRandomString(),
                    Yii::$app->security->generatePasswordHash('viewer'),
                    1,
                    1,
                    1,
                    new \yii\db\Expression('NOW()'),
                    new \yii\db\Expression('NOW()'),
                ],
            ]);
        }

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
        if ($this->db->schema->getTableSchema("{{%$this->table_name}}", true) != null)
            $this->dropTable("{{%$this->table_name}}");
        $this->deleteTranslates();
        $this->deleteRbac();
    }
}