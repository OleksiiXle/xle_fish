<?php
namespace app\modules\adminx\models\filters;

use app\models\dictionary\Dictionary;
use app\modules\adminx\models\UserData;
use app\modules\adminx\models\UserM;
use yii\base\Model;

class UserActivityFilter extends Model
{
    public $activityInterval;

    public $username;
    public $userDirection; //userDirection
    public $userGunp;
    public $userFam; //last_name


    public function rules()
    {
        return [
            [['activityInterval', 'userDirection', 'userGunp'], 'integer'],
            [['username', 'first_name'], 'string', 'min' => 3, 'max' => 32],
            [['username'], 'match', 'pattern' => UserM::USER_PASSWORD_PATTERN,
                'message' => UserM::USER_PASSWORD_ERROR_MESSAGE],
            [['userFam'],  'match', 'pattern' => UserM::USER_NAME_PATTERN,
                'message' => UserM::USER_NAME_ERROR_MESSAGE],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activityInterval' => 'Змінити інтервал',
            'username' => 'Логін',
            'userDirection' => 'Напрямок',
            'userGunp' => 'ГУНП',
            'userFam' => 'Прізвище',
        ];
    }


    public function getQuery($params = null){

        $query = UserData::find();

        if (!empty($this->username)){
            $query->joinWith(['userM']);
        }

        if (!empty($this->userGunp)){
            $query->joinWith(['userDepartments']);
        }

        if (!empty($this->userFam)){
            $query->andWhere(['LIKE', 'user_data.last_name', $this->userFam ]);
        }

        if (!empty($this->userDirection)){
            $query->andWhere(['user_data.direction' => $this->userDirection]);
        }

        if (!empty($this->userGunp)){
            $query->andWhere(['user_department.department_id' => $this->userGunp]);
        }

        if (!empty($this->username)){
            $query->andWhere(['LIKE', 'user.username', $this->username]);
        }


        if (!empty($this->activityInterval)) {
            $timeFix = time() - $this->activityInterval;
        } else {
            $timeFix = time() - 3600;
        }
        $query->andWhere(['>', 'user_data.last_rout_time', $timeFix]);





        $query->orderBy('user_data.last_rout_time DESC');
       // $r = $query->createCommand()->getSql();


        return $query;

    }
}