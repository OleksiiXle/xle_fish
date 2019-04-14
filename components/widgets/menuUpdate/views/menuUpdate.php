<?php
$_csrfT = \Yii::$app->request->csrfToken;
$_menuData = \yii\helpers\Json::htmlEncode($menuData);
$this->registerJs("
    var _menuData  = {$_menuData};
    var _menu_id   = '{$menu_id}';
    var _csrfT     = '{$_csrfT}';
",\yii\web\View::POS_HEAD);

?>




<div class="container-fluid">
    <div class="row">
        <div id="<?=$menu_id;?>">

        </div>
    </div>
</div>

