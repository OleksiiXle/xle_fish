<?php

namespace app\components\widgets\controllers;

use app\components\widgets\menuUpdate\models\MenuX;
use yii\web\Controller;


class WidgetController extends Controller
{
    /**
     * Ответ, который будет возвращаться на AJAX-запросы
     * @var array
     */
    public $result = [
        'status' => false,
        'data' => 'Информация не найдена'
    ];

    public function actionMenuxGetDefaultTree()
    {
        $i=1;
        try {
            $this->result =[
                'status' => true,
                'data'=> MenuX::getDefaultTree()
                ];
        } catch (\Exception $e) {
            $this->result['data'] = $e->getMessage();
        }
        return $this->asJson($this->result);
    }

    public function actionMenuxGetChildren()
    {
        try {
            $id = \Yii::$app->request->post('id');
        //    $menu_id = \Yii::$app->request->post('menu_id');
            $menux = MenuX::findOne($id);
            $this->result =[
                'status' => true,
                'data'=> $menux->childrenArray,
                ];
        } catch (\Exception $e) {
            $this->result['data'] = $e->getMessage();
        }
        return $this->asJson($this->result);

    }


}