<?php

namespace app\modules\adminx\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "configs".
 *
 * @property int $id
 * @property string $owner
 * @property int $type
 * @property string $name
 * @property string $content
 */
class Configs extends \yii\db\ActiveRecord
{

    const CONTENT_PATTERN = '/^[A-Za-z0-9 -.@]+$/u'; //--маска для нимени

    public $cacheKey = 'conf';

    public $adminEmail;
    public $userControl;
    public $guestControl;
    public $guestControlDuration;
    public $menuType;
    public $permCacheKey = 'perm';
    public $permCacheKeyDuration = 0;
    public $passwordResetTokenExpire = 3600;  //const PASSWORD_RESET_TOKEN_EXPIRE = 3600;
    public $userDefaultRole = 'user';  //const DEFAULT_ROLE = 'user';

    const ITEMS_LIST = [
      'adminEmail',
      'userControl',
      'guestControl',
      'guestControlDuration',
      'menuType',
      'permCacheKey',
      'permCacheKeyDuration',
      'passwordResetTokenExpire',
      'userDefaultRole'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['owner'], 'required'],
            [['owner'], 'string', 'max' => 255],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 250],
            ['name', 'unique'],
            [['content',
                'adminEmail',
                'userControl',
                'guestControl',
                'guestControlDuration',
                'menuType',
                'permCacheKey',
                'permCacheKeyDuration',
                'passwordResetTokenExpire',
                'userDefaultRole'
            ],  'match', 'pattern' => self::CONTENT_PATTERN],


            [['adminEmail'], 'email'],





        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'owner' => Yii::t('app', 'Владелец'),
            'name' => Yii::t('app', 'Название'),
            'content' => Yii::t('app', 'Содержимое'),


            'adminEmail' => Yii::t('app', 'Email администратора системы'),
            'userControl' => Yii::t('app', 'Контролировать посещение сайта зарегистрированными пользователями'),
            'guestControl' => Yii::t('app', 'Контролировать посещение сайта гостями'),
            'guestControlDuration' => Yii::t('app', 'Время храненния лога гостей'),
            'menuType' => Yii::t('app', 'Тип меню сайта'),
            'permCacheKey' => Yii::t('app', 'Ключ кеша разрешений'),
            'permCacheKeyDuration' => Yii::t('app', 'Время хранения разрешений в кеше'),
            'passwordResetTokenExpire' => Yii::t('app', 'Время жизни токена сброса пароля'),
            'userDefaultRole' => Yii::t('app', 'Начальная роль нового зарегистрированного пользователя'),
        ];
    }

    public function getConfigs()
    {
       // $ret = \Yii::$app->cache->delete($this->cacheKey);

        try{
            $data = \Yii::$app->cache->get($this->cacheKey);
            //-- если в кеше сохранены настройки - берем их оттуда
            if (is_array($data)) {
                $ok = true;
                foreach (self::ITEMS_LIST as $item){
                    if (!isset($data[$item])){
                        $ok=false;
                        break;
                    }
                }
                if ($ok){
                    foreach (self::ITEMS_LIST as $item){
                        $this->{$item} = $data[$item];
                    }
                    return true;
                }
            }
            //-- если в кеше настроек нет - пробуем прочитать их из бд и сохоанить в кеш
            $data = self::find()
                ->indexBy('name')
                ->asArray()
                ->all();
            if (is_array($data)) {
                $ok = true;
                foreach (self::ITEMS_LIST as $item){
                    if (!isset($data[$item])){
                        $ok=false;
                        break;
                    }
                }
                if ($ok){
                    $dataToCache = [];
                    foreach (self::ITEMS_LIST as $item){
                        $this->{$item} = $data[$item]['content'];
                        $dataToCache[$item] = $data[$item]['content'];
                    }
                    $ret = \Yii::$app->cache->set($this->cacheKey, $dataToCache);
                    return $ret;
                }
            }
            //-- если в бд тоже нет настроек - очищаем бд и записываем туда настройки из params.php
            $dataDel  = self::deleteAll(['IN', 'name', self::ITEMS_LIST ]);
            $params = \Yii::$app->params;
            $dataToCache = [];
            foreach (self::ITEMS_LIST as $item){
                $this->{$item} = $params[$item];
                $dataToCache[$item] = $params[$item];
                $model = new self();
                $model->owner = 'admin';
                $model->name = $item;
                $model->content = $params[$item];
                if (!$model->save()){
                    $this->addErrors($model->getErrors());
                    return false;
                }

            }
            $ret = \Yii::$app->cache->set($this->cacheKey, $dataToCache);
            return $ret;

        } catch (Exception $e){
            $this->addError('id', $e->getMessage());
        }
        return false;
    }

    public function setConfigs()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $dataToCache = [];
            foreach (self::ITEMS_LIST as $item){
                $target = self::findOne(['name' => $item]);
                if (empty($target)){
                    $target = new self();
                }
                $target->owner = 'admin';
                $target->name = $item;
                $target->content = $this->{$item};
                if (!$target->save()){
                    $transaction->rollBack();
                    $this->addErrors($target->getErrors());
                    return false;
                }
                $dataToCache[$item] = $this->{$item};
            }
            $transaction->commit();

            \Yii::$app->cache->delete($this->cacheKey);
            $ret = \Yii::$app->cache->set($this->cacheKey, $dataToCache);
            return $ret;
        } catch (\Exception $e) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            $this->addError('id', $e->getMessage());
            return false;
        }
    }

    public static function dictionaryYes()
    {
        return [
            '1' => \Yii::t('app', 'Да'),
            '0' => \Yii::t('app', 'Нет'),
        ];
    }

    public static function dictionaryMenu()
    {
        return [
            'vertical' => \Yii::t('app', 'Вертикальное'),
            'horizontal' => \Yii::t('app', 'Горизонтальное'),
        ];
    }

    public static function dictionaryDuration()
    {
        return [
            '0' => \Yii::t('app', 'Постоянно'),
            '3600' => \Yii::t('app', 'Час'),
            '86400' => \Yii::t('app', 'Сутки'),
            '2592000' => \Yii::t('app', 'Месяц'),
            '31104000' => \Yii::t('app', 'Год'),
        ];
    }

    public static function dictionaryDurationSec()
    {
        return [
            '0' => \Yii::t('app', 'Постоянно'),
            '360' => \Yii::t('app', 'Час'),
            '8640' => \Yii::t('app', 'Сутки'),
            '259200' => \Yii::t('app', 'Месяц'),
            '3110400' => \Yii::t('app', 'Год'),
        ];
    }

    public static function dictionaryRoles()
    {
        $ret = [];
        $roles = \Yii::$app->authManager->getRoles();
        foreach ($roles as $role){
            $ret[$role->name] = $role->name;
        }
        return $ret;
    }







}
