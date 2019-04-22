<?php

namespace app\components;

use app\modules\adminx\models\UserM as User;
use app\modules\adminx\models\UserDepartment;
use app\modules\adminx\models\UserM;
use app\modules\structure\models\OrderProjectDepartment;
use app\models\DepartmentCommon;
use app\models\Department;



class AccessHelper {
    /*
     Array
(
    [rootRights] =>  права на все подразделения
    [rootDepartments] => Array - выбранные администратором подразделения, к которым (включая их детей) у юсера есть доступ
        (
            [30018] => Array
                (
                    [can_department] => 1
                    [can_position] => 1
                    [can_personal] => 1
                )

            [26361] => Array
                (
                    [can_department] => 1
                    [can_position] => 1
                    [can_personal] => 1
                )

            [29161] => Array
                (
                    [can_department] => 1
                    [can_position] => 1
                    [can_personal] => 1
                )

        )

    [departmentsAvailable] => Array - полный массив ид разрешенных подразделений
        (
            [0] => 30018
            [1] => 30019
            [2] => 30020
            [3] => 30021
            [4] => 30022
        )

    [parentsDepartments] => ид всех родителей корневых подразделений - будет использоваться для рисования дефолтного дерева
    они будут недоступны для изменения, но выводить их надо
    Array
(
    [30018] => 30018
    [14005] => 14005
    [26361] => 26361
    [25385] => 25385
    [29161] => 29161
    [29145] => 29145
    [28688] => 28688
)



)

     */

    /*
     ВСЯ аутентифакационная информация хранится в сессии:
    Array
(
    [userPermissions] => Array
        (   ВСЕ РОЛИ, РАЗРЕШЕНИЯ И РОУТЫ
            [orgStatMainTreeView] => ""
            [_orgStatProposalCRUD] => proposalCRUDRule - ЕСЛИ У РАЗРЕШЕНИЯ ЕСТЬ ПРАВИЛО - ОНО УКАЗЫВАЕТСЯ ЗДЕСЬ
            [_orgStatProposalView] => ""
            [/minitree/*] => ""
            [/minitreemp/*] => ""
            [/mptree/*] => ""
            [/orgstat/*] => ""
            [menuDictionariyPositionMain_] =>""
            [menuOrgstat_] => ""
        )

    [userRoutes] => Array
    ТОЛЬКО РОУТЫ
        (
            [/adminx/default/logout] => 1
            [/adminx/user/change-password] => 1
            [/adminx/user/forget-password] => 1
            [/dictionary/*] => 1
            [/education/default/tutorial] => 1
            [/minitree/*] => 1
            [/minitreemp/*] => 1
            [/mptree/*] => 1
            [/orgstat/*] => 1
            [/site/*] => 1
            [/tree/*] => 1
            [/treetwo/*] => 1
        )

    [userRoles] => Array
    ТОЛЬКО РОЛИ
        (
            [orgstatAdminNP_] => 1
        )
        [rootRights] => 1 - ПРИЗНАК, ЧТО ЮСЕРУ ДОСТУПНЫ ВСЕ ПОДРАЗДЕЛЕНИЯ
    [rootDepartments] => Array
    ПОДРАЗДЕЛЕНИЯ, ВЕТКИ КОТОРЫХ ЮСЕР МОЖЕТ ВИДЕТЬ И РЕДАСТИРОВАТЬ В ЗАВИСИМОСТИ ОТ can_
        (
            [14005] => Array
                (
                    [can_department] => 1
                    [can_position] => 1
                    [can_personal] => 0
                )

            [30018] => Array
                (
                    [can_department] => 1
                    [can_position] => 1
                    [can_personal] => 1
                )

        )
    [parentDepartments] => Array
    ПОДРАЗДЕЛЕНИЯ - РОДИТЕЛИ ДОСТУПНЫХ ЮЗЕРУ ВЕРШИН (ИСПОЛЬЗУЮТСЯ ПРИ РИСОВАНИИ ДЕРЕВА, НЕДОСТУПАНЫ ДЛЯ ИЗМЕНЕНИЯ)
     (
        30018 => 30018,
        14005 => 14005,
        25385 => 25385,
        )


        [departmentsAvailable] => Array
    ИД ПОДРАЗДЕЛЕНИЙ, - ДЕТЕЙ РАЗРЕШЕННЫХ РОДИТЕЛЕЙ - ИСПОЛЬЗУЕТСЯ ТОЛЬКО В education - и ПОДЛЕЖИТ УДАЛЕНИЮ В ДАЛЬНЕЙШЕМ
     (
        0 => 30018,
        1 => '30019',
        2 => '30020',
        3 => '30021',
        4 => '30022',
        5 => '30023',
        6 => '30024',
        )



)
)
     */


