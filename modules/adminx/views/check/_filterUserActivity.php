<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \yii\helpers\Url;

//\app\modules\adminx\assets\AdminxUserFilterAsset::register($this);

?>
<div class="user-search container-fluid" style="background-color: lightgrey">
    <?php
    $form = ActiveForm::begin([
      //  'action' => ['index'],
        'method' => 'post',
        'id' => 'userFilterForm',
        // 'layout' => 'horizontal',
    ]);
    ?>
    <div class="row">
        <div class="col-md-6">
            <?php
            echo $form->field($filter, 'activityInterval')
                ->dropDownList(\app\modules\adminx\models\UserData::$activityIntervalArray,
                    ['options' => [ $filter->activityInterval => ['Selected' => true]],]);
            echo $form->field($filter, 'username');
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
        document.getElementById('useractivityfilter-username').value = null;

        $("#subBtn").click();
    }
</script>


