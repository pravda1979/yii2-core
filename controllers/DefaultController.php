<?php

namespace pravda1979\core\controllers;

use pravda1979\core\components\Controller;

/**
 * Default controller for the `core` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTest()
    {
        return $this->render('test');
    }
}
