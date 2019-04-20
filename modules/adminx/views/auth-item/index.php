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

$this->title = 'Дозвіли';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6" align="left">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-6" align="right" style="padding: 20px">
            <?php
            echo Html::a('Нова роль', ['/adminx/auth-item/create', 'type' => AuthItemX::TYPE_ROLE],
                [
                'class' =>'btn btn-primary',
            ]);
            echo '  ';
            echo Html::a('Новий дозвіл', ['/adminx/auth-item/create', 'type' => AuthItemX::TYPE_PERMISSION], [
                'class' =>'btn btn-primary',
            ]);
            echo '  ';
            echo Html::a('Додати маршрути', '/adminx/route', [
                'class' =>'btn btn-primary',
            ]);
            ?>
        </div>

    </div>
    <div class="row">
        <?php Pjax::begin(['id' => 'gridPermission']);
        echo \app\components\widgets\xlegrid\Xlegrid::widget([
            'dataProvider' => $dataProvider,
            'gridTitle' => 'Дозвіли',
            'additionalTitle' => 'qq',
            'filterView' => '@app/modules/adminx/views/auth-item/_authItemFilter',
            //-------------------------------------------
            'tableOptions' => [
                'class' => 'table table-bordered table-striped ',
            ],
            //-------------------------------------------
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                [
                    'attribute'=>'description',
                    'label'=>'Описання',
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

