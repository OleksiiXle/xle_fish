<?php

namespace app\components\configs\models;

use Yii;

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
    const OWNER_ADMIN = 0;
    const OWNER_USER = 1;

    const TYPE_INTEGER = 0;
    const TYPE_STRING = 1;
    const TYPE_ARRAY = 2;

    const OWNER_LIST = [
        self::OWNER_ADMIN => 'admin',
        self::OWNER_USER => 'user',

    ];

    const TYPE_LIST = [
        self::TYPE_INTEGER => 'integer',
        self::TYPE_STRING => 'string',
        self::TYPE_ARRAY => 'array',
    ];

    private $_ownerTxt;
    private $_typeTxt;


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
            [['owner', 'type'], 'required'],
            [['type'], 'integer'],
            [['owner'], 'string', 'max' => 255],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 250],
            ['name', 'unique'],


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
            'type' => Yii::t('app', 'Тип'),
            'name' => Yii::t('app', 'Название'),
            'content' => Yii::t('app', 'Содержимое'),
        ];
    }

    /**
     * @return mixed
     */
    public function getOwnerTxt()
    {
        $this->_ownerTxt = self::OWNER_LIST[$this->owner];
        return $this->_ownerTxt;
    }

    /**
     * @return mixed
     */
    public function getTypeTxt()
    {
        $this->_typeTxt = self::TYPE_LIST[$this->type];
        return $this->_typeTxt;
    }


}
