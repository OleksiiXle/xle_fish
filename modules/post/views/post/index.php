<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;
use \app\components\widgets\xlegrid\Xlegrid;


$this->title = \Yii::t('app', 'Посты');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6" align="left">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="col-md-6" align="right" style="padding-top: 20px">
            <?php
            echo Html::a(\Yii::t('app', 'Создать'), '/post/post/create', [
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
            'filterView' => '@app/modules/post/views/post/_filterPost',
            //-------------------------------------------
            'tableOptions' => [
                'class' => 'table table-bordered table-striped ',
            ],
            //-------------------------------------------
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'name',
             //   'content',
                'ownerLastName',
                'ownerUsername',
                'created',
                [
                    'label'=> \Yii::t('app', 'Тип'),
                    'content'=>function($data){
                        return \app\modules\post\models\Post::getTypeStr($data->type);
                    },
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'buttons'=>[
                        'update'=>function($url, $data) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/post/post/update', 'id' => $data['id'] ],
                                [
                                    'title' => 'Изменить',
                                ]);

                        },
                        'delete'=>function($url, $data) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/post/post/delete', 'id' => $data['id'] ],
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

