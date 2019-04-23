<?php

namespace app\modules\adminx\controllers;

use app\controllers\MainController;
use app\modules\adminx\components\AccessControl;
use app\modules\adminx\models\MenuX;
use app\modules\adminx\models\Route;
use yii\web\Controller;

class MenuxController extends MainController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow'      => true,
                    'actions'    => [
                        'menu', 'get-menux'
                    ],
                    'roles'      => ['adminCRUD', ],
                ],
            ],
            'denyCallback' => function ($rule, $action) {
                \yii::$app->getSession()->addFlash("warning","Действие запрещено.");
                return $this->redirect(\Yii::$app->request->referrer);

            }
        ];
        return $behaviors;
    }


    public function actionMenu()
    {
        $rout = new Route();
        $routes = $rout->getAppRoutes();
        return $this->render('menuEdit');
    }

    /**
     * AJAX Возвращает вид _menuxInfo для показа информации по выбранному
     * @return string
     */
    public function actionGetMenux($id = 0)
    {

        $model = MenuX::findOne($id);
        if (isset($model)){
            return $this->renderAjax('@app/modules/adminx/views/menux/_menuxInfo', [
                'model' => $model,
            ]);
        } else {
            return 'Not found';
        }
    }



}