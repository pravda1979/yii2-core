<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 25.10.2018
 * Time: 15:31
 */

namespace pravda1979\core\components\core;

use yii\db\Query;
use yii\i18n\MissingTranslationEvent;

class TranslationEventHandler
{
    public static function addMissingTranslation(MissingTranslationEvent $event)
    {
//        $event->translatedMessage = "@MISSING: {$event->category}.{$event->message} FOR LANGUAGE {$event->language} @";
        $db = \Yii::$app->db;

        $exist = (new Query())->select(['id', 'category', 'message'])->from('{{%source_message}}')
            ->where(['category' => $event->category, 'message' => $event->message])
            ->one($db);

        if (empty($exist)) {
            $lastPk = \Yii::$app->db->schema->insert('{{%source_message}}', ['category' => $event->category, 'message' => $event->message]);
            $db->createCommand()
                ->insert('{{%message}}', ['id' => $lastPk['id'], 'language' => \Yii::$app->language, 'translation' => $event->translatedMessage])
                ->execute();
        }
    }
}