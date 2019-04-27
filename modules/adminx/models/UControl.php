<?php

namespace app\modules\adminx\models;

use app\modules\adminx\components\Config;
use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "u_control".
 *
 * @property int $user_id
 * @property string $remote_ip
 * @property string $referrer
 * @property string $remote_host
 * @property string $absolute_url
 * @property string $url
 * @property int $created_at
 */
class UControl extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'u_control';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at'], 'integer'],
            [['referrer', 'absolute_url', 'url'], 'string'],
            [['created_at'], 'required'],
            [['remote_ip', 'remote_host'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'remote_ip' => 'Remote Ip',
            'referrer' => 'Referrer',
            'remote_host' => 'Remote Host',
            'absolute_url' => 'Absolute Url',
            'url' => 'Url',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserDatas()
    {
        return $this->hasOne(UserData::class, ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserM::class, ['user_id' => 'user_id']);
    }

    public static function userControl($userId, $route)
    {
        $timeAction=time();
        try{
            $strSql = "
               UPDATE user_data
                  SET last_rout = '$route', last_rout_time = $timeAction
                  WHERE user_id = $userId
             ";
            $ret = \Yii::$app->db->createCommand($strSql)->execute();
            return '';
        } catch (Exception $e){
            return $e->getMessage();
        }

    }

    /**
     *  $params = [
    'user_id' => (!empty($userId)) ? $userId : null,
    'remote_ip' => $request->getRemoteIP(),
    'referrer' => $request->getReferrer(),
    'remote_host' => $request->getRemoteHost(),
    'absolute_url' => $request->getAbsoluteUrl(),
    'url' => $request->getUrl(),
    'created_at' => time(),
    ];

     * @param $params
     * @return string
     */
    public static function guestControl($params)
    {
        $tExpire = time() - Config::$guestControlDuration;
        $user_id = $remote_ip = $referrer = $remote_host = $absolute_url =  $url = $created_at = null;
        extract($params, EXTR_OVERWRITE);
        $remote_ip = (!empty($remote_ip)) ? trim($remote_ip) : 'none';
        try{
            //-- удаляем устаревшие записи
            $strSql = "
                DELETE FROM u_control WHERE created_at < $tExpire
              ";
            $ret = \Yii::$app->db->createCommand($strSql)->execute();
            //-- проверяем, есть ли записи юсера или $remote_ip
            if (!empty($user_id)){
                $strSql = "
                UPDATE `u_control`
                  SET 
                  `remote_ip`= '$remote_ip', `referrer`= '$referrer', `remote_host`='$remote_host', 
                  `absolute_url`='$absolute_url', `url`='$url', `created_at`=$created_at
                WHERE user_id = '$user_id'
                   ";
            } else {
                $strSql = "
                UPDATE `u_control`
                  SET 
                  `user_id`=$user_id, `referrer`= '$referrer', `remote_host`='$remote_host', 
                  `absolute_url`='$absolute_url', `url`='$url', `created_at`=$created_at
                WHERE remote_ip = '$remote_ip'
                   ";
            }
            $ret = \Yii::$app->db->createCommand($strSql)->execute();
            if ($ret === 0){
                $strSql = "
              INSERT INTO `u_control`
                (`user_id`, `remote_ip`, `referrer`, `remote_host`, `absolute_url`, `url`, `created_at`) 
                VALUES 
                ($user_id, '$remote_ip', '$referrer', '$remote_host', '$absolute_url', '$url', $created_at )
                   ";
                $ret = \Yii::$app->db->createCommand($strSql)->execute();
            }
            return '';
        } catch (Exception $e){
            return $e->getMessage();
        }

    }

}
