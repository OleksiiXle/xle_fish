<?php

namespace app\components\widgets\controllers;

use app\components\widgets\menuUpdate\models\MenuX;
use app\controllers\MainController;
use app\modules\adminx\models\Route;
use yii\web\Controller;


class WidgetController extends MainController
{
    /**
     * Ответ, который будет возвращаться на AJAX-запросы
     * @var array
     */
    public $result = [
        'status' => false,
        'data' => 'Информация не найдена'
    ];

    //*************************************************************************************************************** РЕДАКТИРОВАНИЕ МЕНЮ

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

    /**
     * AJAX Открытие модального окна для редактирования
     * @param  $id
     * @return string
     */
    public function actionMenuxModalOpenMenuUpdate($id, $menu_id, $nodeAction){
        $routes = MenuX::getRoutesDict();
        $permissions = MenuX::getPermissionsDict();
        switch ($nodeAction){
            case 'update':
                $model = MenuX::findOne($id);
                break;
            case 'appendChild':
            case 'appendBrother':
                $model = new MenuX();
                $model->node1 = $id; //-- кому добавлять потомка или брата
                break;
                break;
        }
        if (isset($model)){
            $model->menu_id = $menu_id;
            $model->nodeAction = $nodeAction;
            return $this->renderAjax('@app/components/widgets/menuUpdate/views/_form_menu', [
                'model' => $model,
                'routes' => $routes,
                'permissions' => $permissions,

            ]);
        } else {
            return 'Iнформацію не знайдено';
        }
    }

    /**
     * AJAX Сoхранение изменений после редактирования
     */
    public function actionMenuxMenuUpdate()
    {
        $r=2;
        if ($menuData = \Yii::$app->request->post('MenuX')){
            switch ($menuData['nodeAction']){
                case 'update':
                    $model = MenuX::findOne($menuData['id']);
                    if (isset($model)){
                        $model->setAttributes($menuData);
                        if ($model->save()){
                            $this->result = [
                              'status' => true,
                              'data' => $model->getAttributes(),
                            ];
                        } else {
                            $this->result['data'] = $model->getErrors();
                        }
                    }
                    break;
                case 'appendChild':
                    $model = MenuX::findOne($menuData['node1']);
                    if (isset($model)){
                        if ($model->appendChild($menuData)){
                            $this->result = $model->result;
                        } else {
                            $this->result['data'] = $model->result['data'];
                        }
                    }
                    break;
                case 'appendBrother':
                    $model = MenuX::findOne($menuData['node1']);
                    if (isset($model)){
                        if ($model->appendBrother($menuData)){
                            $this->result = $model->result;
                        } else {
                            $this->result['data'] = $model->result['data'];
                        }
                    }
                    break;
            }
        }
        return $this->asJson($this->result);
    }

    /**
     * AJAX Операции с деревом , не требующие ввода данных
     *
     */
    public function actionMenuxTreeModifyAuto(){
        $_post = \Yii::$app->request->post();
        $node1_id = $_post['node1_id'];
        $node2_id = $_post['node2_id'];
        $nodeAction = $_post['nodeAction'];
        $node1 = MenuX::findOne($node1_id);
        if(isset($node1)){
            switch ($nodeAction){
                case 'moveUp':  //--- move up
                case 'moveDown':  //--- move down
                    //-- поменять сортировку у $node1_id и $node2_id
                if ($node1->exchangeSort($node2_id)){
                    $this->result = $node1->result;
                } else {
                    $this->result['data'] = $node1->result['data'];
                }
                    break;
                case 'levelUp':
                    //-- сделать $node1_id из потомка $node2_id - его соседом сверху
                    if ($node1->levelUp($node2_id)){
                        $this->result = $node1->result;
                    } else {
                        $this->result['data'] = $node1->result['data'];
                    }

                    break;
                case 'levelDown':
                    //-- сделать $node1_id из соседа сверху $node2_id - его первым потомком
                    if ($node1->levelDown($node2_id)){
                        $this->result = $node1->result;
                    } else {
                        $this->result['data'] = $node1->result['data'];
                    }
                    break;
            }
        }

        return $this->asJson($this->result);
    }

    /**
     *  AJAX Удаление с потомками
     */
    public function actionMenuxDelete()
    {
        $_post = \Yii::$app->request->post();
        $node1_id = $_post['node1_id'];
        $this->result = MenuX::deleteWithChildren($node1_id);
        return $this->asJson($this->result);
    }







    //*************************************************************************************************************** РЕДАКТИРОВАНИЕ МЕНЮ (конец)

}