<?php

namespace pravda1979\core\controllers;

use Yii;
use pravda1979\core\components\core\DataController;

/**
 * StatusController implements the CRUD actions for Status model.
 */
class StatusController extends DataController
{
    const modelClass = 'pravda1979\core\models\Status';
    const searchModelClass = 'pravda1979\core\searches\StatusSearch';

}
