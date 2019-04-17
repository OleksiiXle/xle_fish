<?php

namespace app\components\widgets\menuUpdate;

use app\components\widgets\menuUpdate\models\MenuX;
use yii\base\Widget;
use yii\helpers\Url;
use yii\web\View;

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
       // $url = Url::toRoute('@app/components/widgets/menuUpdate/assets/js/xtree.js');
    //    $view->registerJsFile($url, [], $this->menu_id);

        return $this->render('menuUpdate',
            [
                'menu_id' => $this->menu_id,
                'params' => $this->params,
            ]);
    }

}
