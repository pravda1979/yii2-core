<?php

namespace pravda1979\core\controllers;

use Yii;
use pravda1979\core\components\core\DataController;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends DataController
{
    const modelClass = 'pravda1979\core\models\Message';
    const searchModelClass = 'pravda1979\core\searches\MessageSearch';

}
