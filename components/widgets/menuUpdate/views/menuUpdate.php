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
        <div id="actionButtons_<?=$menu_id;?>" class="row" align="center">
            <?php
            echo Html::button('<span class="glyphicon glyphicon-plus"></span>', [
                'title' => 'Добавить потомка',
                'id' => 'btn_' . $menu_id . '_appendChild',
                'class' => 'actionBtn ',
            ]);
            echo Html::button('<span class="glyphicon glyphicon-download-alt"></span>', [
                'title' => 'Добавить брата вниз',
                'id' => 'btn_' . $menu_id . '_appendBrother',
                'class' => 'actionBtn ',
            ]);
            echo Html::button('<span class="glyphicon glyphicon-thumbs-up"></span>', [
                'title' => 'Поднять на уровень выше',
                'id' => 'btn_' . $menu_id . '_levelUp',
                'class' => 'actionBtn ',
            ]);
            echo Html::button('<span class="glyphicon glyphicon-thumbs-down"></span>', [
                'title' => 'Опустить на уровень вниз',
                'id' => 'btn_' . $menu_id . '_levelDown',
                'class' => 'actionBtn ',
            ]);
            echo Html::button('<span class="glyphicon glyphicon-menu-up"></span>', [
                'title' => 'Поднять в своем уровне',
                'id' => 'btn_' . $menu_id . '_moveUp',
                'class' => 'actionBtn ',
            ]);
            echo Html::button('<span class="glyphicon glyphicon-menu-down"></span>', [
                'title' => 'Опустить в своем уровне',
                'id' => 'btn_' . $menu_id . '_moveDown',
                'class' => 'actionBtn ',
            ]);
            echo Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
                'title' => 'Изменить',
                'id' => 'btn_' . $menu_id . '_modalOpenMenuUpdate',
                'class' => 'actionBtn ',
            ]);
            echo Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                'title' => 'Удалить вместе с потомками',
                'id' => 'btn_' . $menu_id . '_deleteItem',
                'class' => 'actionBtn ',
            ]);
            ?>
        </div>
        <?php //***********************************  заготовки под модальные окна
        yii\bootstrap\Modal::begin([
            'headerOptions' => ['id' => 'modalHeader_md','class'=>'text-center'],
            'id' => 'main-modal-md',
            'size' => 'modal-md',
            'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE],
        ]);?>
        <div id='modalContent_md'></div>
        <?php yii\bootstrap\Modal::end();?>


    <?php endif;?>
</div>
