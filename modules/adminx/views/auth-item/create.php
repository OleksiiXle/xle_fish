<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \yii\helpers\Url;
use \app\widgets\changePosDep\ChangePosDepWidget;
use \app\modules\adminx\models\AuthItemX;

switch ($model->type){
    case AuthItemX::TYPE_ROLE:
        $this->title = 'Роль';
        break;
    case AuthItemX::TYPE_PERMISSION:
        $this->title = 'Дозвіл';
        break;
    case AuthItemX::TYPE_ROUTE:
        $this->title = 'Маршрут';
        break;

}

//\app\modules\adminx\assets\AdminxPermissionAsset::register($this);
/*
$_assigments = \yii\helpers\Json::htmlEncode($assigments);
$this->registerJs("
    var _assigments = {$_assigments};
");
$this->registerJs($this->render('_scriptUpdate.js'));
*/
?>

<div class="container-fluid">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
  //  echo var_dump($availableRoles);
   // echo var_dump($allRoles);
    ?>
    <div class="col-md-3">
        <?php $form = ActiveForm::begin([
            'id' => 'form-update',
        ]); ?>
        <?= Html::errorSummary($model)?>
        <?php
        echo $form->field($model, 'type')->hiddenInput()->label(false);
        echo $form->field($model, 'name');
        echo $form->field($model, 'description');
        echo $form->field($model, 'rule_name')
            ->dropDownList(\app\modules\adminx\models\AuthItemX::getRulesList(),
                ['options' => [ $model->rule_name => ['Selected' => true]],]);

        ?>
        <div class="form-group" align="center">
            <?= Html::submitButton('Створити', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            <?= Html::a('Відміна', '/adminx/auth-item',[
                'class' => 'btn btn-danger', 'name' => 'reset-button'
            ]);?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-md-9">
    </div>
</div>


