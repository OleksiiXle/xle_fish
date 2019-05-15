<?php
echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'parent_id',
        'sort',
        [
            'label' => \Yii::t('app', 'Название'),
            'value' => function () use ($model){
                return \Yii::t('app', $model->name);
            },
            'format' => 'html',
        ],

        'route',
        'role',
    ],
]);
?>
