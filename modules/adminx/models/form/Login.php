<?php

namespace app\modules\adminx\models\form;

use app\components\AccessHelper;
use app\modules\adminx\models\UserM;
use Yii;

/**
 * Login form
 */
class Login extends UserM
{
    const USER_PASSWORD_PATTERN       = '/^[a-zA-Z0-9_]+$/ui'; //--маска для пароля
    const USER_PASSWORD_ERROR_MESSAGE = 'Припустимі символи - латиниця, цифри, _'; //--сообщение об ошибке

    public $username;
    public $password;
    public $rememberMe = true;
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'match', 'pattern' => UserM::USER_PASSWORD_PATTERN,
                'message' => UserM::USER_PASSWORD_ERROR_MESSAGE],

            ['username',  'string', 'min' => 3, 'max' => 50],
            ['password',  'string', 'min' => 3, 'max' => 50],

            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }


    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        $r=1;
        if ($this->validate()) {
            $ret = Yii::$app->getUser()->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            //-- запись в сессию корневых подразделений, доступных пользователю и их потомков с предварительной их очисткой
          //  AccessHelper::saveUserPermissionsToSession();
         //   AccessHelper::saveUserDepartmentsToSession(true);
            return $ret;
        } else {
            return false;
        }
    }

}
