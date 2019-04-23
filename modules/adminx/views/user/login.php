<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="site-login">
    <h3><?= Html::encode('Вход') ?></h3>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username')->label('Логин') ?>
                <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>
                <?= $form->field($model, 'rememberMe')->checkbox()->label(false)->hiddenInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
