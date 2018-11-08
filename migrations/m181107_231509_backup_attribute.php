<?php

use pravda1979\core\components\migration\Migration;

class m181107_231509_backup_attribute extends Migration
{
    public $table_name = 'backup_attribute';
    public $route = 'core/backup-attribute';
    public $parents = [
        'BackupAttribute: editor' => 'BackupAttribute: viewer',
        'BackupAttribute: admin' => 'BackupAttribute: editor',
        'viewer' => 'BackupAttribute: viewer',
        'editor' => 'BackupAttribute: editor',
        'admin' => 'BackupAttribute: admin',
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

    /**
     * @inheritdoc
     */
    public function getTranslates($translates = [])
    {
        $translates =[
            'ru-RU' => [
                'BackupAttribute' => [
                    'Index' => 'Список измененных аттрибутов',
                    'Backup ID' => 'ID бекапа',
                    'Attribute' => 'Поле',
                    'Attribute Name' => 'Поле',
                    'Old Value' => 'Старое значение',
                    'New Value' => 'Новое значение',
                    'Old Label' => 'Старый текст',
                    'New Label' => 'Новый текст',
                    'Old' => 'Старое значение',
                    'New' => 'Новое значение',
                    'Changes' => 'Изменения',
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

            $this->addForeignKey("{{%fk_" . "user_id" . "_$this->table_name}}", "{{%$this->table_name}}", "[[user_id]]", "{{%user}}", "[[id]]");
            $this->addForeignKey("{{%fk_" . "status_id" . "_$this->table_name}}", "{{%$this->table_name}}", "[[status_id]]", "{{%status}}", "[[id]]");
            $this->addForeignKey("{{%fk_" . "backup_id" . "_$this->table_name}}", "{{%$this->table_name}}", "[[backup_id]]", "{{%backup}}", "[[id]]", 'CASCADE');
        }

// $VISIBLE_CHECK_ACCESS = 1; $VISIBLE_GUEST = 10; $VISIBLE_AUTHORIZED = 20; $VISIBLE_ADMIN = 30;
// $VISIBLE_ALWAYS = 40; $VISIBLE_NEVER = 50; $VISIBLE_HAS_CHILDREN = 60;
// data=>1; dirs=>2; admin=>3; instruments=>8;
//        $this->batchInsert('{{%menu}}', ['use_url_helper', 'visible', 'position', 'menu_id', 'label', 'icon', 'url', 'parent_id', 'level', 'status_id', 'user_id', 'updated_at'], [
//            [1, 1, 1061, 'menu.main', 'Backup Attributes', null, '/core/backup-attribute/index', 3, 1, 1, 1, new \yii\db\Expression('NOW()')],
//        ]);

        $this->createTranslates();
        $this->createRbac();
    }

    public function safeDown()
    {
        if ($this->db->schema->getTableSchema("{{%$this->table_name}}", true) != null)
            $this->dropTable("{{%$this->table_name}}");
        $this->deleteTranslates();
        $this->deleteRbac();
        $this->delete('{{%menu}}', ['menu_id' => 'menu.main', 'label' => 'Backup Attributes']);
    }
}