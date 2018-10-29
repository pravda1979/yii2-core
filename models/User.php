<?php

namespace pravda1979\core\models;

use Yii;
use pravda1979\core\components\validators\StringFilter;
use yii\base\NotSupportedException;
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
 */
class User extends \pravda1979\core\components\core\ActiveRecord implements IdentityInterface
{
    const STATE_DELETED = 0;
    const STATE_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'name', 'note'], StringFilter::className()],
            [['password_reset_token', 'name', 'note', 'updated_at'], 'default', 'value' => null],
            [['username', 'auth_key', 'password_hash', 'email', 'user_state'], 'required'],
            [['user_state', 'status_id', 'user_id'], 'integer'],
            [['note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'name'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['user_state', 'status_id', 'user_id'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
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
        return static::findOne(['username' => $username, 'user_state' => self::STATE_ACTIVE]);
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
}