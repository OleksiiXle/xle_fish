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
    public function actionModalOpenMenuUpdate($id){
        $model = MenuX::findOne($id);
        if (isset($model)){
            return $this->renderAjax('_form_menu', [
                'model' => $model,
            ]);
        } else {
            return 'Iнформацію не знайдено';
        }
    }

    /**
     * AJAX Сoхранение изменений после редактирования
     */
    public function actionMenuUpdate()
    {
        $departmentData = \Yii::$app->request->post('OrderProjectDepartment');
        $department = OrderProjectDepartment::findOne($departmentData['id']);
        if (isset($department)){
            // $department->checkAccessToAdiition();
            $department->scenario = OrderProjectDepartment::SCENARIO_UPDATE_B;
            $department->setAttributes($departmentData);
            if ($department->saveDepartment()){ //--Behavior
                $this->result['status'] = true;
                $this->result['data'] = [
                    'currentDepartment_id'       => $departmentData['id'],
                    'departmentDataForRefresh'   => $department->departmentDataForRefresh,
                    //             'departmentDataForRefresh'   => $department->getDataForTree(),
                    'parentsToRefresh'           => $department->parentsToRefresh,
                ];
            } else {
                $this->result['data'] = $department->getErrors();
            }
        }
        return $this->asJson($this->result);
    }

    /**
     * AJAX Клонирование делается соседом снизу текущего
     * @param $department_id
     * @return string
     */
    public function actionMenuClone()
    {
        $department_id = \Yii::$app->request->post('department_id');
        if (isset($department_id)){
            $node = OrderProjectDepartment::findOne($department_id);
            if (isset($node)){
                //    $node->checkAccessToAdiition();
                $this->result = $node->cloneDepartment(); //--Behavior
            }
        }
        return $this->asJson($this->result);
    }

    /**
     * AJAX Открытие модального окна для удаления
     * @param  $id
     * @return string
     */
    public function actionModalMenuOpenDelete($department_id)
    {
        $model = OrderProjectDepartment::findOne($department_id);
        if (isset($model)){
            //        $model->checkAccessToAdiition();
            $model->scenario = OrderProjectDepartment::SCENARIO_DELETE;
            return $this->renderAjax('_form_department_delete_confirm', [
                'model' => $model,
                //  'department_id' => $department_id,
            ]);
        } else {
            return 'Iнформацію не знайдено';
        }
    }

    /**
     * !!! AJAX Удаление с потомками
     */
    public function actionMenuDelete()
    {
        $department_id = \Yii::$app->request->post('department_id');
        $staff_order_end_delay_str = \Yii::$app->request->post('staff_order_end_delay_str');
        $additionalMode = \Yii::$app->request->post('additionalMode');
        if (isset($department_id)  && isset($staff_order_end_delay_str) && isset($additionalMode)){
            $node = OrderProjectDepartment::findOne($department_id);
            if (isset($node)){
                //     $node->checkAccessToAdiition();
                $node->staff_order_end_delay_str = $staff_order_end_delay_str;
                if (!empty($node->staff_order_end_delay_str)) {
                    $node->staff_order_end_delay = Functions::dateToInt($node->staff_order_end_delay_str);
                }
                if (!$node->validate('staff_order_end_delay_str')){
                    $this->result['data'] = $node->getErrors('staff_order_end_delay_str');
                }  else{
                    $this->result = $node->deleteWithChildren('delete', $additionalMode); //-- Behavior
                }
            }
        }
        return $this->asJson($this->result);
    }

    //********************************************************************* ОПЕРАЦИИ с ДЕРЕВОМ
    /**
     * AJAX Открытие модального окна для создания нового
     * @param  $id
     * @return string
     */
    public function actionModalOpenMenuCreate($parent_id, $mode, $staffOrder_id)
    {
        $model = new OrderProjectDepartment();
        if (isset($model)){
            $model->scenario = OrderProjectDepartment::SCENARIO_UPDATE_B;
            $model->nodeAction = $mode;
            $model->staff_order_id = $staffOrder_id;
            $model->parent_id = $parent_id;
            $model->node1 = $parent_id;
            //       $model->checkAccessToAdiition();
            return $this->renderAjax('_form_department', [
                'model' => $model,
                'id' => $parent_id,
            ]);
        } else {
            return 'Iнформацію не знайдено';
        }
    }

    /**
     * AJAX Создание нового
     */
    public function actionMenuCreate()
    {
        $departmentData = \Yii::$app->request->post('OrderProjectDepartment');
        if (isset($departmentData)){
            switch ($departmentData['nodeAction']){
                case '"appendChild"':
                    $node = OrderProjectDepartment::findOne($departmentData['parent_id']);
                    if (isset($node)){
                        //     $node->checkAccessToAdiition();
                        $this->result = $node->appendChild($departmentData); //-- Behavior
                    }
                    break;
                case '"appendBrother"':
                    $node = OrderProjectDepartment::findOne($departmentData['node1']);
                    if (isset($node)){
                        //       $node->checkAccessToAdiition();
                        $this->result = $node->appendBrother($departmentData); //-- Behavior
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
    public function actionTreeModifyAuto(){
        $data = \Yii::$app->request->post();
        switch ($data['mode']){
            case 'moveUp':  //--- move up
            case 'moveDown':  //--- move down
                $node = OrderProjectDepartment::findOne($data['node1']);
                if (isset($node)){
                    //    $node->checkAccessToAdiition();
                    $this->result = $node->exchangeSort($data['node2']); //-- Behavior
                }
                break;
            case 'moveUpAdditional':  //--- move up additional
            case 'moveDownAdditional':  //--- move down additional
                $node = OrderProjectPosition::findOne($data['position_id']);
                if (isset($node)){
                    //        $node->checkAccessToAdiition();
                    $this->result = $node->exchangeSort($data['position_id2']); //-- Behavior
                }
                break;
            case 'levelUp':
                $node = OrderProjectDepartment::findOne($data['node1']);
                if (isset($node)){
                    //       $node->checkAccessToAdiition();
                    $this->result = $node->levelUp($data['node2']); //-- Behavior
                }
                break;
            case 'levelDown':
                $node = OrderProjectDepartment::findOne($data['node1']);
                if (isset($node)){
                    //      $node->checkAccessToAdiition();
                    $this->result = $node->levelDown($data['node2']); //-- Behavior
                }
                break;
            default:
                $this->result['data'] = 'Щось незрозуміле прийшльо до actionTreeOperationsAuto';
                break;
        }
        return $this->asJson($this->result);
    }





    //*************************************************************************************************************** РЕДАКТИРОВАНИЕ МЕНЮ (конец)

}