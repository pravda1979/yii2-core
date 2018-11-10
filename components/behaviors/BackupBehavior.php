<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 06.11.2018
 * Time: 17:58
 */

namespace pravda1979\core\components\behaviors;

use pravda1979\core\models\Backup;
use pravda1979\core\models\BackupAttribute;
use pravda1979\core\components\core\ActiveRecord;
use Yii;

use yii\base\Behavior;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\helpers\Html;
use yii\helpers\Inflector;

/**
 * @property \pravda1979\core\components\core\ActiveRecord $_oldModel
 */
class BackupBehavior extends Behavior
{
    private $_oldModel = [];
    private $_dirtyAttributes = [];
    private $_backupAttributes = [];
    public $id_field = 'id';
    public $name_field = 'fullName';

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function beforeUpdate($event)
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        $this->_oldModel = $model::findOne($model->{$this->id_field});
        $this->getBackupAttributes($model->dirtyAttributes);
    }

    public function beforeDelete($event)
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        $this->_oldModel = $model::findOne($model->{$this->id_field});
        $this->getBackupAttributes($model->attributes);
    }

    public function getBackupAttributes($changedAttributes = [])
    {
        $result = [];
        /** @var ActiveRecord $model */
        $model = $this->owner;

        foreach ($changedAttributes as $attribute => $value) {
            $oldLabel = $newLabel = null;

            if (isset($this->_oldModel->backupLabels[$attribute])) {
                if ($this->_oldModel->backupLabels[$attribute] instanceof \Closure) {
                    $oldLabel = call_user_func($this->_oldModel->backupLabels[$attribute]);
                } else {
                    $oldLabel = $this->_oldModel->backupLabels[$attribute];
                }
            }

            if (isset($model->backupLabels[$attribute])) {
                if ($model->backupLabels[$attribute] instanceof \Closure) {
                    $newLabel = call_user_func($model->backupLabels[$attribute]);
                } else {
                    $newLabel = $model->backupLabels[$attribute];
                }
            }

            $result[$attribute] = [
                'old_value' => $this->_oldModel->$attribute,
                'new_value' => $model->$attribute,
                'old_label' => $oldLabel,
                'new_label' => $newLabel,
            ];
        }

        $this->_backupAttributes = $result;
        return $result;
    }

    public function afterUpdate($event)
    {
        $this->createBackup($this->_backupAttributes);
    }

    public function afterDelete($event)
    {
        $this->createBackup($this->_backupAttributes);
    }

    public function createBackup($changedAttributes = [])
    {
//        Yii::warning($changedAttributes);
        if (!empty($changedAttributes)) {
            /** @var \pravda1979\core\components\core\ActiveRecord $model */
            $model = $this->owner;
            $model->refresh();
            $backup = new Backup();
            $backup->action = Yii::$app->controller->action->id;
            $backup->record_id = $model->{$this->id_field};
            $backup->record_name = $model->{$this->name_field};
            $backup->record_class = $model::className();
            $backup->record_short_class = Inflector::id2camel(basename($model::className()));
            $backup->save();

            foreach ($changedAttributes as $attribute => $fields) {
                $backupAttribute = new BackupAttribute();
                $backupAttribute->backup_id = $backup->id;
                $backupAttribute->attribute = $attribute;
                $backupAttribute->old_value = $fields['old_value'];
                $backupAttribute->new_value = $fields['new_value'];
                $backupAttribute->old_label = $fields['old_label'];
                $backupAttribute->new_label = $fields['new_label'];
                $backupAttribute->save();
            }
        }
    }

    /**
     * @param $backup Backup
     */
    public static function undoChanges($backup)
    {
        $model = $backup->getParentModel();

        foreach ($backup->getOldValues() as $attribute => $value) {
            $model->$attribute = $value;
        }

        if (!$model->save()) {
            Yii::$app->getSession()->addFlash('error', Html::errorSummary($model, ['header' => '']));
        }
    }
}