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
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @property \pravda1979\core\components\core\ActiveRecord $_oldModel
 */
class BackupBehavior extends Behavior
{
    private $_oldModel = [];
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
        $dirtyAttributes = array_merge($model->dirtyAttributes,
            array_diff_key($model->backupLabels, $model->attributes));
        $this->getBackupAttributes($dirtyAttributes);
    }

    public function beforeDelete($event)
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        $this->_oldModel = $model::findOne($model->{$this->id_field});
        $this->getBackupAttributes($model->attributes, true);
    }

    public function getBackupAttributes($changedAttributes = [], $isDeleting = false)
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

//            if ($model->canGetProperty($attribute)) {
//                if (is_array($model->$attribute)) {
//                    if (empty($model->$attribute)) {
//                        $newValue = null;
//                    } else {
//                        $newValue = serialize($model->$attribute);
//                    }
//                } else {
//                    $newValue = $model->$attribute;
//                }
//            } else {
//                $newValue = $newLabel;
//            }
//
//            if ($this->_oldModel->canGetProperty($attribute)) {
//                if (is_array($this->_oldModel->$attribute)) {
//                    if (empty($this->_oldModel->$attribute)) {
//                        $oldValue = null;
//                    } else {
//                        $oldValue = serialize($this->_oldModel->$attribute);
//                    }
//                } else {
//                    $oldValue = $this->_oldModel->$attribute;
//                }
//            } else {
//                $oldValue = $oldLabel;
//            }

            $oldValue = $this->_oldModel->canGetProperty($attribute) ?
                ((is_array($this->_oldModel->$attribute) && !empty($this->_oldModel->$attribute)) ? serialize($this->_oldModel->$attribute) : $this->_oldModel->$attribute) :
                $oldLabel;

            $newValue = $model->canGetProperty($attribute) ?
                ((is_array($model->$attribute) && !empty($model->$attribute)) ? serialize($model->$attribute) : $model->$attribute) :
                $newLabel;

            if (!(empty($newValue) && empty($oldValue)) && $newValue != $oldValue || $isDeleting) {
                $result[$attribute] = [
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                    'old_label' => $oldLabel,
                    'new_label' => $newLabel,
                ];
            }
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
        if (!empty($changedAttributes)) {

            /** @var \pravda1979\core\components\core\ActiveRecord $model */
            $model = $this->owner;
            $model->refresh();
            $backup = new Backup();
            $backup->action = Yii::$app->controller->action->id;
            $backup->record_id = $model->{$this->id_field};
            $backup->record_name = $model->{$this->name_field};
            $backup->record_class = $model::className();
            $backup->record_short_class = Inflector::id2camel(StringHelper::basename($model::className()));
            $backup->save();

            $newAttributes = $this->getBackupAttributes($model->getAttributes(array_keys($changedAttributes)), true);

            foreach ($changedAttributes as $attribute => $fields) {
                $backupAttribute = new BackupAttribute();
                $backupAttribute->backup_id = $backup->id;
                $backupAttribute->attribute = $attribute;
                $backupAttribute->old_value = $fields['old_value'];
                $backupAttribute->old_label = $fields['old_label'];
                $backupAttribute->new_value = ArrayHelper::getValue($newAttributes, "$attribute.new_value");
                $backupAttribute->new_label = ArrayHelper::getValue($newAttributes, "$attribute.new_label");
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
            if (!$model->canSetProperty($attribute)) {
                continue;
            }

            if ((($unserializedValue = @unserialize($value)) !== false || $value == 'b:0;')) {
                $value = $unserializedValue;
            }

            $model->$attribute = $value;
        }

        if (!$model->save()) {
            Yii::$app->getSession()->addFlash('error', Html::errorSummary($model, ['header' => '']));
        }
    }
}