    /**
     * !!! ADMINX Запись в сессию аутентификационных данных пользователя
     * userPermissions - все разрешения и роуты
     * userRoutes - разрешенные роуты
     * userRoles - роли
     * @param bool $refresh - с очисткой
     */
    static public function saveUserPermissionsToSession($refresh = true)
    {
        $session = \Yii::$app->session;
        if ($refresh){
            if (! $session->get('userPermissions')){
                $session->remove('userPermissions');
            }
            if (! $session->get('userRoutes')){
                $session->remove('userRoutes');
            }
            if (! $session->get('userRoles')){
                $session->remove('userRoles');
            }
        }
        if(!$session->has('userPermissions')
            || !$session->has('userRoutes')
            || !$session->has('userRoles') ){
            $user_id = \yii::$app->user->getId();
            $auth = \yii::$app->authManager;
            $permItems=$auth->getPermissionsByUser($user_id);
            $permissions = $routes = $roles = [];
            foreach ($permItems as $item){
                $permissions[$item->name] = (!empty($item->ruleName)) ? $item->ruleName : '';

                //  $permissions[$item->name] = true;
                if ($item->name[0] === '/') {
                    $routes[$item->name] = true;
                }
            }
            $roleItems = $auth->getRolesByUser($user_id);
            foreach ($roleItems as $item){
                $roles[$item->name] = true;
                $permissions[$item->name] = (!empty($item->ruleName)) ? $item->ruleName : '';

            }
            $session->set('userPermissions', $permissions);
            $session->set('userRoutes', $routes);
            $session->set('userRoles', $roles);
        }
    }

