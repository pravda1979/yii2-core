<?php

namespace pravda1979\core\controllers;

use Yii;
use pravda1979\core\components\core\DataController;

/**
 * SourceMessageController implements the CRUD actions for SourceMessage model.
 */
class SourceMessageController extends DataController
{
    const modelClass = 'pravda1979\core\models\SourceMessage';
    const searchModelClass = 'pravda1979\core\searches\SourceMessageSearch';

}
