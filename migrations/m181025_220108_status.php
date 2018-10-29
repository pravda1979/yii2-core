<?php

use pravda1979\core\components\migration\Migration;

class m181025_220108_status extends Migration
{
    public $table_name = 'status';
    public $route = 'status';
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
        'singular' => 'Статус записи',
        'plural' => 'Статусы записей',
        'accusative' => 'статус записи', // Винительный падеж (кого, что)
        'genitive' => 'статуса записи', // Родительный падеж (кого, чего)
    ];

    /**
     * @inheritdoc
     */
    public function getTranslates($translates = [])
    {
        $translates = [
            'ru-RU' => [
                'Status' => [
                    'Index' => 'Список статусов',
                    'Name' => 'Наименование',
                    'Fixed Status ID' => 'Фиксированный статус',
                    'Is Default' => 'По умолчанию',
                    'The fixed status "{statusName}" does not have a default value' => 'Фиксированный статус "{statusName}" не имеет значения по умолчанию',
                    'Active record' => 'Активная запись',
                    'Draft record' => 'Черновик',
                    'Deleted record' => 'Запрещенная запись',
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

                'name' => $this->string(255)->notNull()->unique(),
                'fixed_status_id' => $this->integer()->notNull(),
                'is_default' => $this->boolean()->notNull()->defaultValue(0),

                'note' => $this->text(),
                'status_id' => $this->integer()->notNull(),
                'user_id' => $this->integer()->notNull(),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->addForeignKey("{{%fk_" . "user_id" . "_$this->table_name}}", "{{%$this->table_name}}", "[[user_id]]", "{{%core_user}}", "[[id]]");
            $this->addForeignKey("{{%fk_" . "status_id" . "_$this->table_name}}", "{{%$this->table_name}}", "[[status_id]]", "{{%status}}", "[[id]]");

            $this->createIndex("{{%ix_" . "is_default" . "_$this->table_name}}", "{{%$this->table_name}}", "[[is_default]]");
            $this->createIndex("{{%ix_" . "fixed_status_id" . "_$this->table_name}}", "{{%$this->table_name}}", "[[fixed_status_id]]");

            $this->batchInsert('{{%' . $this->table_name . '}}', ['id', 'name', 'is_default', 'fixed_status_id', 'status_id', 'user_id', 'updated_at'], [
                [1, 'Активная запись', 1, 100, 1, 1, new \yii\db\Expression('NOW()')],
                [2, 'Черновик', 1, 10, 1, 1, new \yii\db\Expression('NOW()')],
                [3, 'Запрещенная запись', 1, 1, 1, 1, new \yii\db\Expression('NOW()')],
            ]);

            // Добавляем связь для поля "status_id" в таблице "user"
            $this->addForeignKey("{{%fk_" . "status_id" . "_user}}", "{{%core_user}}", "[[status_id]]", "{{%status}}", "[[id]]");
        }

        $this->createTranslates();
        $this->createRbac();
    }

    public function safeDown()
    {
        // Удаляем связь для поля "status_id" в таблице "user"
        $this->dropForeignKey("{{%fk_" . "status_id" . "_user}}", "{{%core_user}}");

        if ($this->db->schema->getTableSchema("{{%$this->table_name}}", true) != null)
            $this->dropTable("{{%$this->table_name}}");
        $this->deleteTranslates();
        $this->deleteRbac();
    }
}