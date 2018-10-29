<?php

namespace pravda1979\core\components\migration;

use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class Migration extends \yii\db\Migration
{
    public $route = '';
    public $table_name = '';
    public $parents = [];
    public $actions = [];
    public $modelNames = [
        'singular' => 'singular',
        'plural' => 'plural',
        'accusative' => 'accusative', // Винительный падеж (кого, что)
        'genitive' => 'genitive', // Родительный падеж (кого, чего)
    ];

    /**
     * Переводы названий
     *
     * @param array $translates
     * @return array
     */
    public function getTranslates($translates = [])
    {
        $className = Inflector::classify($this->table_name);
        $classesName = Inflector::pluralize($className);

        $singular = $this->modelNames['singular'];
        $plural = $this->modelNames['plural'];
        $accusative = $this->modelNames['accusative'];
        $genitive = $this->modelNames['genitive'];

        return ArrayHelper::merge([
            'ru-RU' => [
                'models' => [
                    $className => $singular,
                ],
                'menu' => [
                    Inflector::camel2words($classesName) => $plural,
                ],
                'role' => [
                    $className . ': editor' => $plural . ': Редактирование',
                    $className . ': viewer' => $plural . ': Просмотр',
                    $className . ': admin' => $plural . ': Полный доступ',
                ],
                $className => [
                    $className => $singular,
                    $classesName => $plural,
                    Inflector::camel2words($className) => $singular,
                    Inflector::camel2words($classesName) => $plural,

                    'Index' => 'Список ' . $plural,
                    'Create' => 'Добавить ' . $accusative,
                    'Save' => 'Сохранить ' . $accusative,
                    'Update' => 'Изменить ' . $accusative,
                    'Delete' => 'Удалить ' . $accusative,
                    'Find' => 'Найти ' . $accusative,
                    'View' => 'Просмотреть ' . $accusative,
                    'Viewing' => 'Просмотр ' . $genitive,
                    'Creating' => 'Добавление ' . $genitive,
                    'Updating' => 'Изменение ' . $genitive,
                    'Deleting' => 'Удаление ' . $genitive,
                    'Search' => 'Поиск ' . $genitive,

                    'ID' => 'ID',
                    'Name' => 'Наименование',
                    'Note' => 'Примечание',
                    'Status ID' => 'Статус записи',
                    'User ID' => 'Пользователь',
                    'Created At' => 'Дата создания',
                    'Updated At' => 'Дата изменения',
                ],
            ],
        ], $translates);
    }

    /**
     * Добавляем в БД переводы
     */
    public function createTranslates()
    {
        foreach ($this->translates as $languageName => $language) {
            foreach ($language as $categoryName => $category) {
                foreach ($category as $message => $translation) {

                    //Ищем сохраненный исходник
                    $itemId = (new \yii\db\Query())->select(['id'])->from('{{%source_message}}')
                        ->where(['category' => $categoryName, 'message' => $message])->scalar();

                    //Если не найден сохраненный исходник, добавляем новый
                    if (empty($itemId)) {
                        $this->insert('{{%source_message}}', ['category' => $categoryName, 'message' => $message]);
                        $itemId = (new \yii\db\Query())->select(['id'])->from('{{%source_message}}')
                            ->where(['category' => $categoryName, 'message' => $message])->scalar();
                    }

                    //Ищем сохраненный перевод
                    $translateId = (new \yii\db\Query())->select(['id'])->from('{{%message}}')
                        ->where(['id' => $itemId, 'language' => $languageName])->scalar();

                    // Добавляем перевод
                    if (empty($translateId)) {
                        $this->insert('{{%message}}', [
                            'id' => $itemId,
                            'language' => $languageName,
                            'translation' => $translation,
                        ]);
                    } else {
                        $this->update('{{%message}}',
                            [
                                'id' => $itemId,
                                'language' => $languageName,
                                'translation' => $translation,
                            ],
                            [
                                'id' => $itemId,
                                'language' => $languageName,
                            ]
                        );
                        echo "'category' => $categoryName, 'message' => $message";
                    }

                }
            }
        }
    }

    /**
     * Удаляем переводы из БД
     */
    public function deleteTranslates()
    {
        foreach ($this->translates as $languageName => $language) {
            foreach ($language as $categoryName => $category) {
                foreach ($category as $message => $translation) {

                    //Ищем сохраненный исходник
                    $itemId = (new \yii\db\Query())->select(['id'])->from('{{%source_message}}')
                        ->where(['category' => $categoryName, 'message' => $message])->scalar();

                    //Если не найден сохраненный исходник, добавляем новый
                    if (empty($itemId)) $itemId = -100;

                    // Удаляем перевод
                    $this->delete('{{%message}}', [
                        'id' => $itemId,
                        'language' => $languageName,
//                        'translation' => $translation,
                    ]);

                    $countTranslates = (new \yii\db\Query())->from('{{%message}}')
                        ->where(['id' => $itemId])->count();

                    echo '$count' . ' ' . $countTranslates . "\n";
                    if ($countTranslates == 0) {
                        $this->execute("delete from {{%source_message}} where id = $itemId");
                    } else {
                        echo 'skip $source_message' . ' "$category" => "' . $message . "\"\n";
                    }
                }
            }
        }
    }

    public function createRbac()
    {
        $authManager = \Yii::$app->authManager;
        foreach ($this->actions as $roleName => $actions) {
            $role = $this->getRole($roleName);
            foreach ($actions as $action) {
                $route = (empty($this->route) ? "" : "/" . $this->route) . "/$action";
                $permission = $authManager->getPermission($route);
                if ($permission === null) {
                    $permission = $authManager->createPermission("$route");
                    $authManager->add($permission);
                }
                if (!$authManager->hasChild($role, $permission))
                    $authManager->addChild($role, $permission);
            }
        }
        foreach ($this->parents as $parentName => $childName) {
            if (is_array($childName)) {
                foreach ($childName as $childRole) {
                    $parent = $this->getRole($parentName);
                    $child = $this->getRole($childRole);
                    if (!$authManager->hasChild($parent, $child))
                        $authManager->addChild($parent, $child);
                }
            } else {
                $parent = $this->getRole($parentName);
                $child = $this->getRole($childName);
                if (!$authManager->hasChild($parent, $child))
                    $authManager->addChild($parent, $child);
            }
        }
    }

    public function deleteRbac()
    {
        foreach ($this->actions as $roleName => $role) {
            foreach ($role as $action) {
                $route = (empty($this->route) ? "" : "/" . $this->route) . "/$action";
                $this->execute("delete from {{%auth_item}} where name = '$route'");
            }
        }

        $this->deleteRoles($this->parents);
    }

    public function deleteRoles($roles = null)
    {
        foreach ($roles as $parentName => $childName) {
            if (is_array($childName)) {
                $this->deleteRoles($childName);
            } else {
                $parentName = $this->getDbRoleName($parentName);
                $childName = $this->getDbRoleName($childName);
                $countChild = $this->db->createCommand("select count(*) from {{%auth_item_child}} where parent='$childName'")->queryScalar();
                echo '$countChild' . ' ' . $countChild . "\n";
                if ($countChild == 0) {
                    $this->execute("delete from {{%auth_item}} where name = '$childName'");
                } else {
                    echo 'skip deleting $childName' . ' ' . $parentName . "\n";
                }

                if (in_array($parentName, ['viewer', 'editor', 'admin'])) {
                    // Не удаляем 3 основные роли
                    continue;
                } else {
                    // Удаляем неиспользуемы роли
                    $countParent = $this->db->createCommand("select count(*) from {{%auth_item_child}} where parent='$parentName'")->queryScalar();
                    echo '$countParent' . ' ' . $countParent . "\n";
                    if ($countParent == 0) {
                        $this->execute("delete from {{%auth_item}} where name = '$parentName'");
                    } else {
                        echo 'skip deleting $parentName' . ' ' . $parentName . "\n";
                    }
                }
            }
        }
    }

    /**
     * @param $roleName
     * @return null|\yii\rbac\Role
     */
    public function getRole($roleName)
    {
        $authManager = \Yii::$app->authManager;
        $dbRoleName = $this->getDbRoleName($roleName);
        $role = $authManager->getRole($dbRoleName);
        if ($role === null) {
            $role = $authManager->createRole($dbRoleName);
            $authManager->add($role);
        }
        return $role;
    }

    /**
     * @param $roleName
     * @return mixed
     */
    public function getDbRoleName($roleName)
    {
        $route = empty($this->route) ? '' : Inflector::id2camel($this->route);
        $dbRoleName = str_replace('$route', $route, $roleName);
        return $dbRoleName;
    }
}
