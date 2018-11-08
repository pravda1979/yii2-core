<?php
/**
 * This view is used by console/controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */
use yii\helpers\StringHelper;
use yii\helpers\Inflector;

$tblName = substr($className, 15);
$modelName = StringHelper::basename(Inflector::classify($tblName));
$modelsName = Inflector::pluralize($modelName);
echo "<?php\n";
?>

use pravda1979\core\components\migration\Migration;

class <?= $className ?> extends Migration
{
    public $table_name = '<?= $tblName ?>';
    public $route = '<?= str_replace('_', '-', Inflector::singularize($tblName)) ?>';
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
        'singular' => '<?= $modelName ?>',
        'plural' => '<?= $modelsName ?>',
        'accusative' => '<?= Inflector::camel2id($modelName) ?>', // Винительный падеж (кого, что)
        'genitive' => '<?= Inflector::camel2id($modelName) ?>', // Родительный падеж (кого, чего)
    ];

    /**
     * @inheritdoc
     */
    public function getTranslates($translates = [])
    {
        $translates =[
            'ru-RU' => [
                '<?= $modelName ?>' => [
                    'Index' => 'Список <?= $modelsName ?>',
                    'Name' => 'Наименование',
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

                'note' => $this->text(),
                'status_id' => $this->integer()->notNull(),
                'user_id' => $this->integer()->notNull(),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->addForeignKey("{{%fk_" . "user_id" . "_$this->table_name}}", "{{%$this->table_name}}", "[[user_id]]", "{{%user}}", "[[id]]");
            $this->addForeignKey("{{%fk_" . "status_id" . "_$this->table_name}}", "{{%$this->table_name}}", "[[status_id]]", "{{%status}}", "[[id]]");

//            $this->createIndex("{{%$this->table_name"."_"."fieldName"."}}", "{{%$this->table_name}}", "[[fieldName]]");
//            $this->batchInsert('{{%' . $this->table_name . '}}', ['name', 'status_id', 'user_id'], [
//                  ['name1', 1, 1],
//                  ['name2', 1, 1],
//            ]);
        }

// $VISIBLE_CHECK_ACCESS = 1; $VISIBLE_GUEST = 10; $VISIBLE_AUTHORIZED = 20; $VISIBLE_ADMIN = 30;
// $VISIBLE_ALWAYS = 40; $VISIBLE_NEVER = 50; $VISIBLE_HAS_CHILDREN = 60;
// data=>1; dirs=>2; admin=>3; instruments=>8;
//        $this->batchInsert('{{%menu}}', ['use_url_helper', 'visible', 'position', 'menu_id', 'label', 'icon', 'url', 'parent_id', 'level', 'status_id', 'user_id', 'updated_at'], [
//            [1, 1, 1000, 'menu.main', 'itemName', null, null, null, 0, 1, 1, new \yii\db\Expression('NOW()')],
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
//        $this->delete('{{%menu}}', ['menu_id' => 'menu.main', 'label' => 'itemName']);
    }
}