<?php

use pravda1979\core\components\migration\Migration;

class m181203_205202_rbac_and_translates_auth_item extends Migration
{
    public $table_name = '';
    public $route = 'core/auth-item';
    public $parents = [
        'AuthItem: editor' => 'AuthItem: viewer',
        'AuthItem: admin' => 'AuthItem: editor',
        'admin' => 'AuthItem: admin',
    ];
    public $actions = [
        'AuthItem: viewer' => [
            'index',
            'view',
            'autocomplete',
        ],
        'AuthItem: editor' => [
            'create',
            'update',
        ],
        'AuthItem: admin' => [
            'delete',
        ],
    ];
    public $modelNames = [
        'singular' => 'Право доступа',
        'plural' => 'Права доступа',
        'accusative' => 'право доступа', // Винительный падеж (кого, что)
        'genitive' => 'права доступа', // Родительный падеж (кого, чего)
    ];

    /**
     * @inheritdoc
     */
    public function getTranslates($translates = [])
    {
        $translates = [
            'ru-RU' => [
                'AuthItem' => [
                    'Index' => 'Список прав доступа',
                    'Name' => 'Наименование',
                    'Type' => 'Тип',
                    'Description' => 'Описание',
                    'Rule Name' => 'Правило',
                    'Data' => 'Данные',
                    'TYPE_ROLE' => 'Роль',
                    'TYPE_ROUTE' => 'Маршрут',
                    'Children Items' => 'Дочерние элементы',
                    'Parent Items' => 'Родительские элементы',
                ],
            ],
        ];

        $this->table_name = 'auth_item';
        return parent::getTranslates($translates);
    }

    public function safeUp()
    {
        /** @var \pravda1979\core\Module $module */
        $module = Yii::$app->getModule('core');

        // $VISIBLE_CHECK_ACCESS = 1; $VISIBLE_GUEST = 10; $VISIBLE_AUTHORIZED = 20; $VISIBLE_ADMIN = 30;
        // $VISIBLE_ALWAYS = 40; $VISIBLE_NEVER = 50; $VISIBLE_HAS_CHILDREN = 60;
        // data=>1; dirs=>2; admin=>3; instruments=>8;
        $this->batchInsert('{{%' . $module->tableNames['menu'] . '}}', ['use_url_helper', 'visible', 'position', 'menu_id', 'label', 'icon', 'url', 'parent_id', 'level', 'status_id', 'user_id', 'updated_at'], [
            [1, 1, 450, 'menu.main', 'Auth Items', null, '/core/auth-item/index', 3, 1, 1, 1, new \yii\db\Expression('NOW()')],
        ]);

        $this->createTranslates();
        $this->createRbac();
    }

    public function safeDown()
    {
        /** @var \pravda1979\core\Module $module */
        $module = Yii::$app->getModule('core');
        $this->deleteTranslates();
        $this->deleteRbac();
        $this->delete('{{%' . $module->tableNames['menu'] . '}}', ['menu_id' => 'menu.main', 'label' => 'Auth Items']);
    }
}