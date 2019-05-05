<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;
use \app\components\widgets\xlegrid\Xlegrid;


$this->title = \Yii::t('app', 'Посетители');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6" align="left">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
    </div>
    <div class="row">
        <?php Pjax::begin(['id' => 'gridGuest']);
        echo Xlegrid::widget([
            'dataProvider' => $dataProvider,
            'gridTitle' => '',
            'additionalTitle' => 'qq',
            'filterView' => '@app/modules/adminx/views/check/_filterUControl',
            //-------------------------------------------
            'tableOptions' => [
                'class' => 'table table-bordered table-striped ',
            ],
            //-------------------------------------------
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'user_id',
                'username',
                'remote_ip',
                'referrer',
                'remote_host',
                'absolute_url',
                'url',
                'created_at_str',
                /*
                [
                    'label'=>'Статус',
                    'content'=>function($data){
                        return \app\modules\adminx\models\UserM::STATUS_DICT[$data->status];
                    },
                ],
                */
                ['class' => 'yii\grid\ActionColumn',
                    'buttons'=>[
                        'view'=>function($url, $data) {
                            return Html::a('<span class="glyphicon glyphicon-eye"></span>', false,
                                [
                                    'title' => '',
                                ]);

                        },
                    ],
                    'template'=>' {view}',

                ],
                //------------------------------
            ],

        ]);
        Pjax::end() ?>
    </div>

</div>




