<?php

use pravda1979\core\components\migration\Migration;

class m181030_164117_menu extends Migration
{
    public $table_name = 'menu';
    public $route = 'core/menu';
    public $parents = [
        'Menu: editor' => 'Menu: viewer',
        'Menu: admin' => 'Menu: editor',
        'viewer' => 'Menu: viewer',
        'editor' => 'Menu: editor',
        'admin' => 'Menu: admin',
    ];
    public $actions = [
        'Menu: viewer' => [
            'index',
            'view',
            'autocomplete',
        ],
        'Menu: editor' => [
            'create',
            'update',
        ],
        'Menu: admin' => [
            'delete',
        ],
    ];
    public $modelNames = [
        'singular' => 'Меню',
        'plural' => 'Меню',
        'accusative' => 'меню', // Винительный падеж (кого, что)
        'genitive' => 'меню', // Родительный падеж (кого, чего)
    ];

    /**
     * @inheritdoc
     */
    public function getTranslates($translates = [])
    {
        $translates = [
            'ru-RU' => [
                'Menu' => [
                    'Index' => 'Список меню',
                    'Menu ID' => 'Наименование меню',
                    'Label' => 'Наименование',
                    'Icon' => 'Иконка',
                    'Url' => 'Ссылка',
                    'Link Options' => 'Конфиг ссылки',
                    'Position' => '№ п/п внутри меню',
                    'Level' => 'Уровень вложеннности',
                    'Parent ID' => 'Родительский элемент',
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

                'menu_id' => $this->string(255),
                'label' => $this->string(255),
                'icon' => $this->string(255),
                'url' => $this->string(255),
                'linkOptions' => $this->text(),
                'position' => $this->integer(),
                'level' => $this->integer(),
                'parent_id' => $this->integer(),

                'note' => $this->text(),
                'status_id' => $this->integer()->notNull(),
                'user_id' => $this->integer()->notNull(),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->addForeignKey("{{%fk_" . "user_id" . "_$this->table_name}}", "{{%$this->table_name}}", "[[user_id]]", "{{%user}}", "[[id]]");
            $this->addForeignKey("{{%fk_" . "status_id" . "_$this->table_name}}", "{{%$this->table_name}}", "[[status_id]]", "{{%status}}", "[[id]]");

//          $this->createIndex("{{%$this->table_name"."_"."fieldName"."}}", "{{%$this->table_name}}", "[[fieldName]]");
            $this->batchInsert('{{%' . $this->table_name . '}}', ['id', 'menu_id', 'label', 'icon', 'url', 'parent_id', 'status_id', 'user_id'], [
                [1, 'menu.main', 'data', 'files-o', null, null, 1, 1],
                [2, 'menu.main', 'dirs', 'book', null, null, 1, 1],
                [3, 'menu.main', 'admin', 'key', null, null, 1, 1],
                [4, 'menu.main', 'User', 'users', '/core/user/index', 3, 1, 1],
                [5, 'menu.main', 'Status', null, '/core/status/index', 3, 1, 1],
                [6, 'menu.main', 'Message', null, '/core/message/index', 3, 1, 1],
                [7, 'menu.main', 'Menu', null, '/core/menu/index', 3, 1, 1],
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