<?php
use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use \app\modules\adminx\models\AuthItemX;

?>

<?php

\app\modules\adminx\assets\AdminxUpdateAuthItemAsset::register($this);
//$_assigments = \yii\helpers\Json::htmlEncode($assigments);
/*
$this->registerJs("
    var _assigments = {$_assigments};
");
*/

$this->title = 'Разрешения';

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6" align="left">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="col-md-6" align="right" style="padding: 20px">
            <?php
            echo Html::a('Новая роль', ['/adminx/auth-item/create', 'type' => AuthItemX::TYPE_ROLE],
                [
                'class' =>'btn btn-primary',
            ]);
            echo '  ';
            echo Html::a('Новое разрешение', ['/adminx/auth-item/create', 'type' => AuthItemX::TYPE_PERMISSION], [
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
            'gridTitle' => 'Разрешения и роли',
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
                                $ret = 'Роль';
                                break;
                            case AuthItemX::TYPE_PERMISSION:
                                $ret = 'Разрешение';
                                break;
                        }
                        return $ret;
                    },
                ],

                'name',
                [
                    'attribute'=>'description',
                    'label'=>'Описание',
                ],
                [
                    'attribute'=>'rule_name',
                    'label'=>'Правило',
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'buttons'=>[
                        'update'=>function($url, $data) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                                \yii\helpers\Url::toRoute(['/adminx/auth-item/update',
                                    'name' => $data['name'],

                                ]  ),
                                [
                                    'title' => 'Видалити',
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

