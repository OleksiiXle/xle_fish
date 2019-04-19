<?php
namespace app\modules\adminx\models\form;

use app\modules\adminx\models\UserM;
use Yii;
use app\modules\adminx\models\User;
use app\modules\adminx\models\UserData;
use yii\base\Model;

/**
 * Signup form
 */
class Signup extends UserM
{
    public $first_name;
    public $middle_name;
    public $last_name;
    public $phone;
    public $spec_document;
    public $personal_id;
    public $job_name;
    public $direction;

    public $password;
    public $retypePassword;
    public $oldPassword;
    public $newPassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username' , 'email', 'first_name', 'middle_name', 'last_name', 'password', 'retypePassword'], 'required'],
            [['retypePassword'], 'compare', 'compareAttribute' => 'password'],
            [['first_name', 'middle_name', 'last_name', 'job_name', 'phone',
                'email', ], 'string', 'max' => 255],
            ['spec_document',  'match', 'pattern' => '/^[0-9]{7}$/', 'message' => 'Введіть 7 цифр без пробілів!' ],
            [['username', 'password', 'oldPassword', 'retypePassword',  'newPassword' ], 'match', 'pattern' => self::USER_PASSWORD_PATTERN,
                'message' => self::USER_PASSWORD_ERROR_MESSAGE],
            [['first_name', 'middle_name', 'last_name'],  'match', 'pattern' => self::USER_NAME_PATTERN,
                'message' => self::USER_NAME_ERROR_MESSAGE],
            ['phone',  'match', 'pattern' => self::USER_PHONE_PATTERN,
                'message' => self::USER_PHONE_ERROR_MESSAGE],

        ];
    }

    /**
     * @return $this
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new self();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->password = $this->password;
            $user->retypePassword = $this->retypePassword;
            $user->first_name = $this->first_name;
            $user->middle_name = $this->middle_name;
            $user->last_name = $this->last_name;

            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                $userData = new UserData();
                $userData->user_id = $user->id;
                $userData->first_name = $this->first_name;
                $userData->middle_name = $this->middle_name;
                $userData->last_name = $this->last_name;
                if ($userData->save()){
                    return $user;
                } else {
                    foreach ($userData->getErrors() as $key => $err){
                        $this->addError('username', $err[0] );
                    }
                }
            }else {
                $d = $user->getErrors();
            }
        }

        return null;
    }




}
