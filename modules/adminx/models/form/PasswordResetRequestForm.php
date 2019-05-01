<?php

namespace app\modules\adminx\models\form;

use app\modules\adminx\models\User;
use Yii;
use yii\base\Model;

class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\modules\adminx\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }
        $_from = Yii::$app->params['adminEmail'];
        $_to = $this->email;
        $_mailer = Yii::$app->smtpXleMailer;
        $ret = $_mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom($_from)
            ->setTo($_to)
            ->setSubject('Password reset for ')
            ->send();
        return $ret;
    }

}