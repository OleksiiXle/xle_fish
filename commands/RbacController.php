<?php

namespace app\commands;

use app\modules\adminx\models\form\Signup;
use app\modules\adminx\models\form\Update;
use app\modules\adminx\models\MenuX;
use app\modules\adminx\models\UserData;
use app\modules\adminx\models\UserDepartment;
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

}