<?php

namespace app\components\widgets\menuUpdate;

use app\components\widgets\menuUpdate\models\MenuX;
use yii\base\Widget;

class MenuUpdateWidget extends Widget
{
    public $menu_id;
    public $params;


    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $view = $this->getView();
        MenuUpdateAssets::register($view);

        return $this->render('menuUpdate',
            [
                'menu_id' => $this->menu_id,
                'params' => $this->params,
            ]);
    }

}
