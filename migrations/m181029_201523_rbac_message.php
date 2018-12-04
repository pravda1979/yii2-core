<?php

use pravda1979\core\components\migration\Migration;

class m181029_201523_rbac_message extends Migration
{
    public $table_name = '';
    public $route = '';
    public $parents = [
        'Message: editor' => 'Message: viewer',
        'Message: admin' => 'Message: editor',
        'admin' => 'Message: admin',
    ];
    public $actions = [
        'Message: viewer' => [
            'core/message/index',
            'core/message/view',
            'core/message/autocomplete',
        ],
        'Message: editor' => [
            'core/message/create',
            'core/message/update',
        ],
        'Message: admin' => [
            'core/message/delete',
        ],
    ];

    public function safeUp()
    {
        $this->createRbac();
    }

    public function safeDown()
    {
        $this->deleteRbac();
    }
}