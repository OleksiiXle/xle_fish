<?php
namespace app\modules\adminx\models\form;

use app\modules\adminx\models\UserM;
use app\modules\adminx\models\UserData;


/**
 * Update form
 */
class Update extends UserM
{
    public $first_name;
    public $middle_name;
    public $last_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username' , 'email'], 'required'],
            [['first_name', 'middle_name', 'last_name',
                'email', ], 'string', 'max' => 255],
            [['first_name', 'middle_name', 'last_name'],  'match', 'pattern' => self::USER_NAME_PATTERN,
                'message' => self::USER_NAME_ERROR_MESSAGE],

        ];
    }

    /**
     * @return $this
     */
    public function updateUser()
    {
        if ($this->validate()) {
            $user = self::findOne($this->id);
            $user->email = $this->email;
            $user->first_name = $this->first_name;
            $user->middle_name = $this->middle_name;
            $user->last_name = $this->last_name;

            if ($user->save()) {
                $userData = UserData::findOne(['user_id' => $this->id]);
                $userData->first_name = $user->first_name;
                $userData->middle_name = $user->middle_name;
                $userData->last_name = $user->last_name;

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
