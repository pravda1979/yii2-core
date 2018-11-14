<?php

use pravda1979\core\components\migration\Migration;

class m181107_205629_backup extends Migration
{
    public $table_name = 'backup';
    public $route = 'core/backup';
    public $parents = [
        'Backup: editor' => 'Backup: viewer',
        'Backup: admin' => 'Backup: editor',
//        'viewer' => 'Backup: viewer',
//        'editor' => 'Backup: editor',
        'admin' => 'Backup: admin',
    ];
    public $actions = [
        'Backup: viewer' => [
            'index',
            'view',
            'autocomplete',
            'history',
        ],
        'Backup: editor' => [
//            'create',
//            'update',
        ],
        'Backup: admin' => [
//            'delete',
        ],
    ];
    public $modelNames = [
        'singular' => 'Бэкап',
        'plural' => 'Журнал изменений',
        'accusative' => 'бэкап', // Винительный падеж (кого, что)
        'genitive' => 'бэкапа', // Родительный падеж (кого, чего)
    ];

    /**
     * @inheritdoc
     */
    public function getTranslates($translates = [])
    {
        $translates = [
            'ru-RU' => [
                'Backup' => [
                    'Index' => 'Журнал изменений',
                    'Action' => 'Действие',
                    'Record Class' => 'Класс записи (полный)',
                    'Record Short Class' => 'Класс записи',
                    'Record ID' => 'ID исходной записи',
                    'Record Name' => 'Исходная запись',
                    'Record deleted' => 'Запись удалена',
                    'Go to parent record' => 'Перейти к исходной записи',
                    'Changes' => 'Изменения',
                    'The backup "{caption}" was successfully reverted.' => 'Изменение "{caption}" успешно отменено.',
                    'Are you sure you want to undo this changes?' => 'Вы уверены, что хотите отменить эти изменения?',
                    'Undo changes' => 'Отменить изменения',
                    'History' => 'Просмотреть историю изменений',
                ],
                'actions' => [
                    'undo' => 'Отмена изменений',
                    'history' => 'История изменений',
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

            'action' => $this->string(255),
            'record_short_class' => $this->string(255),
            'record_class' => $this->string(255),
            'record_id' => $this->integer(),
            'record_name' => $this->string(255),

            'note' => $this->text(),
            'status_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey("{{%fk_" . "user_id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[user_id]]", "{{%" . $module->tableNames['user'] . "}}", "[[id]]");
        $this->addForeignKey("{{%fk_" . "status_id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[status_id]]", "{{%" . $module->tableNames['status'] . "}}", "[[id]]");

        $this->createIndex("{{%" . $module->tableNames[$this->table_name] . "_" . "record_id" . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[record_id]]");
        $this->createIndex("{{%" . $module->tableNames[$this->table_name] . "_" . "record_class" . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[record_class]]");
        $this->createIndex("{{%" . $module->tableNames[$this->table_name] . "_" . "record_short_class" . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[record_short_class]]");

        // $VISIBLE_CHECK_ACCESS = 1; $VISIBLE_GUEST = 10; $VISIBLE_AUTHORIZED = 20; $VISIBLE_ADMIN = 30;
        // $VISIBLE_ALWAYS = 40; $VISIBLE_NEVER = 50; $VISIBLE_HAS_CHILDREN = 60;
        // data=>1; dirs=>2; admin=>3; instruments=>8;
        $this->batchInsert('{{%' . $module->tableNames['menu'] . '}}', ['use_url_helper', 'visible', 'position', 'menu_id', 'label', 'icon', 'url', 'parent_id', 'level', 'status_id', 'user_id', 'updated_at'], [
            [1, 1, 1060, 'menu.main', 'Backups', null, '/core/backup/index', 3, 0, 1, 1, new \yii\db\Expression('NOW()')],
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
        $this->delete('{{%' . $module->tableNames['menu'] . '}}', ['menu_id' => 'menu.main', 'label' => 'Backups']);
    }
}