<?php

namespace pravda1979\core\components\validators;

use Yii;
use yii\validators\Validator;

class PhoneValidator extends Validator
{
    public $multiple = false;

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $errors = 0;
        $arr = [];
        $result = [];
        $message = Yii::t('PhoneValidator', 'Wrong phone format. Allowed formats: +38(XXX)XXX-XX-XX, +7(XXX)XXX-XX-XX or +8(XXX)XXX-XX-XX, где X - digit. Brackets, dashes and spaces are optional.');
        if ($this->multiple === true) {
            $arr = explode(',', trim($model->$attribute, ','));
            $message .= ' ' . Yii::t('PhoneValidator', 'Phone numbers must be separated by commas.');
        } else {
            $arr[] = $model->$attribute;
        }
        foreach ($arr as $item) {
            if (($str = $this->validatePhone($item)) !== false) {
                $result[] = $str;
            } else {
                $model->addError($attribute, $message);
                $errors++;
                return;
            }
        }
        if ($errors === 0) {
            $model->$attribute = implode(', ', $result);
        }
    }

    /**
     * @param $phone
     * @return string
     */
    public static function validatePhone($phone)
    {
        $str = trim(preg_replace('/[\s()-]/', '', $phone));
        if (preg_match('/^(\+7|\+8|\+38)[0-9]{10}$/', $str)) {
            return $str;
        } else {
            return false;
        }
    }

    /**
     * @param $phone
     * @return string
     */
    public static function formatPhone($phone)
    {
        $phones = explode(',', $phone);

        $result = '';
        foreach ($phones as $phoneNumber) {
            $number = trim(preg_replace('/[\s()-]/', '', $phoneNumber));
            if (preg_match('/^\+(\d+)(\d{3})(\d{3})(\d{2})(\d{2})$/', $number, $matches)) {
                $resultPhone = '+' . $matches[1] . '(' . $matches[2] . ')' . $matches[3] . '-' . $matches[4] . '-' . $matches[5];
                if (!empty($result)) {
                    $result .= ', ';
                }
                $result .= $resultPhone;
            }
        }
        return $result;
    }
}