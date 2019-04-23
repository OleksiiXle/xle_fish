<?php

namespace app\modules\adminx\models;

use app\models\Functions;
use Yii;
use yii\db\ActiveRecord;

/**
 * User - модель с правилами, геттерами, сеттерами и пр. данными
 *
 */
class UserM extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 10;

    const USER_NAME_PATTERN           = '/^[А-ЯІЇЄҐ]{1}[а-яіїєґ\']+([-]?[А-ЯІЇЄҐ]{1}[а-яіїєґ\']+)?$/u'; //--маска для нимени
    const USER_NAME_ERROR_MESSAGE     = 'Використовуйте українські літери, починаючи із великої. 
                                         Апостроф - в англійській розкладці на букві є. Подвійні імена - через тире!'; //--сообщение об ошибке
    const USER_PASSWORD_PATTERN       = '/^[a-zA-Z0-9_]+$/ui'; //--маска для пароля
    const USER_PASSWORD_ERROR_MESSAGE = 'Припустимі символи - латиниця та цифри'; //--сообщение об ошибке
    const USER_PHONE_PATTERN       = '/^[0-9, \-)(+]+$/ui'; //--маска для пароля
    const USER_PHONE_ERROR_MESSAGE = 'Припустимі символи - латиниця та цифри'; //--сообщение об ошибке


    public $first_name;
    public $middle_name;
    public $last_name;

    public $password;
    public $retypePassword;
    public $oldPassword;
    public $newPassword;
    public $rememberMe = true;

    private $_user = false;
    private $_created_at_str;
    private $_updated_at_str;
    private $_time_login_str;
    private $_time_logout_str;
    private $_time_session_expire_str;
    private $_userRoles;
    private $_lastRout;
    private $_lastRoutTime;
    private $_nameFam; //-- фамилия
    private $_nameNam;//--имя
    private $_nameFat;//-- отчество

    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['email', 'filter', 'filter' => 'trim'],
            //----------------------------------------------------------------------- ТИПЫ ДАННЫХ, РАЗМЕР
            [['status', 'created_at', 'updated_at',], 'integer'],

            ['rememberMe', 'boolean'],
            ['email', 'email'],

            [['username', 'auth_key'], 'string', 'min' => 5, 'max' => 32],
            [['password', 'retypePassword', 'oldPassword' , 'newPassword'],  'string', 'min' => 3, 'max' => 20],
            [['first_name', 'middle_name', 'last_name',
                'password_hash', 'password_reset_token', 'email', ], 'string', 'max' => 255],

            //------------------------------------------------------------------------ УНИКАЛЬНОСТЬ
            ['username', 'unique', 'targetClass' => 'app\modules\adminx\models\UserM',
                'message' => 'This username has already been taken.' ],
            ['email', 'unique', 'targetClass' => 'app\modules\adminx\models\UserM',
                 'message' => 'This email address has already been taken.'],

            //------------------------------------------------------------------------ МАСКИ ВВОДА
            [['username', 'password', 'oldPassword', 'retypePassword',  'newPassword' ], 'match', 'pattern' => self::USER_PASSWORD_PATTERN,
                'message' => self::USER_PASSWORD_ERROR_MESSAGE],
            [['first_name', 'middle_name', 'last_name'],  'match', 'pattern' => self::USER_NAME_PATTERN,
                'message' => self::USER_NAME_ERROR_MESSAGE],



            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'last_name' => 'Фамилия',
            'auth_key' => 'Ключ авторизации',
            'password' => 'Пароль',
            'password_hash' => 'Пароль',
            'oldPassword' => 'Старий пароль',
            'retypePassword' => 'Подтверждение пароля',
            'password_reset_token' => 'Токен зброса пароля',
            'email' => 'Email',
            'status' => 'Status',
            'created_at_str' => 'Создан',
            'updated_at_str' => 'Изменен',
            'lastRoutTime' => 'Последняя активность',
            'lastRout' => 'Последний роут',
            'nameFam' => 'Фамилия',
            'nameNam' => 'Имя',
            'nameFat' => 'Отчество',
            'userRoles' => 'Роли',
        ];
    }

