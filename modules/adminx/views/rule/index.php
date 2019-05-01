<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;


$this->title = \Yii::t('app', 'Правила');
?>
<div class="role-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a(\Yii::t('app', 'Создать'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',

            ],

        ],
    ]);
    ?>

</div>
