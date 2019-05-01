<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


?>
<div class="user-search container-fluid" style="background-color: lightgrey">
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
        'id' => 'userFilterForm',
        // 'layout' => 'horizontal',
    ]);
    ?>
    <div class="row">
        <div class="col-md-6">
            <?php
            echo $form->field($filter, 'last_name');
            echo $form->field($filter, 'first_name');
            echo $form->field($filter, 'middle_name');
            echo $form->field($filter, 'role', ['inputOptions' =>
                ['class' => 'form-control', 'tabindex' => '4']])
                ->dropDownList($filter->roleDict,
                    ['options' => [ $filter->role => ['Selected' => true]],]);
            ?>
        </div>
        <div class="col-md-6">
            <div class="row">
            </div>
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
        document.getElementById('userfilter-last_name').value = null;
        document.getElementById('userfilter-first_name').value = null;
        document.getElementById('userfilter-middle_name').value = null;
        document.getElementById('userfilter-role').value = null;
        $("#subBtn").click();
    }
</script>


