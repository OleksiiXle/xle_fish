<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DictionarySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-item-search" >
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
        'id' => 'authItemFilterForm',
        'layout' => 'horizontal',
    ]);
    ?>
    <div class="row">
        <div class="col-md-4">
            <?php
            echo $form->field($filter, 'type')
                ->dropDownList(\app\modules\adminx\models\AuthItemX::$typeDict,
                    ['options' => [ $filter->type => ['Selected' => true]],])->label('Тип');
            ?>
        </div>
        <div class="col-md-4">
            <?php
            echo $form->field($filter, 'name')->label('Найменування');
            ?>
        </div>
        <div class="col-md-4">
            <?php
            echo $form->field($filter, 'rule_name')
                ->dropDownList(\app\modules\adminx\models\AuthItemX::getRulesList(),
                    ['options' => [ $filter->rule_name => ['Selected' => true]],]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group" align="center">
            <?= Html::submitButton('Переключитися', ['class' => 'btn btn-danger']) ?>

        </div>
    </div>
    <input name="modelName" value="AuthItemFilter" hidden>

    <?php ActiveForm::end(); ?>

</div>
