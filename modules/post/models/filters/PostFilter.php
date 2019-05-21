<?php
namespace app\modules\post\models\filters;

use app\modules\post\models\Post;
use Yii;
use yii\base\Model;

class PostFilter extends Model
{

    public $user_id;
    public $created_at;
    public $username;
    public $target;
    public $type;
    public $typeMedia;
    public $name;
    public $content;
    public $nameMedia;



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'target', 'type', 'typeMedia', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['name', 'nameMedia'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'Владелец'),
            'target' => Yii::t('app', 'Цель'),
            'type' => Yii::t('app', 'Тип'),
            'name' => Yii::t('app', 'Название'),
            'typeMedia' => Yii::t('app', 'Тип медиа'),
            'nameMedia' => Yii::t('app', 'Название медиа'),
            'content' => Yii::t('app', 'Содержимое'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }



    public function getQuery($params = null)
    {
        $query = Post::find();

        if (!empty($this->user_id)){
            $query->joinWith(['user']);
        }

        if (!empty($this->typeMedia) || !empty($this->nameMedia) ){
            $query->joinWith(['postMedia']);
        }

        if (!empty($this->name)){
            $query->andWhere(['LIKE', 'post.name', $this->name ]);
        }

        if (!empty($this->type)){
            $query->andWhere(['post.type' => $this->type]);
        }
       // $r = $query->createCommand()->getSql();

        return $query;
    }
}