<?php

namespace app\controllers;

use app\models\MenuX;
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
            return $this->renderAjax('@app/views/menux/_menuxInfo', [
                'model' => $model,
            ]);
        } else {
            return 'Not found';
        }
    }



}