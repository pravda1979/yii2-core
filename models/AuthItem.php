<?php

namespace pravda1979\core\models;

use function Symfony\Component\Debug\Tests\testHeader;
use Yii;
use pravda1979\core\components\validators\StringFilter;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%core_auth_item}}".
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 *
 * // * @property CoreAuthAssignment[] $coreAuthAssignments
 * // * @property CoreAuthRule $ruleName
 * // * @property CoreAuthItemChild[] $coreAuthItemChildren
 * // * @property AuthItemChild[] $coreAuthItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 * @property string $typeName
 * @property string $nameT
 */
class AuthItem extends \pravda1979\core\components\core\ActiveRecord
{
    public static $TYPE_ROLE = 1;
    public static $TYPE_ROUTE = 2;

    private $_childrenItems = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        /** @var \pravda1979\core\Module $module */
        $module = Yii::$app->getModule('core');
        return "{{%" . $module->tableNames["auth_item"] . "}}";
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'rule_name', 'data'], StringFilter::className()],
            [['description', 'rule_name', 'data', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['type', 'created_at', 'updated_at'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
            [['name'], 'unique'],
            [['childrenItems'], 'safe'],
//            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => CoreAuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('AuthItem', 'Name'),
            'nameT' => Yii::t('AuthItem', 'Name'),
            'type' => Yii::t('AuthItem', 'Type'),
            'description' => Yii::t('AuthItem', 'Description'),
            'rule_name' => Yii::t('AuthItem', 'Rule Name'),
            'data' => Yii::t('AuthItem', 'Data'),
            'created_at' => Yii::t('AuthItem', 'Created At'),
            'updated_at' => Yii::t('AuthItem', 'Updated At'),
            'childrenItems' => Yii::t('AuthItem', 'Children Items'),
            'parentItems' => Yii::t('AuthItem', 'Parent Items'),
        ];
    }

//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getCoreAuthAssignments()
//    {
//        return $this->hasMany(CoreAuthAssignment::className(), ['item_name' => 'name']);
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getRuleName()
//    {
//        return $this->hasOne(CoreAuthRule::className(), ['name' => 'rule_name']);
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getCoreAuthItemChildren()
//    {
//        return $this->hasMany(CoreAuthItemChild::className(), ['parent' => 'name']);
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getCoreAuthItemChildren0()
//    {
//        return $this->hasMany(CoreAuthItemChild::className(), ['child' => 'name']);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        /** @var \pravda1979\core\Module $module */
        $module = Yii::$app->getModule('core');
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable("{{%" . $module->tableNames["auth_item_child"] . "}}", ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        /** @var \pravda1979\core\Module $module */
        $module = Yii::$app->getModule('core');
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable("{{%" . $module->tableNames["auth_item_child"] . "}}", ['child' => 'name']);
    }

    /**
     * {@inheritdoc}
     * @return \pravda1979\core\queries\AuthItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \pravda1979\core\queries\AuthItemQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $rules = [];
        $result = array_merge(parent::behaviors(), $rules);
        unset($result['backup']);
        return $result;
    }

    /**
     * Список типов правил доступа
     * @return array
     */
    public static function getListTypes()
    {
        return [
            static::$TYPE_ROLE => Yii::t('AuthItem', 'TYPE_ROLE'),
            static::$TYPE_ROUTE => Yii::t('AuthItem', 'TYPE_ROUTE'),
        ];
    }

    /**
     * Наименование типа
     * @return mixed
     */
    public function getTypeName()
    {
        $types = static::getListTypes();
        return $types[$this->type];
    }

    public function getNameT()
    {
        return $this->type == 2 ? $this->name : Yii::t('role', $this->name);
    }

    public function getFullName()
    {
        return $this->nameT;
    }

    public function getChildrenItems()
    {
        $result = $this->_childrenItems;
        if ($result === null) {
            $result = $this->getChildren()->select(['name']);
            $this->_childrenItems = $result;
        }
        return $result;
    }

    public function setChildrenItems($items)
    {
        if (empty($items))
            $items = [];
        $items = is_array($items) ? $items : [$items];
        $this->_childrenItems = $items;
        return $this->_childrenItems;
    }

    /**
     * @param array $params
     * @param array $select
     * @param array $order
     * @return \yii\db\ActiveQuery
     */
    public static function getListQuery($params = [], $select = [], $order = [])
    {
        if (empty($select))
            $select = ['id' => 'name', 'label' => 'name'];
        if (empty($order))
            $order = ['name' => SORT_ASC];

        $sqlQuery = static::find()
            ->orderBy($order)
            ->andFilterWhere($params)
            ->select($select);

        return $sqlQuery;
    }


    /**
     * @inheritdoc
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->saveChildren();
    }

    /**
     * Сохранение присвоенных ролей в БД
     * @return array
     */
    public function saveChildren()
    {
        /** @var \pravda1979\core\Module $module */
        $module = Yii::$app->getModule('core');

        // Clear childrenItems if this->type == $TYPE_ROUTE
        if ($this->type == static::$TYPE_ROUTE)
            $this->_childrenItems = [];

        // Delete unused from post
        foreach ($this->children as $child) {
            if (!in_array($child->name, $this->_childrenItems)) {
                Yii::$app->db->createCommand()->delete("{{%" . $module->tableNames["auth_item_child"] . "}}", ['parent' => $this->name, 'child' => $child->name])->execute();
            }
        }

        //Save from post
        $newItems = array_diff($this->_childrenItems, $this->getChildren()->select('name')->column());
        foreach ($newItems as $child) {
            Yii::$app->db->createCommand()->insert("{{%" . $module->tableNames["auth_item_child"] . "}}", ['parent' => $this->name, 'child' => $child])->execute();
        }

        return true;
    }
}
