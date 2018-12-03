<?php

use pravda1979\core\components\migration\Migration;

class m181107_231509_backup_attribute extends Migration
{
    public $table_name = 'backup_attribute';
    public $route = 'core/backup-attribute';
    public $parents = [
//        'BackupAttribute: editor' => 'BackupAttribute: viewer',
//        'BackupAttribute: admin' => 'BackupAttribute: editor',
//        'viewer' => 'BackupAttribute: viewer',
//        'editor' => 'BackupAttribute: editor',
//        'admin' => 'BackupAttribute: admin',
    ];
    public $actions = [
//        'BackupAttribute: viewer' => [
//            'index',
//            'view',
//            'autocomplete',
//        ],
//        'BackupAttribute: editor' => [
//            'create',
//            'update',
//        ],
//        'BackupAttribute: admin' => [
//            'delete',
//        ],
    ];
    public $modelNames = [
        'singular' => 'Бэкап аттрибута',
        'plural' => 'Бэкапы аттрибутов',
        'accusative' => 'бэкап аттрибута', // Винительный падеж (кого, что)
        'genitive' => 'бэкап аттрибута', // Родительный падеж (кого, чего)
    ];

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

            'backup_id' => $this->integer(),
            'attribute' => $this->string(255),
            'old_value' => $this->text(),
            'new_value' => $this->text(),
            'old_label' => $this->text(),
            'new_label' => $this->text(),

            'note' => $this->text(),
            'status_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey("{{%fk_" . "user_id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[user_id]]", "{{%" . $module->tableNames['user'] . "}}", "[[id]]");
        $this->addForeignKey("{{%fk_" . "status_id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[status_id]]", "{{%" . $module->tableNames['status'] . "}}", "[[id]]");
        $this->addForeignKey("{{%fk_" . "backup_id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[backup_id]]", "{{%" . $module->tableNames['backup'] . "}}", "[[id]]", 'CASCADE');
   }

    public function safeDown()
    {
        /** @var \pravda1979\core\Module $module */
        $module = Yii::$app->getModule('core');
        $this->dropTable("{{%" . $module->tableNames[$this->table_name] . "}}");
    }
}