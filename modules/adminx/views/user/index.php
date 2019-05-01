<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;
use \app\components\widgets\xlegrid\Xlegrid;


$this->title = \Yii::t('app', 'Пользователи');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6" align="left">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="col-md-6" align="right" style="padding-top: 20px">
            <?php
            echo Html::a(\Yii::t('app', 'Создать'), '/adminx/user/signup-by-admin', [
                'class' =>'btn btn-primary',
            ]);
            ?>
        </div>

    </div>
    <div class="row">
        <?php Pjax::begin(['id' => 'gridUsers']);
        echo Xlegrid::widget([
            'dataProvider' => $dataProvider,
            'gridTitle' => '',
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
                'email',
                [
                    'label'=> \Yii::t('app', 'Статус'),
                    'content'=>function($data){
                        return \Yii::t('app', \app\modules\adminx\models\UserM::STATUS_DICT[$data->status]);
                    },
                ],

                'lastRoutTime',
                'lastRout',
                ['class' => 'yii\grid\ActionColumn',
                    'buttons'=>[
                        'delete'=>function($url, $data) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/adminx/user/delete', 'id' => $data['id'] ],
                                [
                                    'title' => \Yii::t('app', 'Удалить'),
                                    'data-confirm' => \Yii::t('app', 'Удалить'),
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




