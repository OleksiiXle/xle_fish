<?php

namespace app\commands;

use app\modules\adminx\models\form\Signup;
use app\modules\adminx\models\form\Update;
use app\modules\adminx\models\MenuX;
use app\modules\adminx\models\UserData;
use app\modules\adminx\models\UserM;
use Yii;
use yii\console\Controller;
use yii\console\Exception;
use app\modules\adminx\models\User;

/**
 * Class RbacController
 * Инициализация прав доступа
 * Права доступа
 * @package app\commands
 */
class RbacController extends Controller
{
    public function actionInit(){
        $this->actionRemoveAll();
        $this->actionAssignmentsInit();
        $this->actionMenuInit();
        $this->actionUserInit();
    }

    public function actionAssignmentsInit(){
        $params = require(__DIR__ . '/data/rbacInit.php');
        $permissions      = $params['permissions'];
        $roles            = $params['roles'];
        $rolesPermissions = $params['rolesPermissions'];
        $rolesChildren    = $params['rolesChildren'];
        $auth = \Yii::$app->authManager;

        //-- добавляем роли, которых не было
        echo 'РОЛИ *******************************' .PHP_EOL;
        foreach ($roles as $roleName => $roleNote){
            echo '* роль * ' . $roleName ;
            $checkRole = $auth->getRole($roleName);
            if (!isset($checkRole)){
                echo ' добавляю' ;
                $newRole = $auth->createRole($roleName);
                $newRole->description = $roleNote;
                if ($auth->add($newRole)){
                    echo ' OK ' . PHP_EOL;
                } else {
                    echo ' ERROR ' . PHP_EOL;
                    return false;
                }
            } else {
                echo ' уже есть' . PHP_EOL;
            }
        }
        //-- добавляем разрешения, которых не было
        echo 'РАЗРЕШЕНИЯ *******************************' .PHP_EOL;
        foreach ($permissions as $permission => $description){
            echo '* дозвіл * ' . $permission ;
            $checkRole = $auth->getPermission($permission);
            if (!isset($checkRole)){
                echo ' добавляю';
                $newPermission = $auth->createPermission($permission);
                $newPermission->description = $description;
                if ($auth->add($newPermission)){
                    echo ' OK ' . PHP_EOL;
                } else {
                    echo ' ERROR ' . PHP_EOL;
                    return false;
                }

            } else {
                echo ' уже есть' . PHP_EOL;
            }
        }
        //-- добавляем ролям детей, которых не было
        echo 'ДЕТИ РОЛЕЙ *******************************' .PHP_EOL;
        foreach ($rolesChildren as $role => $children){
            echo '* діти ролі * ' . $role . PHP_EOL;
            $parentRole = $auth->getRole($role);
            foreach ($children as $child){
                echo ' добавляю' . ' ' . $child ;
                try{
                    $childRole = $auth->getRole($child);
                    if ($auth->addChild($parentRole, $childRole)){
                        echo ' OK ' . PHP_EOL;
                    } else {
                        echo ' ERROR ' . PHP_EOL;
                        return false;
                    }

                } catch (\yii\base\Exception $e){
                    echo ' мабуть вже є така дитинка' . ' ' . $child . PHP_EOL;
                }
            }

        }
        //-- добавляем ролям разрешения, которых не было
        echo 'РАЗРЕШЕНИЯ РОЛЕЙ *******************************' .PHP_EOL;
        foreach ($rolesPermissions as $role => $permission){
            echo '* дозвіли ролі * ' . $role . PHP_EOL;
            $parentRole = $auth->getRole($role);
            foreach ($permission as $perm){
                echo ' добавляю' . ' ' . $perm ;
                try{
                    $rolePermission = $auth->getPermission($perm);
                    if (isset($rolePermission)){
                        ;
                        if ($auth->addChild($parentRole, $rolePermission)){
                            echo ' OK ' . PHP_EOL;
                        } else {
                            echo ' ERROR ' . PHP_EOL;
                            return false;
                        }
                    } else {
                        echo ' упс... такого дозвілу ще немає' . PHP_EOL;
                        exit();
                    }
                } catch (\yii\base\Exception $e){
                    echo ' мабуть вже є така дозвіл' . ' ' . $perm . PHP_EOL;
                }
            }

        }

    }

