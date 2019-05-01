<?php

namespace app\modules\adminx\models\form;

use app\modules\adminx\models\UserM;
use Yii;
use yii\base\Model;

class ChangePassword extends Model
{
    public $oldPassword;
    public $newPassword;
    public $retypePassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'retypePassword'], 'required'],
            [['oldPassword'], 'validatePassword'],
            [['retypePassword', 'oldPassword' , 'newPassword'],  'string', 'min' => 3, 'max' => 20],
            [['retypePassword'], 'compare', 'compareAttribute' => 'newPassword'],
            [['oldPassword', 'retypePassword',  'newPassword' ], 'match', 'pattern' => UserM::USER_PASSWORD_PATTERN,
                'message' => \Yii::t('app', UserM::USER_PASSWORD_ERROR_MESSAGE)],

        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'oldPassword' =>  \Yii::t('app', 'Старий пароль'),
            'newPassword' => \Yii::t('app', 'Новый пароль'),
            'retypePassword' => \Yii::t('app', 'Подтверждение пароля'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        if (!$user || !$user->validatePassword($this->oldPassword)) {
            $this->addError('oldPassword', \Yii::t('app', 'Неверный старый пароль'));
        }
    }

    /**
     * Change password.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function change()
    {
        if ($this->validate()) {
            /* @var $user User */
            $user = Yii::$app->user->identity;
            $user->setPassword($this->newPassword);
            $user->generateAuthKey();
            if ($user->save()) {
                return true;
            }
        }

        return false;
    }
}
