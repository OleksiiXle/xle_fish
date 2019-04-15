<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\models\dictionary\Dictionary;
use \dosamigos\datepicker\DatePicker;

?>
<?//**********************************************************************************************?>
<?php $form = ActiveForm::begin(['id' => 'tree-modify-form']); ?>
            <?= $form->field($model, 'node1')->hiddenInput(['value' => $id])->label(false) ?>
            <?= $form->field($model, 'nodeAction')->textInput(['value' => $mode])->label(false) ?>
            <?= $form->field($model, 'id')->hiddenInput()->label(false); ?>

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
            <?= Html::button($model->isNewRecord ? 'Створити' : 'Зберігти зміни',
                [
                   'id' => 'tree-modify',
                   'class' => 'btn btn-primary',
                   'onclick' =>
                    'treeModify("menux");
                    '
            ]); ?>
            <?= Html::button( 'Відміна',
                ['class' =>  'btn btn-success',
                    'onclick' => '$("#main-modal-lg").modal("hide");'
                ]) ?>

        </div>
 <?php ActiveForm::end(); ?>
