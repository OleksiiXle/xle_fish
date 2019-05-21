<?php
namespace app\modules\adminx\models\form;

use app\modules\adminx\models\UserM;
use Yii;
use app\modules\adminx\models\User;
use app\modules\adminx\models\UserData;
use yii\base\Model;
use yii\db\Exception;

/**
 * Signup form
 */
class Signup extends UserM
{
    public $first_name;
    public $middle_name;
    public $last_name;

    public $password;
    public $retypePassword;
    public $oldPassword;
    public $newPassword;

    public $reCaptcha;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username' , 'email', 'first_name', 'middle_name', 'last_name', 'password', 'retypePassword'], 'required'],
            [['retypePassword'], 'compare', 'compareAttribute' => 'password'],
            [['first_name', 'middle_name', 'last_name',
                'email', ], 'string', 'max' => 255],
            [['username', 'password', 'oldPassword', 'retypePassword',  'newPassword' ], 'match', 'pattern' => self::USER_PASSWORD_PATTERN,
                'message' => \Yii::t('app', self::USER_PASSWORD_ERROR_MESSAGE)],
            [['first_name', 'middle_name', 'last_name'],  'match', 'pattern' => self::USER_NAME_PATTERN,
                'message' => \Yii::t('app', self::USER_NAME_ERROR_MESSAGE)],

         //   [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => '6LfU-p8UAAAAAJIytAMOw7CMnd8K5HmVaP0vT49-'],

        ];

    }

    /**
     * @return boolean
     */
    public function signup($byEmail = false)
    {
        $user = new self();

        $user->username = $this->username;
        $user->email = $this->email;
        $user->password = $this->password;
        $user->retypePassword = $this->retypePassword;
        $user->first_name = $this->first_name;
        $user->middle_name = $this->middle_name;
        $user->last_name = $this->last_name;
        $user->reCaptcha = $this->reCaptcha;
        if ($byEmail){
            $user->email_confirm_token = \Yii::$app->security->generateRandomString();
            $user->status = UserM::STATUS_WAIT;
        } else {
            $user->status = UserM::STATUS_ACTIVE;
        }


        $user->setPassword($this->password);
        $user->generateAuthKey();
        if ($user->save()) {
            $userData = new UserData();
            $userData->user_id = $user->id;
            $userData->first_name = $this->first_name;
            $userData->middle_name = $this->middle_name;
            $userData->last_name = $this->last_name;
            if ($userData->save()){
                $userSent = User::findOne($user->id);
                return $this->sentEmailConfirm($userSent);
            } else {
                foreach ($userData->getErrors() as $key => $err){
                    $this->addError('username', $err[0] );
                }
            }
        } else {
            $this->addErrors( $user->getErrors());
        }

        return false;
    }

    //************************************************************************************** по Email


    public function sentEmailConfirm($user)
    {
        try{
            $email = $user->email;

            $mailer = Yii::$app->smtpXleMailer;

            $sent = $mailer
                ->compose(
                    ['html' => 'user-signup-comfirm-html', 'text' => 'user-signup-comfirm-text'],
                    ['user' => $user])
                ->setTo($email)
                ->setFrom(\Yii::$app->params['adminEmail'])
                ->setSubject('Confirmation of registration')
                ->send();

            if (!$sent) {
                $this->addError('email', 'Ошибка отправки токена');
                return false;
            }
        } catch (\Swift_TransportException $e){
            $this->addError('email', $e->getMessage());
            return false;
        }
        return true;
    }


    public function confirmation($token)
    {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token.');
        }

        $user = User::findOne(['email_confirm_token' => $token]);
        if (!$user) {
            throw new \DomainException('User is not found.');
        }

        $user->email_confirm_token = null;
        $user->status = UserM::STATUS_ACTIVE;

        $auth = \Yii::$app->authManager;
        $userRole = $auth->getRole(\Yii::$app->configs->userDefaultRole);
        if (!empty($userRole)){
            \Yii::$app->authManager->assign($userRole, $user->id);
        }

        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }

        if (!\Yii::$app->getUser()->login($user)) {
            throw new \RuntimeException('Error authentication.');
        }
    }





}
