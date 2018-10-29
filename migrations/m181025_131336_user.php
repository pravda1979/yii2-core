<?php

use pravda1979\core\components\migration\Migration;

class m181025_131336_user extends Migration
{
    public $table_name = 'user';
    public $route = 'user';
    public $parents = [
        '$route: editor' => '$route: viewer',
        '$route: admin' => '$route: editor',
        'viewer' => '$route: viewer',
        'editor' => '$route: editor',
        'admin' => '$route: admin',
    ];
    public $actions = [
        '$route: viewer' => [
            'index',
            'view',
            'autocomplete',
        ],
        '$route: editor' => [
            'create',
            'update',
        ],
        '$route: admin' => [
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

            $this->insert('{{%user}}', [
                'id' => 1,
                'username' => 'admin',
                'name' => 'Администратор',
                'email' => 'admin@example.com',
                'auth_key' => Yii::$app->security->generateRandomString(),
                'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
                'user_state' => 1,
                'status_id' => 1,
                'user_id' => 1,
                'created_at' => new \yii\db\Expression('NOW()'),
                'updated_at' => new \yii\db\Expression('NOW()'),
            ]);
        }

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