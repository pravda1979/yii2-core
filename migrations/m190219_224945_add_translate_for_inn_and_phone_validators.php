<?php

use pravda1979\core\components\migration\Migration;

class m190219_224945_add_translate_for_inn_and_phone_validators extends Migration
{
    /**
     * @inheritdoc
     */
    public function getTranslates($translates = [])
    {
        $translates = [
            'ru-RU' => [
                'InnValidator' => [
                    'The value in the "{inn}" field should contain either 8 or 10 digits, or a series and passport number (For example: Ukraine - "АА 123456", Russia and DNR - "0123 123456"'
                    => 'Значение в поле "{inn}" должно содержать либо 8 либо 10 цифр, или серию и номер паспорта (Например: Украина - "АА 123456", Россия и ДНР - "0123 123456"',
                ],
                'PhoneValidator' => [
                    'Wrong phone format. Allowed formats: +38(XXX)XXX-XX-XX, +7(XXX)XXX-XX-XX or +8(XXX)XXX-XX-XX, где X - digit. Brackets, dashes and spaces are optional.'
                    => 'Неверный формат номера телефона. Разрешенные варианты: +38(XXX)XXX-XX-XX, +7(XXX)XXX-XX-XX либо +8(XXX)XXX-XX-XX, где X - цифра. Скобки, черточки и пробелы необязательны.',
                    'Phone numbers must be separated by commas.'
                    => 'Номера телефонов должны быть разделены запятой.',
                ],
            ],
        ];
        return $translates;
    }

    public function safeUp()
    {
        $this->createTranslates();
    }

    public function safeDown()
    {
        $this->deleteTranslates();
    }
}