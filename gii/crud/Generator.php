<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace pravda1979\core\gii\crud;

use yii\db\Schema;
use yii\helpers\Inflector;

class Generator extends \yii\gii\generators\crud\Generator
{

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                return "\$form->field(\$model, '$attribute')->passwordInput()";
            }

            return "\$form->field(\$model, '$attribute')";
        }
        $column = $tableSchema->columns[$attribute];

        if ($column->type === 'date') {
            return "\$form->field(\$model, '$attribute')->input('date')";
        } elseif (preg_match('/^is_/i', $attribute)) {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        } elseif ($attribute === 'status_id') {
            return "\$form->field(\$model, '$attribute')->dropDownList(Status::getListWithGroup(), ['prompt' => ''])";
        } elseif ($attribute === 'user_id') {
            return "\$form->field(\$model, '$attribute')->dropDownList(User::getList(), ['prompt' => ''])";
        } elseif (in_array($attribute, ['file_type_id', ])) {
            return "\$form->field(\$model, '$attribute')->widget(AutoCompleteWithId::className())";
        }

        return parent::generateActiveField($attribute);
    }

    /**
     * Generates code for active search field
     * @param string $attribute
     * @return string
     */
    public function generateActiveSearchField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false) {
            return "\$form->field(\$model, '$attribute')";
        }

        $column = $tableSchema->columns[$attribute];

        if (preg_match('/^is_/i', $column->name)) {
            return "\$form->field(\$model, '$attribute')->dropDownList(['1' => 'Да', '0' => 'Нет'], ['prompt' => ''])";
        } elseif ($attribute === 'status_id') {
            return "\$form->field(\$model, '$attribute')->dropDownList(Status::getListWithGroup(), ['prompt' => ''])";
        } elseif ($attribute === 'user_id') {
            return "\$form->field(\$model, '$attribute')->dropDownList(User::getList(), ['prompt' => ''])";
        } elseif (in_array($attribute, ['file_type_id'])) {
            return "\$form->field(\$model, '$attribute')->widget(AutoCompleteWithId::className(), ['clientOptions' => ['appendTo' => '.modal']])";
        }

        return parent::generateActiveField($attribute);
    }

    /**
     * Генерация поля для GridView
     *
     * @param $column
     * @param $rem
     * @return string
     */
    public function generateIndexColumn($column, $rem)
    {
        if ($column->name == 'id')
            $rem = ' //';

        if ($column->type == Schema::TYPE_DATE) {
            return "               $rem '" . "$column->name:date" . "',\n";
        } elseif (in_array($column->type, [Schema::TYPE_DATE, Schema::TYPE_DATETIME, Schema::TYPE_TIMESTAMP])) {
            return "                $rem '" . "$column->name:datetime" . "',\n";
        } elseif (preg_match('/^is_/i', $column->name)) {
            return "               $rem '" . "$column->name:boolean" . "',\n";
        } elseif ($column->type == 'datetime') {
            return "               $rem '" . "$column->name:date" . "',\n";
        } elseif ($column->name === 'status_id') {
            return "                $rem [
                $rem     'attribute' => 'status_id',
                $rem     'value' => 'status.fullName',
                $rem     'filter' => Status::getListWithGroup(),
                $rem ]" . ",\n";
        } elseif ($column->name === 'user_id') {
            return "                $rem [
                $rem     'attribute' => 'user_id',
                $rem     'value' => 'user.fullName',
                $rem     'filter' => User::getList(),
                $rem ]" . ",\n";
        } elseif (in_array($column->name, ['file_type_id'])) {
            $relName = lcfirst(Inflector::id2camel(str_replace('_id', '', $column->name), '_'));
            $modelName = Inflector::id2camel($relName, '_');
            return "                $rem [
                $rem     'attribute' => '$column->name',
                $rem     'value' => '$relName.fullName',
                $rem     'filter' => $modelName::getList(),
                $rem ]" . ",\n";
        }

        $format = $this->generateColumnFormat($column);
        return "               $rem '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
    }

    /**
     * Генерация поля для DetailView
     *
     * @param $column
     * @return string
     */
    public function generateViewColumn($column)
    {
        if ($column->type == Schema::TYPE_DATE) {
                return "                '" . "$column->name:date" . "',\n";
        } elseif (in_array($column->type, [Schema::TYPE_DATE, Schema::TYPE_DATETIME, Schema::TYPE_TIMESTAMP])) {
                return "                '" . "$column->name:datetime" . "',\n";
        } elseif (preg_match('/^is_/i', $column->name)) {
                return "                '" . "$column->name:boolean" . "',\n";
        } elseif ($column->name === 'status_id') {
                return "                [
                    'attribute' => 'status_id',
                    'value' => ArrayHelper::getValue(\$model, 'status.fullName'),
                ]" . ",\n";
        } elseif ($column->name === 'user_id') {
                return "                [
                    'attribute' => 'user_id',
                    'value' =>  ArrayHelper::getValue(\$model, 'user.fullName'),
                ]" . ",\n";
        } elseif (in_array($column->name, ['file_type_id', ])) {
                $relName = lcfirst(Inflector::id2camel(str_replace('_id', '', $column->name), '_'));
                return "                [
                    'attribute' => '$column->name',
                    'value' =>  ArrayHelper::getValue(\$model, '$relName.fullName'),
                ]" . ",\n";
        }

        $format = $this->generateColumnFormat($column);
        return "                '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
    }

    /**
     * Generates search conditions
     * @return array
     */
    public function generateSearchConditions()
    {
        $columns = [];
        if (($table = $this->getTableSchema()) === false) {
            $class = $this->modelClass;
            /* @var $model \yii\base\Model */
            $model = new $class();
            foreach ($model->attributes() as $attribute) {
                $columns[$attribute] = 'unknown';
            }
        } else {
            foreach ($table->columns as $column) {
                $columns[$column->name] = $column->type;
            }
        }

        $likeConditions = [];
        $hashConditions = [];
        foreach ($columns as $column => $type) {
            switch ($type) {
                case Schema::TYPE_TINYINT:
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_BOOLEAN:
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $hashConditions[] = "'{$column}' => \$this->{$column},";
                    break;
                case Schema::TYPE_DATE:
                    $likeKeyword = $this->getClassDbDriverName() === 'pgsql' ? 'ilike' : 'like';
                    $likeConditions[] = "->andFilterWhere(['{$likeKeyword}', new Expression('DATE_FORMAT({$column}, \"%d.%m.%Y\")'), \$this->{$column}])";
                    break;
                case Schema::TYPE_TIME:
                    $likeKeyword = $this->getClassDbDriverName() === 'pgsql' ? 'ilike' : 'like';
                    $likeConditions[] = "->andFilterWhere(['{$likeKeyword}', new Expression(\'DATE_FORMAT({$column}, \"%k:%i:%s\")'), \$this->{$column}])";
                    break;
                case Schema::TYPE_DATETIME:
                    $likeKeyword = $this->getClassDbDriverName() === 'pgsql' ? 'ilike' : 'like';
                    $likeConditions[] = "->andFilterWhere(['{$likeKeyword}', new Expression('DATE_FORMAT({$column}, \"%d.%m.%Y %k:%i:%s\")'), \$this->{$column}])";
                    break;
                case Schema::TYPE_TIMESTAMP:
                    $likeKeyword = $this->getClassDbDriverName() === 'pgsql' ? 'ilike' : 'like';
                    $likeConditions[] = "->andFilterWhere(['{$likeKeyword}', new Expression('DATE_FORMAT({$column}, \"%d.%m.%Y %k:%i:%s\")'), \$this->{$column}])";
                    break;
                default:
                    $likeKeyword = $this->getClassDbDriverName() === 'pgsql' ? 'ilike' : 'like';
                    $likeConditions[] = "->andFilterWhere(['{$likeKeyword}', '{$column}', \$this->{$column}])";
                    break;
            }
        }

        $conditions = [];
        if (!empty($hashConditions)) {
            $conditions[] = "\$query->andFilterWhere([\n"
                . str_repeat(' ', 12) . implode("\n" . str_repeat(' ', 12), $hashConditions)
                . "\n" . str_repeat(' ', 8) . "]);\n";
        }
        if (!empty($likeConditions)) {
            $conditions[] = "\$query" . implode("\n" . str_repeat(' ', 12), $likeConditions) . ";\n";
        }

        return $conditions;
    }
}
