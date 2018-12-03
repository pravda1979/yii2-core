<?php

use pravda1979\core\components\migration\Migration;

class m181030_164117_menu extends Migration
{
    public $table_name = 'menu';
    public $route = 'core/menu';
    public $parents = [
        'Menu: editor' => 'Menu: viewer',
        'Menu: admin' => 'Menu: editor',
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
                    'Label' => 'Наименование элемента',
                    'Icon' => 'Иконка',
                    'Url' => 'Ссылка',
                    'Link Options' => 'Конфиг ссылки',
                    'Position' => '№ п/п внутри меню',
                    'Level' => 'Уровень вложенности',
                    'Parent ID' => 'Родительский элемент',
                    'Use Url Helper' => 'Использовать Url Helper',
                    'Visible' => 'Видимость',
                    'VISIBLE_CHECK_ACCESS' => 'Проверка прав доступа по ссылке',
                    'VISIBLE_GUEST' => 'Только для гостей',
                    'VISIBLE_AUTHORIZED' => 'Только для авторизированных пользователей',
                    'VISIBLE_ADMIN' => 'Только для администратора',
                    'VISIBLE_ALWAYS' => 'Всегда для всех',
                    'VISIBLE_NEVER' => 'Никогда никому',
                    'VISIBLE_HAS_CHILDREN' => 'Если имеются видимые дочерние элементы',
                ],
                'menu.main' => [
                    'admin' => 'Администрирование',
                    'dirs' => 'Справочники',
                    'data' => 'Данные',
                    'instruments' => 'Инструменты',
                    'Delete cache' => 'Очистить кеш',
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

            'menu_id' => $this->string(255),
            'label' => $this->string(255),
            'icon' => $this->string(255),
            'url' => $this->string(255),
            'use_url_helper' => $this->boolean(),
            'visible' => $this->integer(),
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

        $this->addForeignKey("{{%fk_" . "user_id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[user_id]]", "{{%" . $module->tableNames['user'] . "}}", "[[id]]");
        $this->addForeignKey("{{%fk_" . "status_id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[status_id]]", "{{%" . $module->tableNames['status'] . "}}", "[[id]]");
        $this->addForeignKey("{{%fk_" . "parent_id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[status_id]]", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[id]]");

        // $VISIBLE_CHECK_ACCESS = 1; $VISIBLE_GUEST = 10; $VISIBLE_AUTHORIZED = 20; $VISIBLE_ADMIN = 30;
        // $VISIBLE_ALWAYS = 40; $VISIBLE_NEVER = 50; $VISIBLE_HAS_CHILDREN = 60;
        // data=>1; dirs=>2; admin=>3; instruments=>8;
        $this->batchInsert('{{%' . $module->tableNames[$this->table_name] . '}}', ['id', 'use_url_helper', 'visible', 'position', 'menu_id', 'label', 'icon', 'url', 'parent_id', 'level', 'status_id', 'user_id', 'updated_at'], [
            [1, 1, 60, 100, 'menu.main', 'data', 'files-o', null, null, 0, 1, 1, new \yii\db\Expression('NOW()')],
            [2, 1, 60, 200, 'menu.main', 'dirs', 'book', null, null, 0, 1, 1, new \yii\db\Expression('NOW()')],
            [3, 1, 60, 300, 'menu.main', 'admin', 'key', null, null, 0, 1, 1, new \yii\db\Expression('NOW()')],
            [4, 1, 1, 400, 'menu.main', 'Users', 'users', '/core/user/index', 3, 1, 1, 1, new \yii\db\Expression('NOW()')],
            [5, 1, 1, 500, 'menu.main', 'Statuses', null, '/core/status/index', 3, 1, 1, 1, new \yii\db\Expression('NOW()')],
            [6, 1, 1, 600, 'menu.main', 'Messages', null, '/core/message/index', 3, 1, 1, 1, new \yii\db\Expression('NOW()')],
            [7, 1, 1, 700, 'menu.main', 'Menus', null, '/core/menu/index', 3, 1, 1, 1, new \yii\db\Expression('NOW()')],
            [8, 1, 60, 800, 'menu.main', 'instruments', 'wrench', null, 3, 1, 1, 1, new \yii\db\Expression('NOW()')],
            [9, 1, 30, 900, 'menu.main', 'Gii', null, '/gii', 8, 2, 1, 1, new \yii\db\Expression('NOW()')],
            [10, 0, 30, 1000, 'menu.main', 'phpMyAdmin', null, '/tools/phpMyAdmin', 8, 2, 1, 1, new \yii\db\Expression('NOW()')],
            [11, 1, 30, 1100, 'menu.main', 'Delete cache', 'trash', '/core/default/delete-cache', 3, 2, 1, 1, new \yii\db\Expression('NOW()')],
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
    }
}