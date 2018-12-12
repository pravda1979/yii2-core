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

class AutocompleteAction extends Action
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

    public function run($term, array $params = [], $realOnly = true)
    {
        /** @var ActiveRecord $className */
        $className = $this->modelClass;

        Yii::warning($params,'$params');
        if (Yii::$app->request->isAjax) {
            $term = trim(preg_replace('/\s+/', ' ', $term));

            $qs = explode(" ", $term);
            $query = $className::getListQuery();
            if ($realOnly)
                $query->real();
            $query->andFilterWhere($params);
            $fullName = $className::getFullNameSql();
            foreach ($qs as $q) {
                $query->andWhere(['like', $fullName, "$q"]);
            }

            $totalCount = $query->count();
            $results = $query
                ->limit($this->maxResultCount)
                ->asArray()
                ->all();

            if ($totalCount > $this->maxResultCount) {
                array_unshift($results, [
                    'id' => null,
                    'label' => Yii::t('app', '... shown {count} items from {total} ...', ['count' => $this->maxResultCount, 'total' => $totalCount])
                ]);
            }

            return Json::encode($results);
        }
    }
}