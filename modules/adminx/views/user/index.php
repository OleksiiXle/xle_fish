<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;
use \app\components\widgets\xlegrid\Xlegrid;


$this->title = 'Пользователи';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6" align="left">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="col-md-6" align="right" style="padding-top: 20px">
            <?php
            echo Html::a('Новый пользователь', '/adminx/user/signup', [
                'class' =>'btn btn-primary',
            ]);
            ?>
        </div>

    </div>
    <div class="row">
        <?php Pjax::begin(['id' => 'gridUsers']);
        echo Xlegrid::widget([
            'dataProvider' => $dataProvider,
            'gridTitle' => 'Пользователи',
            'additionalTitle' => 'qq',
            'filterView' => '@app/modules/adminx/views/user/_filterUser',
            //-------------------------------------------
            'tableOptions' => [
                'class' => 'table table-bordered table-striped ',
            ],
            //-------------------------------------------
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'username',
                'nameFam',
                'nameNam',
                'nameFat',
                'userRoles',
                'lastRoutTime',
                'lastRout',
                ['class' => 'yii\grid\ActionColumn',
                    'buttons'=>[
                        'delete'=>function($url, $data) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/adminx/user/delete', 'id' => $data['id'] ],
                                [
                                    'title' => 'Удалить',
                                    'data-confirm' => 'Подтвердите удаление',
                                    'data-method' => 'post',
                                ]);

                        },
                    ],
                    'template'=>' {update}  {delete}',

                ],
                //------------------------------
            ],

        ]);
        Pjax::end() ?>
    </div>

</div>




