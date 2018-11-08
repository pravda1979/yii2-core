<?php

namespace pravda1979\core\controllers;

use pravda1979\core\components\behaviors\BackupBehavior;
use pravda1979\core\models\Backup;
use Yii;
use pravda1979\core\components\core\DataController;

/**
 * BackupController implements the CRUD actions for Backup model.
 */
class BackupController extends DataController
{
    const modelClass = 'pravda1979\core\models\Backup';
    const searchModelClass = 'pravda1979\core\searches\BackupSearch';

    /**
     * @param $id
     */
    public function actionUndo($id)
    {
        BackupBehavior::undoChanges(Backup::findOne($id));
        $this->goBack();
    }
}
