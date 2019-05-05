<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
if (Yii::$app->session->getAllFlashes()){
    $fms = Yii::$app->session->getAllFlashes();
    $_fms = \yii\helpers\Json::htmlEncode($fms);
    $this->registerJs("
    var _fms = {$_fms};
",\yii\web\View::POS_HEAD);
    $this->registerJs($this->render('showFlash.js'));
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap" id="mainContainer">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-fixed-top',
                'style' => 'background: lightgrey;'
            ],
        ]);
        echo \app\components\widgets\menuG\MenuGWidget::widget();
        ?>
    <div class="pull-right" >
        <?php
        echo Html::dropDownList('lang', $this->context->language, \app\models\Translation::LIST_LANGUAGES, [
            'class' => 'selectLanguage',
            //   'onchange' => 'console.log(this);'
            'onchange' => "setLanguage(this.value);",
        ]);
        if (Yii::$app->user->isGuest){
            echo Html::a(\Yii::t('app', "Вход"), '/adminx/user/login');
        } else {
            echo Html::a(\Yii::t('app', "Выход(" . Yii::$app->user->identity->username . ')'), '/adminx/user/logout',
                [
                    'data-method' => 'post',
                    'style' => 'color: black',
                ]);
        }
        ?>
    </div>
    <?php

        NavBar::end();
        ?>
    <div style="padding-top: 80px; overflow: hidden;">
        <div class="row">
        </div>
        <div class="row">
            <div class="container contentXleHorizontalMenu" >
                <div id="flashMessage" style="display: none">
                </div>

                <?= $content ?>
            </div>
        </div>
    </div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