//*********************************************************************************************** ДАННЫЕ СВЯЗАННЫХ ТАБЛИЦ

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserDatas()
    {
        return $this->hasOne(UserData::className(), ['user_id' => 'id']);
    }




//*********************************************************************************************** ГЕТТЕРЫ-СЕТТЕРЫ

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    public function getNameFam()
    {
        $this->_nameFam = (isset($this->userDatas->last_name)) ? $this->userDatas->last_name : '';
        return $this->_nameFam;
    }

    public function getNameNam()
    {
        $this->_nameNam = (isset($this->userDatas->first_name)) ? $this->userDatas->first_name : '';
        return $this->_nameNam;
    }

    public function getNameFat()
    {
        $this->_nameFat = (isset($this->userDatas->middle_name)) ? $this->userDatas->middle_name : '';
        return $this->_nameFat;
    }

    public function getLastRout()
    {
        $this->_lastRout = (isset($this->userDatas->last_rout)) ? $this->userDatas->last_rout : '';
        return $this->_lastRout;
    }

    public function getLastRoutTime()
    {
        $this->_lastRoutTime = (isset($this->userDatas->last_rout_time)) ? Functions::intToDateTime($this->userDatas->last_rout_time) : '';
        return $this->_lastRoutTime;
    }

    public function getCreated_at_str()
    {
        $this->_created_at_str = Functions::intToDateTime($this->created_at);
        return $this->_created_at_str;
    }

    public function getUpdated_at_str()
    {
        $this->_updated_at_str = Functions::intToDateTime($this->updated_at);
        return $this->_updated_at_str;
    }

    public function getTime_login_str()
    {
        $this->_time_login_str = Functions::intToDateTime($this->time_login);
        return $this->_time_login_str;
    }

    public function getTime_logout_str()
    {
        $this->_time_logout_str = Functions::intToDateTime($this->time_logout);
        return $this->_time_logout_str;
    }

    public function getTime_session_expire_str()
    {
        $this->_time_session_expire_str = Functions::intToDateTime($this->time_session_expire);
        return $this->_time_session_expire_str;
    }


    public function getUserRoles()
    {
        $this->_userRoles = '';
        $roles = \Yii::$app->authManager->getRolesByUser($this->id);
        if (isset($roles)){
            foreach ($roles as $role){
                $this->_userRoles .= $role->name . ' ';
            }
        }
        return $this->_userRoles;
    }




//********************************************************************************* МЕДОТЫ АВТОРИЗАЦИИ И АУТЕНТИФИКАЦИИ
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function validateOldPassword()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        if (!$user || !$user->validatePassword($this->oldPassword)) {
            $this->addError('oldPassword', 'Incorrect old password.3');
        }
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

//********************************************************************************** ПЕРЕОПРЕДЕЛЕННЫЕ МЕТОДЫ

    public function beforeSave($insert) {
        if ($insert){
            $this->created_at = time();
        }
        $this->updated_at = time();
        return parent::beforeSave($insert);
    }


//********************************************************************************** FOR RESET PASSWORD

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * +++ Finds user for reset password
     *
     * @param string $data
     * @return static|null
     */
    public static function findForReset($data)
    {
        //return var_dump(stripos($data, '@'));
        if(stripos($data, '@')){
            return static::findByEmail(trim($data));
        }else{
            return static::findByUsername(trim($data));
        }
    }

    /**
     * Set new Password for user.
     *
     * @return User|false the saved model or null if saving fails
     */
    public function resetPassword() {
        $this->setPassword($this->newPassword);
        $this->generateAuthKey();
        if ($this->save(false)) {
            return true;
        }
        return false;
    }

}
