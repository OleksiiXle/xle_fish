<?php
namespace app\modules\adminx\models\form;

use app\modules\adminx\models\UserM;
use app\modules\adminx\models\UserData;


/**
 * @deprecated
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
            [['status', ], 'integer'],
/*
            [['username' , 'email'], 'required'],
            [['first_name', 'middle_name', 'last_name',
                'email', ], 'string', 'max' => 255],
            [['first_name', 'middle_name', 'last_name'],  'match', 'pattern' => self::USER_NAME_PATTERN,
                'message' => self::USER_NAME_ERROR_MESSAGE],
*/

        ];
    }

    /**
     * @return $this
     */
    public function updateUser()
    {
        if ($this->validate()) {
            $user = self::findOne($this->id);
            $user->status = $this->status;

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
