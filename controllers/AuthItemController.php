<?php

namespace pravda1979\core\controllers;

use Yii;
use pravda1979\core\components\core\DataController;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 */
class AuthItemController extends DataController
{
    const modelClass = 'pravda1979\core\models\AuthItem';
    const searchModelClass = 'pravda1979\core\searches\AuthItemSearch';

}
