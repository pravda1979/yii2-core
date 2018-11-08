<?php

namespace pravda1979\core\controllers;

use Yii;
use pravda1979\core\components\core\DataController;

/**
 * BackupAttributeController implements the CRUD actions for BackupAttribute model.
 */
class BackupAttributeController extends DataController
{
    const modelClass = 'pravda1979\core\models\BackupAttribute';
    const searchModelClass = 'pravda1979\core\searches\BackupAttributeSearch';

}
