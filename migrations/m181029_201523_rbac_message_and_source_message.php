<?php

use pravda1979\core\components\migration\Migration;

class m181029_201523_rbac_message_and_source_message extends Migration
{
    public $table_name = '';
    public $route = '';
    public $parents = [
        'Message: editor' => 'Message: viewer',
        'Message: admin' => 'Message: editor',
        'SourceMessage: editor' => 'SourceMessage: viewer',
        'SourceMessage: admin' => 'SourceMessage: editor',

        '::viewer' => ['SourceMessage: viewer', 'Message: viewer'],
        '::editor' => ['SourceMessage: editor', 'Message: editor'],
        '::admin' => ['SourceMessage: admin', 'Message: admin'],

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
        'SourceMessage: viewer' => [
            'core/source-message/index',
            'core/source-message/view',
            'core/source-message/autocomplete',
        ],
        'SourceMessage: editor' => [
            'core/source-message/create',
            'core/source-message/update',
        ],
        'SourceMessage: admin' => [
            'core/source-message/delete',
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