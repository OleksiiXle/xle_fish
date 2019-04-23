<?php
namespace app\modules\adminx\models\filters;

use app\modules\adminx\models\AuthItemX;
use yii\base\Model;

class AuthItemFilter extends Model
{
    public $name;
    public $type;
    public $description;
    public $rule_name;



    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type'], 'integer'],
            [['description', 'rule_name' , 'name'], 'string', 'max' => 64]

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type' => 'Тип',
            'name' => 'Название',
            'rule_name' => 'Правило',
            'description' => 'Описание',
        ];
    }


    public function getQuery($params = null){
        switch ($this->type){
            case AuthItemX::TYPE_All:
                $query = AuthItemX::find();
                break;
            case AuthItemX::TYPE_ROLE:
                $query = AuthItemX::find()
                    ->andWhere(['type' => AuthItemX::TYPE_ROLE]);
                break;
            case AuthItemX::TYPE_PERMISSION:
                $query = AuthItemX::find()
                    ->andWhere(['type' => AuthItemX::TYPE_PERMISSION])
                    ->andWhere('NOT (name LIKE "/%")');
                break;
            case AuthItemX::TYPE_ROUTE:
                $query = AuthItemX::find()
                    ->andWhere(['type' => AuthItemX::TYPE_PERMISSION])
                    ->andWhere('name LIKE "/%"');
                break;
            default:
                $query = AuthItemX::find();

        }


        if (!$this->validate()) {
            return $query;
        }

        if (!empty($this->name)) {
            $query->andWhere(['like', 'name', $this->name]);
        }

        if (!empty($this->rule_name) && $this->rule_name != 'Без правила') {
            $query->andWhere(['like', 'rule_name', $this->rule_name]);
        }


        return $query;







    }



}