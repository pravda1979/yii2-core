<?php

namespace pravda1979\core\components\validators;

use Yii;
use yii\validators\Validator;

class InnValidator extends Validator
{
    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        if (!(preg_match('/^[0-9]{8}$/', $model->$attribute)
            || preg_match('/^[0-9]{10}$/', $model->$attribute)
            || preg_match('/^[А-ЯA-Z]{2} [0-9]{6}$/u', $model->$attribute)// Укр. паспорт
            || preg_match('/^[0-9]{4} [0-9]{6}$/', $model->$attribute) // Россия и ДНР паспорт
        )
        ) {
            $model->addError($attribute, Yii::t('InnValidator', 'The value in the "{inn}" field should contain either 8 or 10 digits, or a series and passport number (For example: Ukraine - "АА 123456", Russia and DNR - "0123 123456"', ['inn' => $model->getAttributeLabel($attribute)]));
        }
    }
}