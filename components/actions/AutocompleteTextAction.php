<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 26.10.2018
 * Time: 15:50
 */

namespace pravda1979\core\components\actions;

use pravda1979\core\components\core\Action;
use pravda1979\core\components\core\ActiveRecord;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;

class AutocompleteTextAction extends Action
{
    public $modelClass = '';
    public $maxResultCount = 100;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->modelClass === null) {
            throw new InvalidConfigException('The "modelClass" property must be set.');
        }
    }

    public function run($term = '', array $params = [])
    {
        /** @var ActiveRecord $className */
        $className = $this->modelClass;

        if (Yii::$app->request->isAjax) {

            $query = $className::find()
                ->addTerm($term)
                ->addWhereParams($params);

            $results = $query->asDropDownList();

            return Json::encode(['results' => $results]);
        }
    }
}