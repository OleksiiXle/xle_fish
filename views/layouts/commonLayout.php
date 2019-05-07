<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
/*

$user_id = (!Yii::$app->user->isGuest) ? Yii::$app->user->getId() : 0;

$user_action = $this->context->id . '->' . $this->context->action->id;
$this->registerJs("
    var _user_id      =  {$user_id};
    var _user_action  = '{$user_action}';
",\yii\web\View::POS_HEAD);
*/

AppAsset::register($this);
//\macgyer\yii2materializecss\assets\MaterializeAsset::register($this);
//$this->registerJs($this->render('@app/assets/js/commonFunctions.js'),\yii\web\View::POS_HEAD);

if (Yii::$app->session->getAllFlashes()){
    $fms = Yii::$app->session->getAllFlashes();
    $_fms = \yii\helpers\Json::htmlEncode($fms);
    $this->registerJs("
    var _fms = {$_fms};
",\yii\web\View::POS_HEAD);
    $this->registerJs($this->render('showFlash.js'));
}





?>
<?php
//$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => \yii\helpers\Url::to(['/images/np_logo.png'])]);?>
<?php $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => \yii\helpers\Url::to(['/images/np_logo.png'])]);?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container-fluid" id="mainContainer">

        <div class="menuX menuXleActive">
            <div class="row">
                <div class="col-md-3">
                    <div class ="img-rounded">
                        <img  src="<?=\yii\helpers\Url::to('@web/images/np_logo.png');?>" height="50px" width="50px;">
                    </div>
                </div>
                <div class="col-md-5">
                    <div>
                        <?php
                        $us = $this->context->user->id;
                        echo \macgyer\yii2materializecss\widgets\form\Select::widget([
                            'name' => 'ddg',
                            'items' => \app\models\Translation::LIST_LANGUAGES,
                            'options' => [
                                'placeholder' => false,
                                'onchange' => "setLanguage(this.value);",
                                'options' => [
                                    $this->context->language => ['Selected' => true],

                                ],
                            ]
                        ]);
                        /*
                        echo Html::dropDownList('lang', $this->context->language, \app\models\Translation::LIST_LANGUAGES, [
                            //'class' => 'selectLanguage',
                            //   'onchange' => 'console.log(this);'
                            'onchange' => "setLanguage(this.value);"
                        ]);
                        */
                        ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <button id="menu-switch-btn" class="btn"
                            style="height: 55px; width: 55px; padding: 2px; background-color: #D0D5D8; outline:none;">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="menuTree">
                    <?=\app\components\widgets\menuX\MenuXWidget::widget([
                        'model' => '',
                        'attribute' => 'kjgh',
                        'name' => '',
                    ]) ;?>
                </div>
                <div>
                    <?php
                    if (!$this->context->user->isGuest){
                        $icon = \yii\helpers\Url::to('@web/images/Gnome-Application-Exit-64.png');
                        echo Html::beginForm(['/adminx/user/logout'], 'post');
                        echo Html::submitButton(
                            ' <img  src="' . $icon . '" height="30px" width="30px;" >',
                            ['class' => 'btn btn-link logout']
                        );
                        echo Html::endForm();
                    }
                    ?>
                </div>
            </div>


        </div>
        <div class="dataX contentXle">
            <div id="flashMessage" style="display: none">
            </div>
            <div class="container-fluid">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<div id="preloaderCommonLayout" style="display: none">
    <div class="page-loader-circle"></div>
    <div id="preloaderText"></div>
</div>

<script>
    //jQuery('#left-side-switch-button').click(toggleLeftSide);
    jQuery('#menu-switch-btn').click(function () {
        if($('.menuX').hasClass("menuXleActive")){
            $('.menuX').addClass("menuXle");
            $('.menuX').removeClass("menuXleActive");
            $('.dataX').removeClass("contentXle");
            $('.dataX').addClass("contentXleActive");
            this.innerHTML='<span class="glyphicon glyphicon-chevron-right"></span>';
            $('.menuTree').hide();
        } else {
            $('.menuX').addClass("menuXleActive");
            $('.menuX').removeClass("menuXle");
            $('.dataX').removeClass("contentXleActive");
            $('.dataX').addClass("contentXle");
            this.innerHTML='<span class="glyphicon glyphicon-chevron-left"></span>';
            $('.menuTree').show();


        }
      //  jQuery('body').toggleClass('left-side-active');
    });
</script>