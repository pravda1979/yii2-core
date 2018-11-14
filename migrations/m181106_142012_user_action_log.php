<?php

use pravda1979\core\components\migration\Migration;

class m181106_142012_user_action_log extends Migration
{
    public $table_name = 'user_action_log';
    public $route = 'core/user-action-log';
    public $parents = [
        'UserActionLog: editor' => 'UserActionLog: viewer',
        'UserActionLog: admin' => 'UserActionLog: editor',
        '::viewer' => 'UserActionLog: viewer',
        '::editor' => 'UserActionLog: editor',
        '::admin' => 'UserActionLog: admin',
    ];
    public $actions = [
        'UserActionLog: viewer' => [
            'index',
            'view',
            'autocomplete',
        ],
        'UserActionLog: editor' => [
            'create',
            'update',
        ],
        'UserActionLog: admin' => [
            'delete',
        ],
    ];
    public $modelNames = [
        'singular' => 'Действие пользователя',
        'plural' => 'Журнал действий пользователей',
        'accusative' => 'запись', // Винительный падеж (кого, что)
        'genitive' => 'запись', // Родительный падеж (кого, чего)
    ];

    /**
     * @inheritdoc
     */
    public function getTranslates($translates = [])
    {
        $translates = [
            'ru-RU' => [
                'UserActionLog' => [
                    'Search' => 'Поиск в журнале действий',
                    'Controller' => 'Контроллер',
                    'Action' => 'Действие',
                    'Route' => 'Маршрут',
                    'Method' => 'Метод',
                    'User IP' => 'IP-адрес',
                    'Url' => 'URL',
                    'Created At' => 'Дата и время',
                ],
                'actions' => [
                    'actions' => 'Действия',
                    'index' => 'Список',
                    'create' => 'Создание',
                    'update' => 'Редактирование',
                    'delete' => 'Удаление',
                    'view' => 'Просмотр',
                    'login' => 'Авторизация',
                    'logout' => 'Выход',
                    'delete-cache' => 'Очистка кеша',
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

            'controller' => $this->string(255),
            'action' => $this->string(255),
            'route' => $this->string(255),
            'method' => $this->string(255),
            'user_ip' => $this->string(255),
            'url' => $this->text(),

            'note' => $this->text(),
            'status_id' => $this->integer()->notNull(),
            'user_id' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey("{{%fk_" . "user_id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[user_id]]", "{{%" . $module->tableNames['user'] . "}}", "[[id]]");
        $this->addForeignKey("{{%fk_" . "status_id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[status_id]]", "{{%" . $module->tableNames['status'] . "}}", "[[id]]");

        $this->createIndex("{{%ix_" . "controller" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[controller]]");
        $this->createIndex("{{%ix_" . "action" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[action]]");


        // $VISIBLE_CHECK_ACCESS = 1; $VISIBLE_GUEST = 10; $VISIBLE_AUTHORIZED = 20; $VISIBLE_ADMIN = 30;
        // $VISIBLE_ALWAYS = 40; $VISIBLE_NEVER = 50; $VISIBLE_HAS_CHILDREN = 60;
        // data=>1; dirs=>2; admin=>3; instruments=>8;
        $this->batchInsert('{{%' . $module->tableNames['menu'] . '}}', ['use_url_helper', 'visible', 'position', 'menu_id', 'label', 'icon', 'url', 'parent_id', 'level', 'status_id', 'user_id', 'updated_at'], [
            [1, 1, 1050, 'menu.main', 'User Action Logs', null, '/core/user-action-log/index', 3, 1, 1, 1, new \yii\db\Expression('NOW()')],
        ]);

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
        $this->delete('{{%' . $module->tableNames['menu'] . '}}', ['menu_id' => 'menu.main', 'label' => 'User Action Logs']);
    }
}