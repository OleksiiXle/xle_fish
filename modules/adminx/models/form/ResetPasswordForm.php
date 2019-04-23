<?php

namespace app\modules\adminx\models\form;

use app\modules\adminx\models\User;
use app\modules\adminx\models\UserM;
use yii\base\Model;
use yii\base\InvalidParamException;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{

    public $password;
    public $retypePassword;

    /**
     * @var \app\models\User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {

        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }

        $this->_user = User::findByPasswordResetToken($token);

        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['retypePassword'], 'compare', 'compareAttribute' => 'password', 'message' => 'Новый пароль и подтверждение не совпадают'],
            [['password', 'retypePassword',   ], 'match', 'pattern' => UserM::USER_PASSWORD_PATTERN,
                'message' => UserM::USER_PASSWORD_ERROR_MESSAGE],

        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Новый пароль',
            'retypePassword' => 'Подтверждение пароля',
        ];
    }


    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        return $user->save(false);
    }

}