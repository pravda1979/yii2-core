<?php

use pravda1979\core\components\migration\Migration;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class m181109_144432_options extends Migration
{
    public $table_name = 'options';
    public $route = 'core/options';
    public $parents = [
        'admin' => 'Options: admin',
    ];
    public $actions = [
        'Options: admin' => [
            'index',
        ],
    ];
    public $modelNames = [
        'singular' => 'Настройки',
        'plural' => 'Настройки',
        'accusative' => 'настройки', // Винительный падеж (кого, что)
        'genitive' => 'настроек', // Родительный падеж (кого, чего)
    ];

    /**
     * @inheritdoc
     */
    public function getTranslates($translates = [])
    {
        $className = 'Options';
        $classesName = 'Options';

        $singular = $this->modelNames['singular'];
        $plural = $this->modelNames['plural'];
        $accusative = $this->modelNames['accusative'];
        $genitive = $this->modelNames['genitive'];

        return ArrayHelper::merge([
            'ru-RU' => [
                'models' => [
                    $className => $singular,
                ],
                'menu.main' => [
                    Inflector::camel2words($classesName) => $plural,
                ],
                'role' => [
                    $className . ': editor' => $plural . ': Редактирование',
                    $className . ': viewer' => $plural . ': Просмотр',
                    $className . ': admin' => $plural . ': Полный доступ',
                ],
                $className => [
                    $className => $singular,
                    $classesName => $plural,
                    Inflector::camel2words($className) => $singular,
                    Inflector::camel2words($classesName) => $plural,

                    'Index' => 'Настройки',
                    'Create' => 'Добавить ' . $accusative,
                    'Save' => 'Сохранить ' . $accusative,
                    'Update' => 'Изменить ' . $accusative,
                    'Delete' => 'Удалить ' . $accusative,
                    'Find' => 'Найти ' . $accusative,
                    'View' => 'Просмотреть ' . $accusative,
                    'Viewing' => 'Просмотр ' . $genitive,
                    'Creating' => 'Добавление ' . $genitive,
                    'Updating' => 'Изменение ' . $genitive,
                    'Deleting' => 'Удаление ' . $genitive,
                    'Search' => 'Поиск ' . $genitive,

                    'ID' => 'ID',
                    'Category' => 'Категория',
                    'Name' => 'Элемент',
                    'Value' => 'Значение',
                    'Note' => 'Примечание',
                    'Status ID' => 'Статус записи',
                    'User ID' => 'Пользователь',
                    'Created At' => 'Дата создания',
                    'Updated At' => 'Дата изменения',

                    'Options was saved successfully.' => 'Настройки успешно сохранены.',

                    'app_LTEAdminMenuState' => 'Главное меню (слева)',
                    'app_LTEAdminSkin' => 'Скин AdminLTE',
                    'app_UseBackups' => 'Вести журнал изменений',
                    'app_UseUserActionLog' => 'Вести журнал действий пользователей',
                    'app_Theme' => 'Тема',
                ],
            ],
        ], $translates);
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

            'category' => $this->string(255),
            'name' => $this->string(255),
            'value' => $this->text(),

            'note' => $this->text(),
            'status_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey("{{%fk_" . "user_id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[user_id]]", "{{%" . $module->tableNames['user'] . "}}", "[[id]]");
        $this->addForeignKey("{{%fk_" . "status_id" . "_" . $module->tableNames[$this->table_name] . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", "[[status_id]]", "{{%" . $module->tableNames['status'] . "}}", "[[id]]");

        $this->createIndex("{{%" . $module->tableNames[$this->table_name] . "_" . "category_name" . "}}", "{{%" . $module->tableNames[$this->table_name] . "}}", ["[[category]]", "[[name]]"], true);

        $this->batchInsert('{{%' . $module->tableNames[$this->table_name] . '}}', ['category', 'name', 'value', 'status_id', 'user_id', 'updated_at'], [
            ['app', 'app_UseBackups', 1, 1, 1, new \yii\db\Expression('NOW()')],
            ['app', 'app_UseUserActionLog', 1, 1, 1, new \yii\db\Expression('NOW()')],
            ['app', 'app_Theme', 'lteadmin', 1, 1, new \yii\db\Expression('NOW()')],
            ['app', 'app_LTEAdminMenuState', 0, 1, 1, new \yii\db\Expression('NOW()')],
            ['app', 'app_LTEAdminSkin', 'skin-blue', 1, 1, new \yii\db\Expression('NOW()')],
        ]);

        // $VISIBLE_CHECK_ACCESS = 1; $VISIBLE_GUEST = 10; $VISIBLE_AUTHORIZED = 20; $VISIBLE_ADMIN = 30;
        // $VISIBLE_ALWAYS = 40; $VISIBLE_NEVER = 50; $VISIBLE_HAS_CHILDREN = 60;
        // data=>1; dirs=>2; admin=>3; instruments=>8;
        $this->batchInsert('{{%' . $module->tableNames['menu'] . '}}', ['use_url_helper', 'visible', 'position', 'menu_id', 'label', 'icon', 'url', 'parent_id', 'level', 'status_id', 'user_id', 'updated_at'], [
            [1, 1, 3000, 'menu.main', 'Options', 'cog', '/core/options/index', 3, 1, 1, 1, new \yii\db\Expression('NOW()')],
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
        $this->delete('{{%' . $module->tableNames['menu'] . '}}', ['menu_id' => 'menu.main', 'label' => 'Options']);
    }
}