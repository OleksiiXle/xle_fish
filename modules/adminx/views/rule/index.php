<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;


$this->title = 'Правила';
?>
<div class="role-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Новое правило', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'label' => 'Название',
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',

            ],

        ],
    ]);
    ?>

</div>
