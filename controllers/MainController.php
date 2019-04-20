<?php

namespace app\controllers;

use app\components\AccessHelper;
use app\components\conservation\ActiveDataProviderConserve;
use app\modules\admin\models\User;
use app\modules\orgstat\models\StaffOrderFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;


class MainController extends Controller
{
    public $layout = '@app/views/layouts/commonLayout.php';

    /**
     * @var
     */
    public $accessData;

    /**
     * Ответ, который будет возвращаться на AJAX-запросы
     * @var array
     */
    public $result = [
        'status' => false,
        'data' => 'Информация не найдена'
    ];

    public function beforeAction($action)
    {
        $i=1;
        if (defined('YII_DEBUG') && YII_DEBUG) {
            \Yii::$app->getAssetManager()->forceCopy = true;
        }

        //-- запись в сессию корневых подразделений, доступных пользователю и их потомков, если их там еще нет
     //   AccessHelper::saveUserDepartmentsToSession(false);
/*
        if (!$this->checkAccess($action->actionMethod)){
            throw new ForbiddenHttpException('Немає дозволу на виконання ' . $action->id);
        }
*/

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * @deprecated  Проверка прав доступа на действие контроллера
     * В контроллере должен быть определен массив $this->accessData типа:
     *     public $accessData = [
     *  [
     *  'actions' => ['actionFunc1', ],
     *  'permissions' => ['permission1', 'permission2', 'permission3']
     *  ],
     *  [
     *  'actions' => ['actionFunc3', 'actionFunc4'],
     *  'permissions' => ['permission4',]
     *  ],
     *  ];
     * возвращается истина, если:
     * 1. Проверяемого действия нет ни в одном actions
     * 2. Проверяемое действие есть в actions и у юсера есть одно из разрешений из permissions к этому actions
     * @param $action
     * @return bool
     */
    private function checkAccess($actionMethod){
        $r=\Yii::$app->user->isGuest;
        if (!empty($this->accessData) && is_array($this->accessData)){
            $user = \Yii::$app->user;
            foreach ($this->accessData as $data){
                foreach ($data['actions'] as $key => $act){
                    if ($act === $actionMethod){
                        foreach ($data['permissions'] as $key => $permission){
                            if ($user->can($permission)){
                                return true;
                            }
                        }
                        return false;
                    }
                }
            }
        }
        return (!\Yii::$app->user->isGuest);

    }

    /**
     * This method forms array of common actions.
     * 
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }









    /**
     * Просмотр грида реализованных приказов
     */
    public function actionStaffOrdersGrid____()
    {
        $filterModel = new StaffOrderFilter();
        $filterModel->is_real_order = 1;

        $dataProvider = new ActiveDataProviderConserve([
            //   'filterModel' => $filterModel,
            //  'baseModel' => $projects,
            'filterModelClass' => StaffOrderFilter::class,
            'pageSize' => 15,
            'conserveName' => 'staffOrdersGrid',
            'filterModel' => $filterModel

        ]);
        $dataProvider->sort->attributes['userCreator.username'] = [
            'asc' => ['userCreator.username' => SORT_ASC],
            'desc' => ['userCreator.username' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['userUpdator.username'] = [
            'asc' => ['userUpdator.username' => SORT_ASC],
            'desc' => ['userUpdator.username' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['department.name'] = [
            'asc' => ['department.name' => SORT_ASC],
            'desc' => ['department.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['creation_time_str'] = [
            'asc' => ['creation_time' => SORT_ASC],
            'desc' => ['creation_time' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['registration_time_str'] = [
            'asc' => ['registration_time' => SORT_ASC],
            'desc' => ['registration_time' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['update_time_str'] = [
            'asc' => ['update_time' => SORT_ASC],
            'desc' => ['update_time' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['registration_time_str'] = [
            'asc' => ['registration_time' => SORT_ASC],
            'desc' => ['registration_time' => SORT_DESC],
        ];
        return $this->render('staffOrdersGrid', [
            //  'department' => $department,
            'dataProvider' => $dataProvider,
        ]);
    }
}