    /**
     * Запись в сессию зазрешений юсера, подразделений, доступных пользователю
     * @param bool $refresh - с очисткой
     */
    //--todo *****
    static public function saveUserDepartmentsToSessionOwn($refresh = true)
    {
    //    $user_id = \yii::$app->user->getId();
    //    $q1=\yii::$app->authManager->getPermissionsByUser($user_id);
    //    $q2=\yii::$app->authManager->getAssignments($user_id);
    //    $q3=\yii::$app->authManager->getRolesByUser($user_id);
        $session = \Yii::$app->session;
        //-- если надо - очищаем данные сессии
        if ($refresh){
            if (! $session->get('departmentsAvailable')){
                $session->remove('departmentsAvailable');
            }
            if (! $session->get('rootDepartments')){
                $session->remove('rootDepartments');
            }
            if (! $session->get('parentsDepartments')){
                $session->remove('parentsDepartments');
            }
            if (! $session->get('rootRights')){
                $session->remove('rootRights');
            }
            if (! $session->get('userAssigments')){
                $session->remove('userAssigments');
            }
        }
        /*
        if(!$session->has('departmentsAvailable')
            || !$session->has('rootDepartments')
            || !$session->has('parentsDepartments')
            || !$session->has('userAssigments')
            || !$session->has('rootRights') ){
        */
        //-- если в сессии нет аутентификационных данных - записываем их туда
        if(!$session->has('userAssigments')){
            $user = User::findOne(\Yii::$app->user->getId());
            $session->set('userAssigments', array_flip(array_keys(\yii::$app->authManager->getPermissionsByUser($user->id))));
            $rootDepartments = $user->rootDepartments;
            $parentsDepartments = [];
            foreach ($rootDepartments as $root_id => $value){
                $pd = DepartmentCommon::getParentsIdsAll($root_id);
                foreach ($pd as $p){
                    if(!isset($parentsDepartments[$p])){
                        $parentsDepartments[$p] = $p;
                    }
                }
            }
            //    if (in_array(\app\models\DepartmentCommon::ROOT_ID, $rootDepartments)){
            if (isset($rootDepartments[DepartmentCommon::ROOT_ID] )){
                $session->set('rootRights', true );
                if (! $session->get('rootDepartments')){
                    $session->set('rootDepartments', $rootDepartments);
                }
            } else {
                $session->set('rootRights', false );
                if (! $session->get('rootDepartments')){
                    $session->set('rootDepartments', $rootDepartments);
                }
                if (! $session->get('departmentsAvailable')){
                    $departmentsAvailable = $user->departmentsAvailable;
                    $departmentsAvailable[] = DepartmentCommon::ROOT_ID;
                    $session->set('departmentsAvailable', $departmentsAvailable);
                }
                if (! $session->get('parentsDepartments')){
                    $session->set('parentsDepartments', $parentsDepartments);
                }

            }
        }

    }

    /**
     * !!! ADMINX Запись в сессию корневых подразделений, доступных пользователю и их потомков
     * @param bool $refresh - с очисткой
     */
    static public function saveUserDepartmentsToSession($refresh = true)
    {
      //  $user_id = \yii::$app->user->getId();
      //  $q1=\yii::$app->authManager->getPermissionsByUser($user_id);
      //  $q2=\yii::$app->authManager->getAssignments($user_id);
      //  $q3=\yii::$app->authManager->getRolesByUser($user_id);
        $session = \Yii::$app->session;
        if ($refresh){
            if (! $session->get('departmentsAvailable')){
                $session->remove('departmentsAvailable');
            }
            if (! $session->get('rootDepartments')){
                $session->remove('rootDepartments');
            }
            if (! $session->get('parentsDepartments')){
                $session->remove('parentsDepartments');
            }
            if (! $session->get('rootRights')){
                $session->remove('rootRights');
            }
            /*
            if (! $session->get('userAssigments')){
                $session->remove('userAssigments');
            }
            */
        }
        /*
                if(!$session->has('departmentsAvailable')
            || !$session->has('rootDepartments')
            || !$session->has('parentsDepartments')
            || !$session->has('userAssigments')
            || !$session->has('rootRights') ){

         */
        if(!$session->has('rootDepartments')){
            $user = UserM::findOne(\Yii::$app->user->getId());
           // $session->set('userAssigments', array_flip(array_keys(\yii::$app->authManager->getPermissionsByUser($user->id))));
            $rootDepartments = $user->rootDepartments;
            $parentsDepartments = [];
            foreach ($rootDepartments as $root_id => $value){
                $pd = Department::getParentsIdsAll($root_id);
                foreach ($pd as $p){
                    if(!isset($parentsDepartments[$p])){
                        $parentsDepartments[$p] = $p;
                    }
                }
            }
            //    if (in_array(\app\models\Department::ROOT_ID, $rootDepartments)){
            if (isset($rootDepartments[Department::ROOT_ID] )){
                $session->set('rootRights', true );
                if (! $session->get('rootDepartments')){
                    $session->set('rootDepartments', $rootDepartments);
                }
            } else {
                $session->set('rootRights', false );
                if (! $session->get('rootDepartments')){
                    $session->set('rootDepartments', $rootDepartments);
                }
                if (! $session->get('departmentsAvailable')){
                    $departmentsAvailable = $user->departmentsAvailable;
                    $departmentsAvailable[] = Department::ROOT_ID;
                    $session->set('departmentsAvailable', $departmentsAvailable);
                }
                if (! $session->get('parentsDepartments')){
                    $session->set('parentsDepartments', $parentsDepartments);
                }

            }
        }
    }

