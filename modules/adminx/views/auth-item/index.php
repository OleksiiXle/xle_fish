<?php
use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use \app\modules\adminx\models\AuthItemX;

?>

<?php

\app\modules\adminx\assets\AdminxUpdateAuthItemAsset::register($this);

$this->title = \Yii::t('app', 'Разрешения');

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6" align="left">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="col-md-6" align="right" style="padding: 20px">
            <?php
            echo Html::a(\Yii::t('app', 'Новая роль'), ['/adminx/auth-item/create', 'type' => AuthItemX::TYPE_ROLE],
                [
                'class' =>'btn btn-primary',
            ]);
            echo '  ';
            echo Html::a(\Yii::t('app', 'Новое разрешение'), ['/adminx/auth-item/create', 'type' => AuthItemX::TYPE_PERMISSION], [
                'class' =>'btn btn-primary',
            ]);
            echo '  ';
            ?>
        </div>

    </div>
    <div class="row">
        <?php Pjax::begin(['id' => 'gridPermission']);
        echo \app\components\widgets\xlegrid\Xlegrid::widget([
            'dataProvider' => $dataProvider,
            'gridTitle' => '',
            'additionalTitle' => 'qq',
            'filterView' => '@app/modules/adminx/views/auth-item/_authItemFilter',
            //-------------------------------------------
            'tableOptions' => [
                'class' => 'table table-bordered table-striped ',
            ],
            //-------------------------------------------
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label'=>'Тип',
                    'content'=>function($data){
                        $ret = '';
                        switch ($data->type){
                            case AuthItemX::TYPE_ROLE:
                                $ret = \Yii::t('app', 'Роль');
                                break;
                            case AuthItemX::TYPE_PERMISSION:
                                $ret = \Yii::t('app', 'Разрешение');
                                break;
                        }
                        return $ret;
                    },
                ],

                'name',
                'description',
                'rule_name',
                ['class' => 'yii\grid\ActionColumn',
                    'buttons'=>[
                        'update'=>function($url, $data) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                                \yii\helpers\Url::toRoute(['/adminx/auth-item/update',
                                    'name' => $data['name'],

                                ]  ),
                                [
                                    'title' => \Yii::t('app', 'Изменить'),
                                ]);

                        },
                    ],
                    'template'=>' {update}',

                ],
            ],
                //------------------------------

        ]);
        Pjax::end() ?>

    </div>
    <div class="row">

    </div>

</div>

