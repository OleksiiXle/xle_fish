<?php

use \yii\helpers\Html;

$this->title = \Yii::t('app', 'Активность пользователей');
//\app\modules\adminx\assets\AdminxUserActivityAsset::register($this);


?>
<?php
$interval = (empty($dataProvider->filterModel->getAttributes()['activityInterval']))
      ? 3600
      : $dataProvider->filterModel->getAttributes()['activityInterval'];
$timeFix = time() - $interval;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6" align="left">
            <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>
        </div>

    </div>
    <div class="row">

        <?php
        echo \app\components\widgets\xlegrid\Xlegrid::widget([
            'dataProvider' => $dataProvider,
            'gridTitle' => '' . \app\modules\adminx\models\UserData::$activityIntervalArray[$interval] ,
            'additionalTitle' => 'qq',
            'filterView' => '@app/modules/adminx/views/check/_filterUserActivity',
            //-------------------------------------------
            'tableOptions' => [
                'class' => 'table table-bordered table-striped ',
            ],
            //-------------------------------------------
            'columns' => [
                ['class' => 'yii\grid\SerialColumn',
                    'headerOptions' => ['style' => 'width:1%'],
                ],
                [
                    'attribute' => 'lastRoutTime',
                    'headerOptions' => ['style' => 'width:4%'],
                ],
                [
                    'attribute' => 'userFio',
                    'headerOptions' => ['style' => 'width:8%'],
                ],
                [
                    'attribute'=>'userLogin',
                    'headerOptions' => ['style' => 'width:4%'],
                    'content'=>function($data) use ($timeFix){
                    //    return Html::a($data->userLogin, '/adminx/user/update?id=' . $data->user_id);
                        return Html::a($data->userLogin, false,
                            [
                                   // 'onclick' => "modalOpenUserActivityInfo($data->user_id, $timeFix);",
                            ]);
                    },
                ],
                [
                    'attribute' => 'last_rout',
                    'headerOptions' => ['style' => 'width:4%'],
                ],
                [
                    'attribute' => 'lastRoutTime',
                    'headerOptions' => ['style' => 'width:4%'],
                ],
            ],

        ]);
        ?>

    </div>


</div>


<?php //***********************************  заготовки под модальные окна
//----- большое окно
yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader_lg','class'=>'text-center'],
    'id' => 'main-modal-lg',
    'closeButton' => ['tag' => 'button', 'label' => '&times;'],
    'size' => 'modal-lg',
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE],
    // 'options'=>['style'=>'width:1200px']
]);?>
<div id='modalContent_lg'></div>

<?php yii\bootstrap\Modal::end();?>