    public function actionUserInit(){
        $users = require(__DIR__ . '/data/rbacDefaultUsers.php');
        $auth = Yii::$app->authManager;
        foreach ($users as $user){
            echo $user['username'] . PHP_EOL;
            //   echo var_dump($user);
            $model = new UserM();
            //   $model->scenario = User::SCENARIO_REGISTRATION;
            $model->setAttributes($user);
            $model->setPassword($user['password']);
            $model->generateAuthKey();
            if (!$model->save()){
                echo var_dump($model->getErrors()) . PHP_EOL;
                return false;
            }
            $userData = new UserData();
            $userData->setAttributes($user);
            $userData->user_id = $model->id;
            if (!$userData->save()){
                echo var_dump($userData->getErrors()) . PHP_EOL;
                return false;
            }
            foreach ($user['userRoles'] as $role){
                $userRole = $auth->getRole($role);
                if (isset($userRole)){
                    $auth->assign($userRole, $model->id);
                    echo '   ' . $role . PHP_EOL;
                } else {
                    echo '   не найдена роль - ' . $role . PHP_EOL;
                }

            }
        }
        return true;
    }

    public function actionMenuInit() {
        $menus = require(__DIR__ . '/data/menuInit.php');
        $sort1 = $sort2 = 1;
        foreach ($menus as $menu){
            echo $menu['name'] . PHP_EOL;
            if ($menu['name'] != 'menu_guest'){
                $m = new MenuX();
                $m->parent_id = 0;
                $m->sort = $sort1++;
                $m->name = $menu['name'];
                $m->route = '';//$menu['route'];
                $m->role = $menu['permission'];
                if (!$m->save()){
                    echo var_dump($m->getErrors()) . PHP_EOL;
                    return true;
                }
                echo '     ' . $menu['children']['name'] . PHP_EOL;
                $mch = new MenuX();
                $mch->parent_id = $m->id;
                $mch->sort = $sort2++;
                $mch->name = $menu['children']['name'];
                $mch->route = '';//$menu['route'];
                $mch->role = $menu['children']['permission'];
                if (!$mch->save()){
                    echo var_dump($mch->getErrors()) . PHP_EOL;
                    return true;
                }
                $sort3 = 1;
                foreach ($menu['children']['children'] as $child){
                    echo '         ' . $child['name'] . PHP_EOL;
                    $ch = new MenuX();
                    $ch->parent_id = $mch->id;
                    $ch->sort = $sort3++;
                    $ch->name = $child['name'];
                    $ch->route = $child['route'];
                    $ch->role = $child['permission'];
                    if (!$ch->save()){
                        echo var_dump($ch->getErrors()) . PHP_EOL;
                        return true;
                    }
                }
            } else {
                $m = new MenuX();
                $m->parent_id = 0;
                $m->sort = $sort1++;
                $m->name = $menu['name'];
                $m->route = '';//$menu['route'];
                $m->role = $menu['permission'];
                if (!$m->save()){
                    echo var_dump($m->getErrors()) . PHP_EOL;
                    return true;
                }
                echo '     ' . $menu['name'] . PHP_EOL;
                $sort3 = 1;
                foreach ($menu['children'] as $child){
                    echo var_dump($child) . PHP_EOL;
                    echo '         ' . $child['name'] . PHP_EOL;
                    $ch = new MenuX();
                    $ch->parent_id = $m->id;
                    $ch->sort = $sort3++;
                    $ch->name = $child['name'];
                    $ch->route = $child['route'];
                    $ch->role = $child['permission'];
                    if (!$ch->save()){
                        echo var_dump($ch->getErrors()) . PHP_EOL;
                        return true;
                    }
                }

            }
        }
        return true;
    }

    public function actionRemoveAll()
    {
        //--- очистить таблицы MenuX, User, удалить все рполи и разрешения
        echo '************************************************************************** ОЧИСТКА ДАННЫХ' . PHP_EOL;
        $delCnt = UserM::deleteAll();
        echo 'Удалено ' . $delCnt . ' пользователей ' .PHP_EOL;
        $delCnt = MenuX::deleteAll();
        echo 'Удалено ' . $delCnt . ' пунктов меню ' .PHP_EOL;
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        echo 'Удалены все роли и разрешения' .PHP_EOL;
        $a = \Yii::$app->db->createCommand('ALTER TABLE user AUTO_INCREMENT=1')->execute();
        $a = \Yii::$app->db->createCommand('ALTER TABLE auth_rule AUTO_INCREMENT=1')->execute();
        $a = \Yii::$app->db->createCommand('ALTER TABLE auth_item AUTO_INCREMENT=1')->execute();
        $a = \Yii::$app->db->createCommand('ALTER TABLE auth_item_child AUTO_INCREMENT=1')->execute();
        $a = \Yii::$app->db->createCommand('ALTER TABLE auth_assignment AUTO_INCREMENT=1')->execute();
        $a = \Yii::$app->db->createCommand('ALTER TABLE menu_x AUTO_INCREMENT=1')->execute();

    }



}