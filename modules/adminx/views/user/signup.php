<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \yii\helpers\Url;
use \app\widgets\changePosDep\ChangePosDepWidget;

$this->title = \Yii::t('app', 'Регистрация');

?>

<div class="container-fluid">
    <h2><?= Html::encode($this->title) ?></h2>
    <?php $form = ActiveForm::begin([
        'id' => 'form-update',
    ]); ?>
    <?= Html::errorSummary($model)?>
    <div class="row">
        <div class="col-md-6">
            <?php
            echo $form->field($model, 'last_name');
            echo $form->field($model, 'first_name');
            echo $form->field($model, 'middle_name');
            ?>
        </div>
        <div class="col-md-6">
            <div class="row">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php
                    echo $form->field($model, 'email');
                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                    echo $form->field($model, 'username');
                    echo $form->field($model, 'password');//->passwordInput();
                    echo $form->field($model, 'retypePassword');//->passwordInput();
                    echo $form->field($model, 'reCaptcha')->widget(
                        \himiklab\yii2\recaptcha\ReCaptcha::className(),
                        ['siteKey' => '6LfU-p8UAAAAAOSjC2aMujiIuD9K8zw7tP4IJQrp']
                    )->label(false);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group" align="center">
            <?= Html::submitButton(\Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            <?= Html::a(\Yii::t('app', 'Отмена'), '/adminx/user',[
                'class' => 'btn btn-danger', 'name' => 'reset-button'
            ]);?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>








