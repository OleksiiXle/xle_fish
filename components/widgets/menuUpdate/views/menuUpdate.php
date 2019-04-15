<?php
$_csrfT = \Yii::$app->request->csrfToken;
$_menuData = \yii\helpers\Json::htmlEncode($menuData);
$this->registerJs("
    var _menuData  = {$_menuData};
    var _menu_id   = '{$menu_id}';
    var _csrfT     = '{$_csrfT}';
",\yii\web\View::POS_HEAD);

//\app\components\widgets\menuUpdate\MenuUpdateAssets::register($this);
/*
$this->registerJs("
    var {$menu_id} = Object.create(MENU_TREE);
    {$menu_id}.init(_menu_id);
 ",\yii\web\View::POS_LOAD);
*/



//$this->registerJsFile($this->render('init.js'), [\yii\web\View::POS_END] );
/*
$this->registerJsFile($this->render('init.js'), [
        'depends' => ['yii\web\JqueryAsset', 'yii\bootstrap\BootstrapAsset'],
        'position' => [\yii\web\View::POS_END]
] );
*/

?>




<div class="container-fluid">
    <div class="row">
        <div id="<?=$menu_id;?>" class="xtree">

        </div>
    </div>
</div>
