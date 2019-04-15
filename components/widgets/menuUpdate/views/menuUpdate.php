<?php
use yii\helpers\Html;
use yii\helpers\Url;


$_csrfT = \Yii::$app->request->csrfToken;
$_params = \yii\helpers\Json::htmlEncode($params);
$this->registerJs("
    var _params_$menu_id  =    {$_params};
    var _menu_id          = '{$menu_id}';
    var _csrfT            = '{$_csrfT}';
",\yii\web\View::POS_HEAD);


?>




<div class="container-fluid">
    <div class="row">
        <div id="<?=$menu_id;?>" class="xtree">

        </div>
    </div>
    <?php if ($params['mode'] === 'update'):?>
        <div class="row" align="center">
            <?php
            echo Html::button('<span class="glyphicon glyphicon-plus"></span>', [
                'title' => 'Добавить потомка',
                'class' => '',
                'onclick' => 'modalOpenDepartmentCreate("appendChild");'
            ]);
            echo Html::button('<span class="glyphicon glyphicon-download-alt"></span>', [
                'title' => 'Добавить брата вниз',
                'class' => '',
                'onclick' => 'modalOpenDepartmentCreate("appendBrother");'
            ]);
            echo Html::button('<span class="glyphicon glyphicon-thumbs-up"></span>', [
                'title' => 'Поднять на уровень выше',
                'class' => '',
                'onclick' => 'treeModifyAuto("levelUp");'
            ]);
            echo Html::button('<span class="glyphicon glyphicon-thumbs-down"></span>', [
                'title' => 'Опустить на уровень вниз',
                'class' => 'noRootAction noProjectRootAction',
                'onclick' => 'treeModifyAuto("levelDown", 1);'
            ]);
            echo Html::button('<span class="glyphicon glyphicon-menu-up"></span>', [
                'title' => 'Поднять в своем уровне',
                'class' => '',
                'onclick' => 'treeModifyAuto("moveUp");'
            ]);
            echo Html::button('<span class="glyphicon glyphicon-menu-down"></span>', [
                'title' => 'Опустить в своем уровне',
                'class' => '',
                'onclick' => 'treeModifyAuto("moveDown");'
            ]);
            echo Html::button('<span class="glyphicon glyphicon-refresh"></span>', [
                'title' => 'Удалить вместе с потомками',
                'class' => '',
                'onclick' => 'recalcDepartment(1);'
            ]);
            ?>
        </div>
    <?php endif;?>
</div>
