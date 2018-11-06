<?php

namespace pravda1979\core\controllers;

use Yii;
use pravda1979\core\components\core\DataController;

/**
 * UserActionLogController implements the CRUD actions for UserActionLog model.
 */
class UserActionLogController extends DataController
{
    const modelClass = 'pravda1979\core\models\UserActionLog';
    const searchModelClass = 'pravda1979\core\searches\UserActionLogSearch';

}
