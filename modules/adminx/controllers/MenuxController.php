<?php

namespace app\modules\adminx\controllers;

use app\modules\adminx\models\MenuX;
use app\modules\adminx\models\Route;
use yii\web\Controller;

class MenuxController extends Controller
{
    /**
     * Ответ, который будет возвращаться на AJAX-запросы
     * @var array
     */
    public $result = [
        'status' => false,
        'data' => 'Информация не найдена'
    ];

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