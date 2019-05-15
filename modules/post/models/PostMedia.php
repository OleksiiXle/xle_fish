<?php

namespace app\modules\post\models;

use app\models\FileUpload;
use app\models\MainModel;
use app\models\UploadPreview;
use Yii;

/**
 * This is the model class for table "post_media".
 *
 * @property int $id ID
 * @property int $post_id Post_id
 * @property int $type Тип
 * @property string $name Название
 * @property resource $file_name Имя файла
 * @property int $created_at Создано
 * @property int $updated_at Изменено
 *
 * @property Post $post
 */
class PostMedia extends MainModel
{

    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;
    const TYPE_AUDIO = 3;
    const TYPE_TEXT = 4;

    const TYPE_LIST = [
        self::TYPE_IMAGE => 'image',
        self::TYPE_VIDEO => 'video',
        self::TYPE_AUDIO => 'audio',
        self::TYPE_TEXT => 'text',
    ];

    private $_urlToFile;
    private $_pathToFile;

    public $mediaFile;
  //  public $imageFile;
 //   public $videoFile;
  //  public $audioFile;
  //  public $textFile;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'name', 'created_at', 'updated_at'], 'required'],
            [['post_id', 'type', 'created_at', 'updated_at'], 'integer'],
            [['name', 'file_name'], 'string', 'max' => 250],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],

            //--------------------------------------------------------------------------- виртуальные атрибуты
         //   [['mediaFile',], 'file'],
        //    [['imageFile', 'videoFile'. 'audioFile' , 'textFile'], 'file'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'post_id' => Yii::t('app', 'Post_id'),
            'type' => Yii::t('app', 'Тип'),
            'name' => Yii::t('app', 'Название'),
            'file_name' => Yii::t('app', 'Имя файла'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }

    /**
     * @return mixed
     */
    public function getListType()
    {
        $this->_listType = [
            self::TYPE_IMAGE => Yii::t('app', 'Изображение'),
            self::TYPE_VIDEO => Yii::t('app', 'Видео'),
            self::TYPE_AUDIO => Yii::t('app', 'Аудио'),
        ];

        return $this->_listType;
    }

    public function savePostMedia($userId)
    {
        $t=1;
        if ($this->validate()){
            $ret = FileUpload::saveMediaFromTmp($userId, $this->file_name, self::TYPE_LIST[$this->type]);
            if ($ret['status']){
                $this->file_name = str_replace('tmp_', '', $this->file_name);
                return $this->save(false);
            } else {
                $this->addError('file_name', $ret['data']);
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getUrlToFile()
    {
        $this->_urlToFile = \Yii::getAlias('@web'). \Yii::$app->params['pathToFiles']
            . '/' . self::TYPE_LIST[$this->type] . '/' . $this->post->user_id . '/' . $this->file_name;
        return $this->_urlToFile;
    }

    /**
     * @return mixed
     */
    public function getPathToFile()
    {
        $this->_pathToFile = \Yii::getAlias('@app') . '/web' . \Yii::$app->params['pathToFiles'] . '/'
            . self::TYPE_LIST[$this->type] . '/' . $this->post->user_id . '/' . $this->file_name;
        return $this->_pathToFile;
    }

    public function deleteFile()
    {
        $ret = unlink($this->pathToFile);
        return $ret;
    }



}
