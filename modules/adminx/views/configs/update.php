<?php
use \app\modules\adminx\models\Configs;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = \Yii::t('app', 'Настройки');


?>

<div class="container-fluid">
    <h3><?= Html::encode($this->title) ?></h3>
    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin([
                'id' => 'form-update',
                'layout'=>'horizontal',

            ]); ?>
            <?= Html::errorSummary($model)?>
            <?php
             echo $form->field($model, 'adminEmail');
             echo $form->field($model, 'userControl')->dropDownList(Configs::dictionaryYes(),
                    ['options' => [ $model->userControl => ['Selected' => true]]]);
             echo $form->field($model, 'guestControl')->dropDownList(Configs::dictionaryYes(),
                    ['options' => [ $model->guestControl => ['Selected' => true]]]);
             echo $form->field($model, 'guestControlDuration')->dropDownList(Configs::dictionaryDuration(),
                    ['options' => [ $model->guestControlDuration => ['Selected' => true]]]);
             echo $form->field($model, 'menuType')->dropDownList(Configs::dictionaryMenu(),
                    ['options' => [ $model->menuType => ['Selected' => true]]]);
             echo $form->field($model, 'permCacheKey');
            echo $form->field($model, 'permCacheKeyDuration')->dropDownList(Configs::dictionaryDurationSec(),
                ['options' => [ $model->permCacheKeyDuration => ['Selected' => true]]]);
            echo $form->field($model, 'passwordResetTokenExpire')->dropDownList(Configs::dictionaryDuration(),
                ['options' => [ $model->passwordResetTokenExpire => ['Selected' => true]]]);
            echo $form->field($model, 'userDefaultRole')->dropDownList(Configs::dictionaryRoles(),
                ['options' => [ $model->userDefaultRole => ['Selected' => true]]]);
            ?>
            <div class="form-group" align="center">
                <?= Html::submitButton(\Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                <?= Html::submitButton(\Yii::t('app', 'Отмена'), ['class' => 'btn btn-danger', 'name' => 'reset-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
