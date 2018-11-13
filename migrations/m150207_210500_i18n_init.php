<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

use yii\db\Migration;

/**
 * Initializes i18n messages tables.
 *
 *
 *
 * @author Dmitry Naumenko <d.naumenko.a@gmail.com>
 * @since 2.0.7
 */
class m150207_210500_i18n_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /** @var \pravda1979\core\Module $module */
        $module = Yii::$app->getModule('core');

        $this->createTable('{{%' . $module->tableNames['source_message'] . '}}', [
            'id' => $this->primaryKey(),
            'category' => $this->string(),
            'message' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%' . $module->tableNames['message'] . '}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(16)->notNull(),
            'translation' => $this->text(),
        ], $tableOptions);

        $this->addPrimaryKey('pk_' . $module->tableNames['message'] . '_id_language', '{{%' . $module->tableNames['message'] . '}}', ['id', 'language']);
        $this->addForeignKey('fk_' . $module->tableNames['message'] . '_' . $module->tableNames['source_message'], '{{%' . $module->tableNames['message'] . '}}', 'id', '{{%' . $module->tableNames['source_message'] . '}}', 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex('idx_' . $module->tableNames['source_message'] . '_category', '{{%' . $module->tableNames['source_message'] . '}}', 'category');
        $this->createIndex('idx_' . $module->tableNames['message'] . '_language', '{{%' . $module->tableNames['message'] . '}}', 'language');
    }

    public function down()
    {
        /** @var \pravda1979\core\Module $module */
        $module = Yii::$app->getModule('core');

        $this->dropForeignKey('fk_' . $module->tableNames['message'] . '_' . $module->tableNames['source_message'], '{{%' . $module->tableNames['message'] . '}}');
        $this->dropTable('{{%' . $module->tableNames['message'] . '}}');
        $this->dropTable('{{%' . $module->tableNames['source_message'] . '}}');
    }
}
