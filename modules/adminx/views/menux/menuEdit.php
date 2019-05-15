<?php

use \yii\helpers\Html;

//\app\components\widgets\menuUpdate\MenuUpdateAssets::register($this);
\app\modules\adminx\assets\AdminxMenuAsset::register($this);

$this->title = \Yii::t('app', 'Редактор меню');

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6" align="left">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6" style="background-color: aliceblue">
            <b>
                <?php
                echo \app\components\widgets\menuUpdate\MenuUpdateWidget::widget([
                    'menu_id' => 'NumberOne',
                    'params' => [
                            'mode' => 'update'
                    ]
                ])
                ?>
            </b>
        </div>
        <div class="col-md-6">
            <div id="menuInfo">

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready ( function(){
        initTrees();
    });

</script>

