<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


?>
<div class="user-search container-fluid" style="background-color: lightgrey">
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
        'id' => 'postFilterForm',
        // 'layout' => 'horizontal',
    ]);
    ?>
    <div class="row">
        <div class="col-md-6">
            <?php
            echo $form->field($filter, 'type');
            echo $form->field($filter, 'name');
            ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group" align="center" style="padding: 20px">
            <?= Html::submitButton(\Yii::t('app', 'Фильтр'), ['class' => 'btn btn-primary', 'id' => 'subBtn']) ?>
            <?= Html::button(\Yii::t('app', 'Очистить фильтр'), [
                'class' => 'btn btn-danger',
                'id' => 'cleanBtn',
                'onclick' => 'cleanFilter();',
            ]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    function cleanFilter(){
        document.getElementById('postfilter-type').value = null;
        document.getElementById('postfilter-name').value = null;
        $("#subBtn").click();
    }
</script>


