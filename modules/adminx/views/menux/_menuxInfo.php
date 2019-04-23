<?php
echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'parent_id',
        'sort',
        'name',
        'route',
        'role',
    ],
]);
?>
