<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \yii\helpers\Url;

//\app\modules\adminx\assets\AdminxUserFilterAsset::register($this);

?>
<div class="user-search container-fluid" style="background-color: lightgrey">
    <?php
    $form = ActiveForm::begin([
        'action' => ['guest-control'],
        'method' => 'post',
        'id' => 'uControlFilterForm',
        // 'layout' => 'horizontal',
    ]);
    ?>
    <div class="row">
        <div class="col-md-6">
            <?php
            echo $form->field($filter, 'username');
            echo $form->field($filter, 'remote_ip');
            ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group" align="center" style="padding: 20px">
            <?= Html::submitButton('Искать', ['class' => 'btn btn-primary', 'id' => 'subBtn']) ?>
            <?= Html::button('Очистить фильтр', [
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
        document.getElementById('ucontrolfilter-username').value = null;
        document.getElementById('ucontrolfilter-remote_ip').value = null;
        $("#subBtn").click();
    }
</script>


