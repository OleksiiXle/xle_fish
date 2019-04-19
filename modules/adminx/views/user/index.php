<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;
use \app\components\widgets\xlegrid\Xlegrid;


$this->title = 'Користувачі';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6" align="left">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-6" align="right" style="padding-top: 20px">
            <?php
            echo Html::a('Рєєстрація нового користувача', '/adminx/user/signup', [
                'class' =>'btn btn-primary',
            ]);
            ?>
        </div>

    </div>
    <div class="row">
        <?php Pjax::begin(['id' => 'gridUsers']);
        echo Xlegrid::widget([
            'dataProvider' => $dataProvider,
            'gridTitle' => 'Працівники',
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
                'userDirection',
                'userRoles',
                'lastRoutTime',
                'lastRout',
                ['class' => 'yii\grid\ActionColumn',
                    'buttons'=>[
                        'delete'=>function($url, $data) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/adminx/user/delete', 'id' => $data['id'] ],
                                [
                                    'title' => 'Видалити',
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




