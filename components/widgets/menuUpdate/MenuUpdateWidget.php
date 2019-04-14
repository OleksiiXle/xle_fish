<?php

namespace app\components\widgets\menuUpdate;

use app\components\widgets\menuUpdate\models\MenuX;
use yii\base\Widget;

class MenuUpdateWidget extends Widget
{
    public $menu_id;


    public function init()
    {
        MenuUpdateAssets::register($this->getView());

        parent::init();
    }

    public function run()
    {
        $menuData = MenuX::find()
            ->asArray()
            ->all();

        return $this->render('menuUpdate',
            [
                'menu_id' => $this->menu_id,
                'menuData' => $menuData,
            ]);
    }

}
