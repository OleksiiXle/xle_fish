<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = \Yii::t('app', 'Профиль');
?>

<div class="container-fluid">
    <h3><?= Html::encode($this->title . ' ' . $model->username) ?></h3>
    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin([
                'id' => 'form-update',
            ]); ?>
            <?= Html::errorSummary($model)?>
            <?php
            echo $form->field($model, 'last_name');
            echo $form->field($model, 'first_name');
            echo $form->field($model, 'middle_name');
            echo $form->field($model, 'email');
            ?>
            <div class="form-group" align="center">
                <?= Html::submitButton(\Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                <?= Html::a(\Yii::t('app', 'Отмена'), '/site/index',[
                    'class' => 'btn btn-danger', 'name' => 'reset-button'
                ]);?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
