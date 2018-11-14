<?php

use pravda1979\core\components\migration\Migration;

class m181114_012957_session extends Migration
{
    public $table_name = 'session';
    public $route = 'core/session';
    public $parents = [
        'Session: editor' => 'Session: viewer',
        'Session: admin' => 'Session: editor',
        '::viewer' => 'Session: viewer',
        '::editor' => 'Session: editor',
        '::admin' => 'Session: admin',
    ];
    public $actions = [
        'Session: viewer' => [
        ],
        'Session: editor' => [
        ],
        'Session: admin' => [
            'index',
            'view',
            'autocomplete',
            'create',
            'update',
            'delete',
        ],
    ];
    public $modelNames = [
        'singular' => 'Сессия',
        'plural' => 'Сессии',
        'accusative' => 'сессию', // Винительный падеж (кого, что)
        'genitive' => 'сесии', // Родительный падеж (кого, чего)
    ];

    /**
     * @inheritdoc
     */
    public function getTranslates($translates = [])
    {
        $translates = [
            'ru-RU' => [
                'Session' => [
                    'Index' => 'Список сессий',
                    'Expire' => 'Истекает',
                    'data' => 'Данные',
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
            'id' => $this->char(40)->notNull(),
            'expire' => $this->integer(),
            'data' => 'LONGBLOB',
            'user_id' => $this->integer(),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey("{{%fk_" . "user_id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[user_id]]", "{{%" . $module->tableNames['user'] . "}}", "[[id]]");

        $this->createIndex("{{%" . $module->tableNames[$this->table_name] . "_" . "user_id" . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", ["[[user_id]]"]);
        $this->createIndex("{{%" . $module->tableNames[$this->table_name] . "_" . "expire" . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", ["[[expire]]"]);

        $this->addPrimaryKey("{{%pk_" . "id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", 'id');


        // $VISIBLE_CHECK_ACCESS = 1; $VISIBLE_GUEST = 10; $VISIBLE_AUTHORIZED = 20; $VISIBLE_ADMIN = 30;
        // $VISIBLE_ALWAYS = 40; $VISIBLE_NEVER = 50; $VISIBLE_HAS_CHILDREN = 60;
        // data=>1; dirs=>2; admin=>3; instruments=>8;
//        $this->batchInsert('{{%' . $module->tableNames['menu'] . '}}', ['use_url_helper', 'visible', 'position', 'menu_id', 'label', 'icon', 'url', 'parent_id', 'level', 'status_id', 'user_id', 'updated_at'], [
//            [1, 1, 1500, 'menu.main', 'Sessions', null, '/core/session/index', 3, 1, 1, 1, new \yii\db\Expression('NOW()')],
//        ]);

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
//        $this->delete('{{%' . $module->tableNames['menu'] . '}}', ['menu_id' => 'menu.main', 'label' => 'Sessions']);
    }
}