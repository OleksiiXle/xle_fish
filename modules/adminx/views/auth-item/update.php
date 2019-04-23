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
        $this->title = 'Разрешение';
        break;
    case AuthItemX::TYPE_ROUTE:
        $this->title = 'Маршрут';
        break;

}
$this->title .= ' ' . $model->name;


$_assigments = \yii\helpers\Json::htmlEncode($assigments);
$this->registerJs("
    var _assigments = {$_assigments};
    var _name       = '{$model->name}';
    var _type       = '{$model->type}';
",\yii\web\View::POS_HEAD);
\app\modules\adminx\assets\AdminxUpdateAuthItemAsset::register($this);


?>
<div class="container-fluid">
    <h3><?= Html::encode($this->title) ?></h3>
    <?php
    $showSelects = (substr($model->name, 0,1) == '/') ? 'style= display:none;' : '';
   // echo var_dump($assigments);
   // echo var_dump($allRoles);
    ?>
    <div class="row">
        <div class="col-md-3">
            <?php $form = ActiveForm::begin([
                'id' => 'form-update',
            ]); ?>
            <?= Html::errorSummary($model)?>
            <?php
            echo $form->field($model, 'type')->hiddenInput()->label(false);
            echo $form->field($model, 'description');
            echo $form->field($model, 'rule_name')
                ->dropDownList(\app\modules\adminx\models\AuthItemX::getRulesList(),
                    ['options' => [ $model->rule_name => ['Selected' => true]],]);

            ?>
            <div class="form-group" align="center">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'update-button']) ?>
                <?= Html::a('Отмена', '/adminx/auth-item',[
                    'class' => 'btn btn-success', 'name' => 'reset-button'
                ]);?>
                <?= Html::submitButton('Удалить', [
                        'class' => 'btn btn-danger',
                         'name' => 'delete-button',
                         'value' => 'delete',
                         'data' => ['confirm' => 'Удалить?']
                ]) ?>

            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-md-9">
            <div class="row" >
                    <h4><b>Дозвіли</b></h4>
                        <div id="authItems" <?=$showSelects;?>>
                            <div class="col-md-5 userSelect">
                                <h5>Доступные</h5>
                                <select multiple size="40" class="form-control list" data-target="avaliable"></select>
                            </div>
                            <div class="col-md-2 userSelect" align="center">
                                <br><br>
                                <?= Html::a('&gt;&gt;' , false, [
                                    'class' => 'btn btn-success btn-assign actionAssign',
                                    'data-rout' => '/adminx/auth-item/assign',
                                    'data-name' => $model->name,
                                    'data-target' => 'avaliable',
                                    'title' => Yii::t('rbac-admin', 'Assign')
                                ]) ?><br><br>
                                <?= Html::a('&lt;&lt;', false, [
                                    'class' => 'btn btn-danger btn-assign actionRevoke',
                                    'data-rout' => '/adminx/auth-item/revoke',
                                    'data-name' => $model->name,
                                    'data-target' => 'assigned',
                                    'title' => Yii::t('rbac-admin', 'Remove')
                                ]) ?>
                            </div>
                            <div class="col-md-5 userSelect">
                                <h5><b>Назначенные</b></h5>
                                <select multiple size="40" class="form-control list" data-target="assigned"></select>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</div>
<script>

</script>

