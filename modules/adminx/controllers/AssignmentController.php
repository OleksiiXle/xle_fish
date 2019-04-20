<?php

namespace app\modules\adminx\controllers;

use app\controllers\MainController;
use Yii;
use app\modules\adminx\models\Assignment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class AssignmentController extends MainController
{
    public $userClassName;
    public $idField = 'id';
    public $usernameField = 'username';
    public $fullnameField;
    public $searchClass;
    public $extraColumns = [];




    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->userClassName === null) {
            $this->userClassName = Yii::$app->getUser()->identityClass;
            $this->userClassName = $this->userClassName ? : 'app\modules\adminx\models\User';
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'assign' => ['POST'],
                    'revoke' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * +++ Назначение пользователю ролей, разрешений, роутов
     * @param string $id
     * @param string $type (roles, permissions, routs)
     * @param array $items
     * @return array
     */
    public function actionAssign(){
        try {
            $id    = Yii::$app->getRequest()->post('user_id', []);
            $items = Yii::$app->getRequest()->post('items', []);
            $model = new Assignment($id);
            $success = $model->assign($items);
            $result = $model->getItemsXle();

            $this->result =[
                'status' => true,
                'data'=> $model->getItemsXle(),
            ];
        } catch (\Exception $e) {
            $this->result['data'] = $e->getMessage();
        }
        return $this->asJson($this->result);


        $id    = Yii::$app->getRequest()->post('user_id', []);
        $items = Yii::$app->getRequest()->post('items', []);
        $model = new Assignment($id);
        $success = $model->assign($items);
        Yii::$app->getResponse()->format = 'json';
        $result = $model->getItemsXle();
        return $result;

    }

    /**
     * +++ Удаление у пользователя ролей, разрешений, роутов
     * @param string $id
     * @param string $type (roles, permissions, routs)
     * @param array $items
     * @return array
     */
    public function actionRevoke() {
        try {
            $id    = Yii::$app->getRequest()->post('user_id', []);
            $items = Yii::$app->getRequest()->post('items', []);
            $model = new Assignment($id);
            $success = $model->revoke($items);
            $result = $model->getItemsXle();

            $this->result =[
                'status' => true,
                'data'=> $model->getItemsXle(),
            ];
        } catch (\Exception $e) {
            $this->result['data'] = $e->getMessage();
        }
        return $this->asJson($this->result);



        $id    = Yii::$app->getRequest()->post('user_id', []);
        $items = Yii::$app->getRequest()->post('items', []);
        $model = new Assignment($id);
        $success = $model->revoke($items);
        Yii::$app->getResponse()->format = 'json';
        $result = $model->getItemsXle();

        return $result;
    }

}
