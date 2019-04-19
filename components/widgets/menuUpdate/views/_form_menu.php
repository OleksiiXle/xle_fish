<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\models\dictionary\Dictionary;
use \dosamigos\datepicker\DatePicker;

?>
<?//**********************************************************************************************?>
<?php $form = ActiveForm::begin(['id' => 'menuMmodifyForm']); ?>
            <?= $form->field($model, 'node1')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'nodeAction')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'menu_id')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'id')->hiddenInput()->label(false); ?>
            <?= $form->field($model, 'sort')->hiddenInput()->label(false); ?>

<div class="row">
    <div class="col-md-12">
        <?php
        echo $form->field($model, 'name', ['inputOptions' => ['class' => 'form-control', 'tabindex' => '1']]);
        echo $form->field($model, 'route', ['inputOptions' => ['class' => 'form-control', 'tabindex' => '3']])
            ->dropDownList($routes, ['options' => [ $model->route => ['Selected' => true]],]);
        echo $form->field($model, 'role')
            ->dropDownList($permissions,
                ['options' => [ $model->role => ['Selected' => true]],]);
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
