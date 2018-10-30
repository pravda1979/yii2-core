<?php

namespace pravda1979\core\controllers;

use Yii;
use pravda1979\core\components\core\DataController;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends DataController
{
    const modelClass = 'pravda1979\core\models\Menu';
    const searchModelClass = 'pravda1979\core\searches\MenuSearch';

}
