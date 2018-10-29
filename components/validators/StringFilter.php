<?php

namespace pravda1979\core\components\validators;

use yii\validators\Validator;

class StringFilter extends Validator
{
    public $strip_tags = true;
    public $ucwords = false;
    public $line_break = false;

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $str = $model->$attribute;

        if ($this->line_break == true) {
            $str = trim(preg_replace('/ \t\v\f/', ' ', $str));
            $str = trim(preg_replace('/[\r\n]{2,}/', "\n", $str));
        } else
            $str = trim(preg_replace('/\s+/', ' ', $str));

        if ($this->strip_tags == true)
            $str = strip_tags($str);

        if ($this->ucwords == true)
            $str = mb_convert_case($str, MB_CASE_TITLE, "UTF-8");

        $model->$attribute = $str;
    }
}