    /**
     * !!! ADMINX Возвращает аутентификационные данные пользователя из сессии
     * если $renew - по предварительное обновление
     * @param string $item  userPermissions || userRoutes || userRoles
     * @return array
     */
    static public function getUserAutentificationData($item, $renew=false)
    {
        $result = [];
        if ($renew){
            self::saveUserPermissionsToSession(true);
        }
        $session = \Yii::$app->session;
        if ($session->has($item)){
            $result = $session->get($item);
        }
        return $result;
    }

    /**
     * !!! Возвращает массив разрешений текущего пользователя
     * @return array
     */
    static public function getUserPermissions()
    {
        $userPermissions = [];
        $session = \Yii::$app->session;
        if ($session->has('userPermissions')){
            $userPermissions = $session->get('userPermissions');
        }
        return $userPermissions;
    }

    /**
     * !!! Возвращает корневые подразделения, доступные текущему пользователю
     * @param integer $department_id - с очисткой
     * @return array
     */
    static public function getRootDepartments()
    {
        $rootDepartments = [];
        $session = \Yii::$app->session;
        if ($session->has('rootDepartments')){
            $rootDepartments = $session->get('rootDepartments');
            /*
            $rootDepartments[DepartmentCommon::ROOT_ID] = [
                'can_department' => false,
                'can_position' => false,
                'can_personal' => false,
            ];
            */

        }
        return $rootDepartments;
    }

    /**
     * !!! Возвращает родителей корневых подразделения, доступных текущему пользователю
     * @return array
     */
    static public function getParentsDepartments()
    {
        $rootDepartments = [];
        $session = \Yii::$app->session;
        if ($session->has('parentsDepartments')){
            $rootDepartments = $session->get('parentsDepartments');
            /*
            $rootDepartments[DepartmentCommon::ROOT_ID] = [
                'can_department' => false,
                'can_position' => false,
                'can_personal' => false,
            ];
            */

        }
        return $rootDepartments;
    }

