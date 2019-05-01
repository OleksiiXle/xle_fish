<?php
namespace app\modules\adminx\models\filters;

use app\models\dictionary\Dictionary;
use app\modules\adminx\models\UControl;
use app\modules\adminx\models\UserData;
use app\modules\adminx\models\UserM;
use yii\base\Model;

class UControlFilter extends Model
{
    const IP_PATTERN       = '/^[0-9 .]+$/ui'; //--маска для пароля
    const IP_ERROR_MESSAGE = 'Допустиные символы - цифры и точка'; //--сообщение об ошибке

    public $user_id;
    public $remote_ip;
    public $username;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['remote_ip', 'filter', 'filter' => 'trim'],
            ['username', 'filter', 'filter' => 'trim'],

            [['user_id'], 'integer'],
            [['remote_ip', 'username'], 'string', 'max' => 32],
            [['remote_ip',],  'match', 'pattern' => self::IP_PATTERN,
                'message' => \Yii::t('app', \Yii::t('app', self::IP_ERROR_MESSAGE))],
            [['username', ], 'match', 'pattern' => UserM::USER_PASSWORD_PATTERN,
                'message' => \Yii::t('app', UserM::USER_PASSWORD_ERROR_MESSAGE)],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [

            'user_id' => 'User ID',
            'remote_ip' => 'IP',
            'username' => 'Login',
        ];
    }



    public function getQuery($params = null)
    {
        $query = UControl::find();

        if (!empty($this->username)){
            $query->joinWith(['userDatas.userM']);
        }

        if (!empty($this->username)){
            $query->andWhere(['LIKE', 'user.username', $this->username ]);
        }

        if (!empty($this->remote_ip)){
            $query->andWhere(['u_control.remote_ip' => $this->remote_ip]);
        }
       // $r = $query->createCommand()->getSql();

        return $query;
    }
}