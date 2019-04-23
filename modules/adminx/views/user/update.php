<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = 'Изменение данных пользователя';
\app\modules\adminx\assets\AdminxUpdateUserAsset::register($this);

$_assigments = \yii\helpers\Json::htmlEncode($assigments);
$this->registerJs("
    var _assigments = {$_assigments};
",\yii\web\View::POS_HEAD);

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
            ?>
            <div class="form-group" align="center">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                <?= Html::a('Отмена', '/adminx/user',[
                    'class' => 'btn btn-danger', 'name' => 'reset-button'
                ]);?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="userAssigments">
                <h4><b>Дозвіли</b></h4>
                <div class="col-md-5">
                    <div id="roles">
                        <div class="col-md-5 userSelect">
                            <h5>Доступні ролі</h5>
                            <select multiple size="25" class="form-control list" data-target="avaliableRoles"></select>
                        </div>
                        <div class="col-md-2 userSelect">
                            <br><br>
                            <?= Html::a('&gt;&gt;' , false, [
                                'class' => 'btn btn-success btn-assign actionAssign',
                                'data-rout' => '/adminx/assignment/assign',
                                'data-user_id' => $user_id,
                                'data-target' => 'avaliableRoles',
                                'title' => Yii::t('rbac-admin', 'Assign')
                            ]) ?><br><br>
                            <?= Html::a('&lt;&lt;' , false, [
                                'class' => 'btn btn-danger btn-assign actionRevoke',
                                'data-rout' => '/adminx/assignment/revoke',
                                'data-user_id' => $user_id,
                                'data-target' => 'assignedRoles',
                                'title' => Yii::t('rbac-admin', 'Remove')
                            ]) ?>
                        </div>
                        <div class="col-md-5 userSelect">
                            <h5><b>Назначені ролі</b></h5>
                            <select multiple size="25" class="form-control list" data-target="assignedRoles"></select>
                        </div>
                        <?php
                        //  require(__DIR__ . '/../ajax/_roleGrid.php');
                        ?>
                    </div>
                </div>
                <div class="col-md-7">
                    <div id="permissions">
                        <div class="col-md-5 userSelect">
                            <h5>Доступні дозвіли</h5>
                            <select multiple size="25" class="form-control list" data-target="avaliablePermissions"></select>
                        </div>
                        <div class="col-md-1 userSelect">
                            <br><br>
                            <?= Html::a('&gt;&gt;' , false, [
                                'class' => 'btn btn-success btn-assign actionAssign',
                              //  'data-rout' => '/adminx/assignment/assign',
                                'data-user_id' => $user_id,
                                'data-target' => 'avaliablePermissions',
                                'title' => Yii::t('rbac-admin', 'Assign')
                            ]) ?><br><br>
                            <?= Html::a('&lt;&lt;' ,  false, [
                                'class' => 'btn btn-danger btn-assign actionRevoke',
                               // 'data-rout' => '/adminx/assignment/revoke',
                                'data-user_id' => $user_id,
                                'data-target' => 'assignedPermissions',
                                'title' => Yii::t('rbac-admin', 'Remove')
                            ]) ?>
                        </div>
                        <div class="col-md-6 userSelect">
                            <h5><b>Назначені дозвіли</b></h5>
                            <select multiple size="25" class="form-control list" data-target="assignedPermissions"></select>
                        </div>
                        <?php
                        //  require(__DIR__ . '/../ajax/_roleGrid.php');
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
