<?php

namespace pravda1979\core\models;

use Yii;
use pravda1979\core\components\validators\StringFilter;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\web\DbSession;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $name
 * @property int $user_state
 * @property string $note
 * @property int $status_id
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Status[] $statuses
 * @property Status $status
 * @property User $user
 * @property User[] $users
 * @property array $userRights
 * @property string $userRightsAsString
 */
class User extends \pravda1979\core\components\core\ActiveRecord implements IdentityInterface
{
    const STATE_DELETED = 0;
    const STATE_ACTIVE = 1;

    private $_userRights = null;

    public $password;
    public $password_repeat;
    public $current_password;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        /** @var \pravda1979\core\Module $module */
        $module = Yii::$app->getModule('core');
        return "{{%" . $module->tableNames["user"] . "}}";
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'name', 'note'],
                StringFilter::className()
            ],
            [['password_reset_token', 'name', 'note', 'updated_at'], 'default', 'value' => null],
            [['username', 'email', 'user_state', 'name'], 'required'],
            [['user_state', 'status_id', 'user_id'], 'integer'],
            [['note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'name'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['user_state', 'status_id', 'user_id'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [
                ['status_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Status::className(),
                'targetAttribute' => ['status_id' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id']
            ],

            [['password', 'password_repeat', 'current_password', 'userRights'], 'safe'],
            ['password', 'compare', 'on' => ['create', 'update', 'profile'], 'compareAttribute' => 'password_repeat'],
            ['password_repeat', 'compare', 'on' => ['create', 'update'], 'compareAttribute' => 'password'],
            ['current_password', 'validateCurrentPassword', 'on' => 'profile'],
            [['name', 'email', 'current_password'], 'required', 'on' => 'profile'],
        ];
    }

    /**
     * Заполняем значения по умолчанию
     */
    public function setDefaultValues()
    {
        if ($this->scenario == 'create') {
            if (empty($this->user_state)) {
                $this->user_state = self::STATE_ACTIVE;
            }
        }
    }

    /**
     * Validates the current password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateCurrentPassword($attribute, $params)
    {
        if (!$this->validatePassword($this->current_password)) {
            $this->addError($attribute, Yii::t('User', 'Incorrect current password'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('User', 'ID'),
            'username' => Yii::t('User', 'Username'),
            'auth_key' => Yii::t('User', 'Auth Key'),
            'password_hash' => Yii::t('User', 'Password Hash'),
            'password_reset_token' => Yii::t('User', 'Password Reset Token'),
            'email' => Yii::t('User', 'Email'),
            'name' => Yii::t('User', 'Name'),
            'user_state' => Yii::t('User', 'User State'),
            'note' => Yii::t('User', 'Note'),
            'status_id' => Yii::t('User', 'Status ID'),
            'user_id' => Yii::t('User', 'User ID'),
            'created_at' => Yii::t('User', 'Created At'),
            'updated_at' => Yii::t('User', 'Updated At'),
            'userRights' => Yii::t('User', 'User Rights'),
            'password' => Yii::t('User', 'Password'),
            'password_repeat' => Yii::t('User', 'Password Repeat'),
            'current_password' => Yii::t('User', 'Current Password'),
            'districtAdministrations' => Yii::t('User', 'District Administrations')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * {@inheritdoc}
     * @return \pravda1979\core\queries\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \pravda1979\core\queries\UserQuery(get_called_class());
    }


    /**
     * ***************************************************************************************************************
     * ************** Функции, относящиеся к идентификации пользователя **********************************************
     * ***************************************************************************************************************
     */


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'user_state' => self::STATE_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()->where(['username' => $username, 'user_state' => self::STATE_ACTIVE])->real()->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'user_state' => self::STATE_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Возвращает присвоенные пользователю роли ($assignedOnly = true) или список всех доступных ролей для присвоения ($assignedOnly = false)
     *
     * @param bool $assignedOnly
     * @return array
     */
    public function getUserRights($assignedOnly = false)
    {
        $result = $this->_userRights;
        if ($result === null || $assignedOnly == true) {
            $authManager = Yii::$app->authManager;
            $roles = $authManager->getRolesByUser($this->id);
            $result = ArrayHelper::getColumn($roles, 'name', false);
            if ($assignedOnly == false) {
                sort($result);
                $this->_userRights = $result;
            }
        }
        return $result;
    }

    /**
     * @param $rights
     */
    public function setUserRights($rights)
    {
        if (empty($rights)) {
            $rights = [];
        }
        $rights = is_array($rights) ? $rights : [$rights];
        sort($rights);
        $this->_userRights = $rights;
    }

    /**
     * Возвращает список всех ролей
     * @return array
     */
    public static function getListUserRights()
    {
        $result = [];
        $authManager = Yii::$app->authManager;
        $roles = $authManager->getRoles();
        foreach ($roles as $key => $role) {
            $result[$role->name] = Yii::t('role', $role->name);
        }
        asort($result);
        return $result;
    }

    /**
     * Сохранение присвоенных ролей в БД
     * @return array
     */
    public function assignUserRights()
    {
        $authManager = Yii::$app->authManager;

        // Delete unused from post
        foreach ($this->getUserRights(true) as $right) {
            if (!in_array($right, $this->_userRights) && $authManager->getAssignment($right, $this->id) != null) {
                $role = $authManager->getRole($right);
                if ($role !== null) {
                    $authManager->revoke($role, $this->id);
                }
            }
        }

        //Save from post
        foreach ($this->_userRights as $right) {
            $role = $authManager->getRole($right);
            if ($role !== null) {
                if ($authManager->getAssignment($role->name, $this->id) === null) {
                    $authManager->assign($role, $this->id);
                }
            }
        }

        return $this->getUserRights();
    }

    /**
     * @inheritdoc
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->scenario != 'profile') {
            $this->assignUserRights();
        }

        if ($insert) {
            $this->sendUserInfo();
        }
    }

    /**
     *
     */
    public function sendUserInfo()
    {
        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = Yii::$app->mailer;
        $mailer->getView()->theme = Yii::$app->view->theme;

        $messages[] = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'register-html'/*, 'text' => 'register-text'*/],
                ['model' => $this]
            )
            ->setFrom([Yii::$app->params['robotEmail'] => Yii::t('app', 'App Name')])
            ->setTo($this->email)
            ->setSubject('Регистрация в системе "' . Yii::t('app', 'App Name') . '"');

        Yii::$app->mailer->sendMultiple($messages);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->isNewRecord) {
            $this->createNewPassword();
        }

        $this->forceReLogin();

        return true;
    }

    public function createNewPassword()
    {
        $this->password = Yii::$app->security->generateRandomString(8);
        $this->password_repeat = $this->password;
        $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
    }

    /**
     * Generating new authKey and delete user session, if need it
     */
    public function forceReLogin()
    {
        $requireReLogin = false;

        if (!empty($this->password) && !Yii::$app->security->validatePassword($this->password, $this->password_hash)) {
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            $requireReLogin = true;
        }
        if (array_key_exists('username', $this->dirtyAttributes)) {
            $requireReLogin = true;
        }
        if (array_key_exists('user_state', $this->dirtyAttributes)) {
            $requireReLogin = true;
        }
        if (array_key_exists('fixed_status_id', $this->dirtyAttributes)) {
            $requireReLogin = true;
        }

        // If need re-login
        if ($requireReLogin) {
            $this->generateAuthKey();
            // Delete user session
            if (Yii::$app->session instanceof DbSession) {
                Yii::$app->db->createCommand()->delete(Yii::$app->session->sessionTable,
                    ['user_id' => $this->id])->execute();
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getBackupLabels()
    {
        $result = array_merge(parent::getBackupLabels(), [
            'user_state' => Yii::$app->formatter->asBoolean($this->user_state),
            'userRights' => $this->userRightsAsString,
            'password_hash' => '********',
        ]);
        if ($this->hasMethod('getAssignedDistrictAdministrationsText')) {
            $result['districtAdministrations'] = $this->getAssignedDistrictAdministrationsText();
        }
        return $result;
    }

    public function getUserRightsAsString()
    {
        $rightsArray = [];
        foreach ($this->userRights as $right) {
            $rightsArray[] = Yii::t('role', $right);
        }
        asort($rightsArray);
        return implode(', ', $rightsArray);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['profile'] = ['name', 'email', 'password', 'password_repeat', 'note', 'current_password'];
        return $scenarios;
    }
}
