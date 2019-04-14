<?php

namespace app\widgets\menuX;
use app\widgets\menuX\MenuXAssets;
use app\widgets\menuX\models\MenuX;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\InputWidget;

class MenuXWidget extends InputWidget
{


    public function init()
    {
       // parent::init();
    }

    public function run()
    {
        $i=1;
        if (\Yii::$app->user->isGuest){
            $menus = [''];
        } else {
            $user_id = \Yii::$app->user->getId();
          //  $userAssignments = \Yii::$app->getAuthManager()->getAssignments($user_id);
            $userAssignments = \Yii::$app->getAuthManager()->getPermissionsByUser($user_id);
            $menus = ['menuAll'];
            foreach ($userAssignments as $name => $data){
                if (substr($name, 0,4) === 'menu'){
                    $menus[] = $name;
                }
            }
        }
        MenuXAssets::register($this->getView());
        $tree = MenuX::find()
            ->orderBy('parent_id, sort')
            ->where(['in', 'role', $menus])
            ->asArray()
            ->all();
        $html = MenuX::getTree($tree,0);

        return $this->render('menuX',
            [
                'html' => $html,
            ]);
    }

}
