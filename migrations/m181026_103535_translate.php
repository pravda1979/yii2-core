<?php

use pravda1979\core\components\migration\Migration;

class m181026_103535_translate extends Migration
{
    /**
     * @inheritdoc
     */
    public function getTranslates($translates = [])
    {
        $translates = [
            'ru-RU' => [
                'menu.main' => [
                    'menu.main' => 'Главное меню',
                ],
                'models' => [
                    'models' => 'Модели',
                ],
                'role' => [
                    'role' => 'Роль',
                    'viewer' => ':: Наблюдатель',
                    'editor' => ':: Редактор',
                    'admin' => ':: Администратор',
                ],
                'app' => [
                    'app' => 'Приложение',
                    'App Name' => null,
                    'Actions' => 'Действия',
                    'Close' => 'Закрыть',
                    'Reset filter' => 'Сброс фильтра',
                    'Cancel' => 'Отмена',
                    'Search' => 'Поиск',
                    'Find' => 'Найти',
                    'Deleted record' => 'Удаленная запись',
                    'Draft record' => 'Черновик',
                    'Active record' => 'Активная запись',
                    'The requested page does not exist.' => 'Запрашиваемая Вами страница не найдена.',
                ],
                'LoginForm' => [
                    'LoginForm' => 'Форма входа',
                    'Username' => 'Логин',
                    'Password' => 'Пароль',
                    'Remember Me' => 'Запомнить меня',
                    'Login' => 'Вход в систему',
                    'Sign In' => 'Войти',
                    'Sign in to start your session' => 'Укажите свои данные для входа',
                    'Incorrect username or password' => 'Неверно введены логин и/или пароль',
                ],
                'SourceMessage' => [
                    'Category' => 'Категория',
                    'Message' => 'Текст',
                ],
                'Message' => [
                    'Language' => 'Язык',
                    'Translation' => 'Перевод',
                ],
            ],
        ];

        $this->route = 'source-message';
        $this->table_name = 'source_message';
        $this->modelNames = [
            'singular' => 'Исходный текст',
            'plural' => 'Исходные тексты',
            'accusative' => 'исходный текст', // Винительный падеж (кого, что)
            'genitive' => 'исходного текста', // Родительный падеж (кого, чего)
        ];
        $translates = parent::getTranslates($translates);

        $this->route = 'message';
        $this->table_name = 'message';
        $this->modelNames = [
            'singular' => 'Перевод',
            'plural' => 'Переводы',
            'accusative' => 'перевод', // Винительный падеж (кого, что)
            'genitive' => 'перевода', // Родительный падеж (кого, чего)
        ];
        $translates = parent::getTranslates($translates);

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