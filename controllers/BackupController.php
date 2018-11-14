<?php

namespace pravda1979\core\controllers;

use pravda1979\core\components\behaviors\BackupBehavior;
use pravda1979\core\components\core\ActiveRecord;
use pravda1979\core\models\Backup;
use pravda1979\core\searches\BackupSearch;
use Yii;
use pravda1979\core\components\core\DataController;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

/**
 * BackupController implements the CRUD actions for Backup model.
 */
class BackupController extends DataController
{
    const modelClass = 'pravda1979\core\models\Backup';
    const searchModelClass = 'pravda1979\core\searches\BackupSearch';

    public function allowAction()
    {
        return [
            '/core/backup/undo',
            '/core/backup/history',
        ];
    }

    /**
     * @param $id
     * @throws ForbiddenHttpException
     */
    public function actionUndo($id)
    {
        if (Yii::$app->user->can('::admin')) {
            BackupBehavior::undoChanges(Backup::findOne($id));
            $this->goBack();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

    }

    /**
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionHistory()
    {
        if (Yii::$app->user->can('/core/backup/history')) {

            /** @var ActiveRecord $model */
            $class = ArrayHelper::getValue(Yii::$app->request->queryParams, 'BackupSearch.record_class');
            $id = ArrayHelper::getValue(Yii::$app->request->queryParams, 'BackupSearch.record_id');
            $model = $class::findOne($id);

            $searchModel = new BackupSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            Url::remember();
            return $this->render('history', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]);

        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

    }
}
