<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\models\dictionary\Dictionary;
use \dosamigos\datepicker\DatePicker;

?>
<?//**********************************************************************************************?>
<?php $form = ActiveForm::begin(['id' => 'menuMmodifyForm']); ?>
            <?= $form->field($model, 'node1')->textInput()->label('node1') ?>
            <?= $form->field($model, 'nodeAction')->textInput()->label('nodeAction') ?>
            <?= $form->field($model, 'menu_id')->textInput()->label('menu_id') ?>
            <?= $form->field($model, 'id')->textInput()->label('id'); ?>
            <?= $form->field($model, 'sort')->textInput()->label('sort'); ?>

<div class="row">
    <div class="col-md-12">
        <?php
        echo $form->field($model, 'name', ['inputOptions' =>
                    ['class' => 'form-control', 'tabindex' => '1']]);
        echo $form->field($model, 'route', ['inputOptions' =>
            ['class' => 'form-control', 'tabindex' => '2']]);
        echo $form->field($model, 'role', ['inputOptions' =>
            ['class' => 'form-control', 'tabindex' => '3']]);
        ?>
    </div>
</div>
        <div class="row" align="center">
            <?= Html::button($model->isNewRecord ? 'Создать' : 'Сохранить',
                [
                   'id' => 'btn_' . $model->menu_id . '_updateForm',
                   'class' => 'btn btn-primary',
            ]); ?>
            <?= Html::button( 'Отмена',
                ['class' =>  'btn btn-danger',
                    'onclick' => '$("#main-modal-md").modal("hide");'
                ]) ?>

        </div>
 <?php ActiveForm::end(); ?>