    /**
     * !!! Пытается найти  $_GET, $_POST идентификаторы приказа, додатка, подразделения или должности
     * чтобы потом проверить доступность
     * для этого достаточно идентификатора приказа, если его нет- додатка, если его нет -подр и т.д.
     * @return array
     */
    static public function getProjectItemsIds() {
        $result = [
            'staff_order_main_id' => 0,
            'staff_order_id' => 0,
            'department_id' => 0,
            'position_id' => 0,
        ];
        $_post      = \yii::$app->request->post();
        $_get       = \yii::$app->request->get();
        //-- наличие ИД приказа (проекта или предложения)
        if (isset($_post['StaffOrderMain']) && isset($_post['StaffOrderMain']['id'])){
            $result['staff_order_main_id'] = $_post['StaffOrderMain']['id'];
            return $result;
        }
        if (isset($_post['StaffOrder']) && !empty($_post['StaffOrder']['staff_order_main_id'])){
            $result['staff_order_main_id'] = $_post['StaffOrder']['staff_order_main_id'];
            return $result;
        }
        if (isset($_post['staffOrderMain_id'])){
            $result['staff_order_main_id'] = $_post['staffOrderMain_id'];
            return $result;
        }
        if (isset($_get['staffOrderMain_id'])){
            $result['staff_order_main_id'] = $_get['staffOrderMain_id'];
            return $result;
        }

        //-- наличие ИД додатка к приказу (проекта или предложения)
        if (isset($_post['StaffOrder']) && !empty($_post['StaffOrder']['id'])){
            $result['staff_order_id'] = $_post['StaffOrder']['id'];
            return $result;
        }
        if (isset($_post['staffOrder_id'])){
            $result['staff_order_id'] = $_post['staffOrder_id'];
            return $result;
        }
        if (isset($_get['staffOrder_id'])){
            $result['staff_order_id'] = $_get['staffOrder_id'];
            return $result;
        }

        //-- наличие ИД подразделения
        if (isset($_post['OrderProjectDepartment']) && !empty($_post['OrderProjectDepartment']['id'])){
            $result['department_id'] = $_post['OrderProjectDepartment']['id'];
            return $result;
        }
        if (isset($_post['OrderProjectDepartment']) && !empty($_post['OrderProjectDepartment']['parent_id'])){
            $result['department_id'] = $_post['OrderProjectDepartment']['parent_id'];
            return $result;
        }
        if (isset($_post['OrderProjectDepartment']) && !empty($_post['OrderProjectDepartment']['node1'])){
            $result['department_id'] = $_post['OrderProjectDepartment']['node1'];
            return $result;
        }
        if (isset($_post['department_id'])){
            $result['department_id'] = $_post['department_id'];
            return $result;
        }
        if (isset($_post['parent_id'])){
            $result['department_id'] = $_post['parent_id'];
            return $result;
        }
        if (isset($_post['node1'])){
            $result['department_id'] = $_post['node1'];
            return $result;
        }
        if (isset($_post['node2'])){
            $result['department_id'] = $_post['node2'];
            return $result;
        }
        if (isset($_get['department_id'])){
            $result['department_id'] = $_get['department_id'];
            return $result;
        }
        if (isset($_get['parent_id'])){
            $result['department_id'] = $_get['parent_id'];
            return $result;
        }

        //-- наличие ИД должности
        if (isset($_post['position_id'])){
            $result['position_id'] = $_post['position_id'];
            return $result;
        }
        if (isset($_get['position_id'])){
            $result['position_id'] = $_get['position_id'];
            return $result;
        }
        if (isset($_post['OrderProjectPosition']) && isset($_post['OrderProjectPosition']['order_project_department_id'])){
            $result['department_id'] = $_post['OrderProjectPosition']['order_project_department_id'];
            return $result;
        }
        return $result;
    }








    /**
     * !!! @deprecated Возвращает массив разрешений текущего пользователя
     * @return array
     */
    static public function getUserAssigments(){
        $userAssigments = [];
        $session = \Yii::$app->session;
        if ($session->has('userPermissions')){
            $userAssigments = $session->get('userPermissions');
        }
        return $userAssigments;
    }

    /**
     * @deprecated Добавление в сессию подразделеня, доступного пользователю
     * @param bool $refresh - с очисткой
     */
    static public function addUserDepartmentsToSession($department_id){
        $session = \Yii::$app->session;
        if ($departmentsAvailable = $session->get('departmentsAvailable')){
            $departmentsAvailable[] = $department_id;
        } else {
            $departmentsAvailable = [];
            $departmentsAvailable[] = $department_id;
        }
        $session->set('departmentsAvailable', $departmentsAvailable);
    }

    /**
     * @deprecated Определение доступности подразделения текущему пользователю
     * @param integer $department_id - с очисткой
     * @return bool
     */
    static public function departmentAvailable($department_id){
        $session = \Yii::$app->session;
        $rootRights = $session->get('rootRights');
        if ($rootRights){
            return true;
        }
        if ($session->has('departmentsAvailable')){
            $dIds = $session->get('departmentsAvailable');
            return (in_array($department_id,$dIds));
        }
        return false;
    }

    /**
     * @deprecated Возвращает все подразделения, доступные текущему пользователю
     * @param integer $department_id - с очисткой
     * @return array
     */
    static public function getDepartmentsAvailable(){
        $departmentsAvailable = [];
        $session = \Yii::$app->session;
        if ($session->has('departmentsAvailable')){
            $departmentsAvailable = $session->get('departmentsAvailable');
        }
        return $departmentsAvailable;
    }

