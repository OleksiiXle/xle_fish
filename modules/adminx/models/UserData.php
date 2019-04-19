<?php

namespace app\modules\adminx\models;

use app\models\Functions;
use Yii;

/**
 * This is the model class for table "user_data".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 *
 * @property User $user
 */
class UserData extends \yii\db\ActiveRecord
{
    public static $activityIntervalArray=[
        3600 => '1 година',
        7200 => '2 години',
        10800 => '3 години',
        86400 => '1 доби',
        172800 => '2 доби',
        259200 => '3 доби',
        345600 => '4 доби',
    ];

    private $_userLogin;
    private $_lastRoutTime;
    private $_userFio;

    public $activityInterval = 3600;



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'first_name', 'middle_name', 'last_name'], 'required'],
            [['user_id', 'last_rout_time', ], 'integer'],
            [['first_name', 'middle_name', 'last_name'], 'string', 'max' => 255],
            [['last_rout', ], 'string', 'max' => 250],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ІД',
            'first_name' => 'Імя',
            'middle_name' => 'По батькові',
            'last_name' => 'Прізвище',
            'last_rout' => 'Останній роут',
            'last_rout_time' => 'Остання активність',
            'userLogin' => 'Логін',
            'userFio' => 'П.І.Б.',
        ];
    }

//*********************************************************************************************** ДАННЫЕ СВЯЗАННЫХ ТАБЛИЦ
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getUserM()
    {
        return $this->hasOne(UserM::class, ['id' => 'user_id']);
    }

//*********************************************************************************************** ГЕТТЕРЫ-СЕТТЕРЫ
    public function getUserFio()
    {
        $this->_userFio = $this->last_name . ' ' . mb_substr($this->first_name,0,1) . '.'
            . mb_substr($this->middle_name,0,1) . '.';
        return $this->_userFio;
    }

    public function getUserLogin()
    {
        $this->_userLogin = $this->user->username;
        return $this->_userLogin;
    }


    public function getLastRoutTime()
    {
        $this->_lastRoutTime = Functions::intToDateTime($this->last_rout_time);
        return $this->_lastRoutTime;
    }


//*********************************************************************************************** ФУНКЦИИ

    public function beforeSave($insert) {
        if ($insert){
            $this->created_at = time();
          //  $this->creation_user = (isset(\Yii::$app->user->id)) ? \Yii::$app->user->id : 0;
            $this->updated_at = time();
          //  $this->update_user = (isset(\Yii::$app->user->id)) ? \Yii::$app->user->id : 0;

        }
        $this->updated_at = time();
       // $this->update_user = (isset(\Yii::$app->user->id)) ? \Yii::$app->user->id : 0;
        return parent::beforeSave($insert);
    }


}
