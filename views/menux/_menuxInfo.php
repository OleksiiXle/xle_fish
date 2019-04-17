<?php
echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'sort',
        'name',
        'route',
        'role',
    ],
]);
?>