    /**
     * @deprecated Определение наличия у юсера подписки на корневой элемент
     * @param integer $department_id - с очисткой
     * @return bool
     */
    static public function hasRootRights(){
        $session = \Yii::$app->session;
        $rootRights = $session->get('rootRights');
        return $rootRights;
    }






    static public function isOrgstat(){
        $user_id = \yii::$app->user->getId();
        $userDepartments = UserDepartment::find()
            ->where(['user_id' => $user_id])
            ->andWhere('can_department OR can_position')
            ->count();
        if (isset($userDepartments) && $userDepartments>0){
            return true;
        }
        return false;
    }

    static public function isComplect(){
        $user_id = \yii::$app->user->getId();
        $userDepartments = UserDepartment::find()
            ->where(['user_id' => $user_id])
            ->andWhere('can_personal')
            ->count();
        if (isset($userDepartments) && $userDepartments>0){
            return true;
        }
        return false;
    }






    /**
     * Пытается найти $fieldName в фильтрах, $_GET, $_POST
     * @param string $fieldName Имя поля, по которому производить поиск
     * @return integer|null
     */
    static public function searchParam($fieldName = "department_id") {
        $result = null;
        $_get       = \yii::$app->request->get();
        $_post      = \yii::$app->request->post();
        if (isset($_get["filter"]) && array_key_exists($fieldName,
                $_get["filter"])) {
            $result = $_get["filter"][$fieldName];
        } elseif (isset($_get[$fieldName])) {
            $result = $_get[$fieldName];
        } elseif (isset($_post[$fieldName])) {
            $result = $_post[$fieldName];
        }
        return $result;
    }

    /**
     * Проверка наличия кода юсера у подразделений проекта, дерева или их предков
     * @param $department_id
     * @param $code
     * @return bool
     */
    static public function checkCode______($department_id, $code){
        $department = DepartmentCommon::findOne($department_id);
        if (isset($department)){
            if ($department->code == $code){
                return true;
            }
            if (DepartmentCommon::checkParentsCodes($department_id, $code)){
                return true;
            }
        }
        $department = OrderProjectDepartment::findOne($department_id);
        if (isset($department)){
            if ($department->code == $code){
                return true;
            }
            if (OrderProjectDepartment::checkParentsCodes($department_id, $code)){
                return true;
            }
        }
        return false;
    }

    /**
     * Проверка соответствия кода юсера коду подразделения, с которым будет работать екшен
     * если в гет и пост нет параметра department_id - возвращаем тру
     * @param $user_id
     * @return bool
     */
    static public function userCodeEqDepartmentCode______($user_id){
        $user = User::findOne($user_id);
        if (isset($user)){
            $department_id = static::searchParam('department_id');
            if (isset($department_id)){
                return static::checkCode($department_id, $user->code);
            } else {
                return true;
            }
        }
        return false;
    }

    /**
     * Осуществляет проверку прав доступа.
     * 
     * @param string|array $roles
     * @example "roleName"
     * @example ["roleName", "permissionName"]
     * @example ["roleName", "permissionName" => ["paramName" => "paramValue"]]
     * 
     * @param array $params
     * @return boolean
     */
    public static function checkAccess($roles = "", $params = []) {
        if (is_string($roles)) {
            return \yii::$app->user->can($roles, $params);
        }
        if (is_array($roles)) {
            $checkResult = false;
            foreach ($roles as $key => $role) {
                if (is_scalar($role)) {
                    $checkResult = $checkResult | static::checkAccess($role,
                                    $params);
                } else {
                    $checkResult = $checkResult | static::checkAccess($key,
                                    $role);
                }
                if ($checkResult)
                    return true;
            }
        }
        return $checkResult;
    }

}
