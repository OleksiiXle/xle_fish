<?php
namespace app\modules\adminx\models\filters;

use app\models\dictionary\Dictionary;
use app\modules\adminx\models\UserM;
use yii\base\Model;

class UserFilter extends Model
{
    public $id;
    public $first_name;
    public $middle_name;
    public $last_name;

    public $role;
    public $permission;
    public $roleDict = [];
    public $permissionDict;


    public function __construct(array $config = [])
    {
        $roles = \Yii::$app->authManager->getRoles();
       // $permission = \Yii::$app->authManager->getPermissions();
      //  $i=1;
        $this->roleDict['0'] = 'Не визначено';
        foreach ($roles as $role){
            $this->roleDict[$role->name] = $role->name;
        }
        parent::__construct($config);
    }


    public function rules()
    {
        return [
            [['id',  ], 'integer'],
            [['first_name', 'middle_name', 'last_name', 'role'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логін',
            'first_name' => 'Імя',
            'middle_name' => 'По батькові',
            'last_name' => 'Прізвище',
            'email' => 'Email',
            'status' => 'Status',
            'role' => 'Роль користувача',

        ];
    }



    public function getQuery($params = null){

        $query = UserM::find()
            ->joinWith(['userDatas']);

        if (!empty($this->role)) {
            $query ->innerJoin('auth_assignment aa', 'user.id=aa.user_id')
                ->innerJoin('auth_item ai', 'aa.item_name=ai.name')
                ->where(['ai.type' => 1])
            ;
        }

        if (!$this->validate()) {
            return $query;
        }

        if (!empty($this->role)) {
            $query->andWhere(['aa.item_name' => $this->role]);
        }

        if (!empty($this->first_name)) {
            $query->andWhere(['like', 'user_data.first_name', $this->first_name]);
        }
        if (!empty($this->middle_name)) {
            $query->andWhere(['like', 'user_data.middle_name', $this->middle_name]);
        }
        if (!empty($this->last_name)) {
            $query->andWhere(['like', 'user_data.last_name', $this->last_name]);
        }
     //   $e = $query->createCommand()->getSql();
        return $query;
    }

}