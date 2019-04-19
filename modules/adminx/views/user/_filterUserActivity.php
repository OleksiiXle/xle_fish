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
            echo $form->field($filter, 'userFam');
            echo $form->field($filter, 'username');
            echo $form->field($filter, 'userDirection', ['inputOptions' =>
                ['class' => 'form-control', 'tabindex' => '4']])
                ->dropDownList(\app\modules\adminx\models\UserData::$directionArray,
                    ['options' => [ $filter->userDirection => ['Selected' => true]],]);
            echo $form->field($filter, 'userGunp', ['inputOptions' =>
                ['class' => 'form-control', 'tabindex' => '4']])
                ->dropDownList(\app\modules\adminx\models\UserDepartment::getDictionaryDepartment(),
                    ['options' => [ $filter->userGunp => ['Selected' => true]],]);
            ?>
        </div>

    </div>
    <div class="row">
        <div class="form-group" align="center" style="padding: 20px">
            <?= Html::submitButton('Шукати', ['class' => 'btn btn-primary', 'id' => 'subBtn']) ?>
            <?= Html::button('Очистити фільтр', [
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
   //     document.getElementById('userfilter-last_name').value = null;   #useractivityfilter-userfam
        document.getElementById('useractivityfilter-userfam').value = null;
        document.getElementById('useractivityfilter-username').value = null;
        document.getElementById('useractivityfilter-userdirection').value = 0;
        document.getElementById('useractivityfilter-usergunp').value = 0;

        $("#subBtn").click();
    }
</script>


