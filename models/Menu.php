<?php

namespace pravda1979\core\models;

use Yii;
use pravda1979\core\components\validators\StringFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property int $id
 * @property string $menu_id
 * @property string $label
 * @property string $icon
 * @property string $url
 * @property int $use_url_helper
 * @property int $visible
 * @property string $linkOptions
 * @property int $position
 * @property int $level
 * @property int $parent_id
 * @property string $note
 * @property int $status_id
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Menu $parent
 * @property Menu[] $children
 * @property Status $status
 * @property User $user
 */
class Menu extends \pravda1979\core\components\core\ActiveRecord
{
    public static $VISIBLE_CHECK_ACCESS = 1;
    public static $VISIBLE_GUEST = 10;
    public static $VISIBLE_AUTHORIZED = 20;
    public static $VISIBLE_ADMIN = 30;
    public static $VISIBLE_ALWAYS = 40;
    public static $VISIBLE_NEVER = 50;
    public static $VISIBLE_HAS_CHILDREN = 60;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_id', 'label', 'icon', 'url', 'linkOptions', 'note'], StringFilter::className()],
            [['menu_id', 'label', 'icon', 'url', 'use_url_helper', 'visible', 'linkOptions', 'position', 'level', 'parent_id', 'note', 'updated_at'], 'default', 'value' => null],
            [['use_url_helper', 'visible', 'position', 'level', 'parent_id', 'status_id', 'user_id'], 'integer'],
            [['label'], 'required'],
            [['linkOptions', 'note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['menu_id', 'label', 'icon', 'url'], 'string', 'max' => 255],
            [['use_url_helper', 'visible', 'position', 'level', 'parent_id', 'status_id', 'user_id'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['visible'], 'default', 'value' => static::$VISIBLE_CHECK_ACCESS],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('Menu', 'ID'),
            'menu_id' => Yii::t('Menu', 'Menu ID'),
            'label' => Yii::t('Menu', 'Label'),
            'icon' => Yii::t('Menu', 'Icon'),
            'url' => Yii::t('Menu', 'Url'),
            'use_url_helper' => Yii::t('Menu', 'Use Url Helper'),
            'visible' => Yii::t('Menu', 'Visible'),
            'linkOptions' => Yii::t('Menu', 'Link Options'),
            'position' => Yii::t('Menu', 'Position'),
            'level' => Yii::t('Menu', 'Level'),
            'parent_id' => Yii::t('Menu', 'Parent ID'),
            'note' => Yii::t('Menu', 'Note'),
            'status_id' => Yii::t('Menu', 'Status ID'),
            'user_id' => Yii::t('Menu', 'User ID'),
            'created_at' => Yii::t('Menu', 'Created At'),
            'updated_at' => Yii::t('Menu', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Menu::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Menu::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \pravda1979\core\queries\MenuQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \pravda1979\core\queries\MenuQuery(get_called_class());
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return (bool)$this->getChildren()->count();
    }

    /**
     * @param array $where
     * @return array
     */
    public static function getMenuItems($where = [])
    {
        $result = [];
        $items = static::find()
            ->andFilterWhere($where)
            ->real()->all();

        /** @var Menu $item */
        foreach ($items as $item) {
            if ($item->visible == static::$VISIBLE_ADMIN) {
                $visible = Yii::$app->user->can('admin');

            } elseif ($item->visible == static::$VISIBLE_ALWAYS) {
                $visible = true;

            } elseif ($item->visible == static::$VISIBLE_AUTHORIZED) {
                $visible = !Yii::$app->user->isGuest;

            } elseif ($item->visible == static::$VISIBLE_CHECK_ACCESS) {
                $visible = Yii::$app->user->can($item->url);

            } elseif ($item->visible == static::$VISIBLE_GUEST) {
                $visible = Yii::$app->user->isGuest;

            } elseif ($item->visible == static::$VISIBLE_NEVER) {
                $visible = false;

            } else {
                $visible = false;

            }
            $tmp = [
                'label' => Yii::t('menu.main', $item->label),
                'icon' => $item->icon,
                'url' => $item->use_url_helper ? Url::to([$item->url]) : $item->url,
                'visible' => $visible,
                'active' => (strpos($item->url, Yii::$app->controller->getUniqueId()) !== false),
            ];
            if ($item->hasChildren()) {
                $tmp['items'] = static::getMenuItems(['parent_id' => $item->id]);
                $tmp['visible'] = (array_search(true, ArrayHelper::getColumn($tmp['items'], 'visible')) !== false);
                $tmp['active'] = (array_search(true, ArrayHelper::getColumn($tmp['items'], 'active')) !== false);
            }
            $result[] = $tmp;
        }
        return $result;
    }

    /**
     * @param $menu_id
     * @return array
     */
    public static function getMenu($menu_id)
    {
        $result = static::getMenuItems(['and', ['=', 'menu_id', $menu_id], ['is', 'parent_id', new \yii\db\Expression('null')]]);
        return $result;
    }

    /**
     * @return array
     */
    public static function getListExistParents()
    {
        $result = static::find()
            ->andFilterWhere(['id' => static::find()->select(['parent_id'])->distinct()])
            ->select(['id', 'label'])
            ->real()
            ->asArray()->all();

        foreach ($result as &$item) {
            $item['label'] = Yii::t('menu.main', $item['label']);
        }

        return ArrayHelper::map($result, 'id', 'label');
    }

    public function getParentLabel()
    {
        return $this->parent ? Yii::t('menu.main', $this->parent->label) : null;
    }

    public static function getFullNameSql()
    {
        return 'label';
    }

    public function getFullName()
    {
        return $this->label;
    }

    /**
     * @return array
     */
    public static function getListVisible()
    {
        return [
            static::$VISIBLE_CHECK_ACCESS => Yii::t('Menu', 'VISIBLE_CHECK_ACCESS'),
            static::$VISIBLE_GUEST => Yii::t('Menu', 'VISIBLE_GUEST'),
            static::$VISIBLE_AUTHORIZED => Yii::t('Menu', 'VISIBLE_AUTHORIZED'),
            static::$VISIBLE_ADMIN => Yii::t('Menu', 'VISIBLE_ADMIN'),
            static::$VISIBLE_ALWAYS => Yii::t('Menu', 'VISIBLE_ALWAYS'),
            static::$VISIBLE_NEVER => Yii::t('Menu', 'VISIBLE_NEVER'),
            static::$VISIBLE_HAS_CHILDREN => Yii::t('Menu', 'VISIBLE_HAS_CHILDREN'),
        ];
    }

    public function getVisibleName()
    {
        $types = $this->getListVisible();
        return $types[$this->visible];
    }

}
