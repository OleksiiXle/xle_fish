<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = \Yii::t('app', 'Настройки');


?>

<div class="container-fluid">
    <h3><?= Html::encode($this->title . ' ' . $model->name) ?></h3>
    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin([
                'id' => 'form-update',
            ]); ?>
            <?= Html::errorSummary($model)?>
            <?php
            echo $form->field($model, 'owner', ['inputOptions' =>
                ['class' => 'form-control','tabindex' => '1']])
                ->dropDownList(\app\components\configs\models\Configs::OWNER_LIST,
                    ['options' => [ $model->owner => ['Selected' => true], ],]);
            echo $form->field($model, 'type', ['inputOptions' =>
                ['class' => 'form-control','tabindex' => '1']])
                ->dropDownList(\app\components\configs\models\Configs::TYPE_LIST,
                    ['options' => [ $model->type => ['Selected' => true], ],]);

             echo $form->field($model, 'name');
             echo $form->field($model, 'content');
             echo $form->field($model, 'id')->hiddenInput()->label(false);

            ?>
            <div class="form-group" align="center">
                <?= Html::submitButton(\Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                <?= Html::a(\Yii::t('app', 'Отмена'), '/adminx/configs',[
                    'class' => 'btn btn-danger', 'name' => 'reset-button'
                ]);?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
