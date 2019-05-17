<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use \yii\helpers\Url;
use app\assets\AppAsset;
use \macgyer\yii2materializecss\assets\MaterializeAsset;
use \macgyer\yii2materializecss\lib\Html as HtmlMC;
use \app\components\widgets\menuG\MenuGWidget;
use \app\components\widgets\selectXle\SelectXleWidget;
use \app\models\Translation;

AppAsset::register($this);
MaterializeAsset::register($this);


if (Yii::$app->session->getAllFlashes()){
    $fms = Yii::$app->session->getAllFlashes();
    $_fms = \yii\helpers\Json::htmlEncode($fms);
    $this->registerJs("var _fms = {$_fms};",\yii\web\View::POS_HEAD);
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
<body id="mainContainer">
<?php $this->beginBody() ?>

<header>
    <div class="navbar-fixed">
        <nav class="navbar topXleHorizontal">
            <div class="nav-wrapper">
                <div class="col-md-2">
                    <a href="<?=Yii::$app->homeUrl;?>" class="appName" ><b><?=Yii::$app->name;?></b></a>
                </div>
                <div class="col-md-7">
                    <div style="height: 20px">
                        <?= MenuGWidget::widget();?>
                    </div>
                </div>
                <div class="col-md-2">
                    <div >
                        <?php
                        echo SelectXleWidget::widget([
                                'listData' => Translation::LIST_LANGUAGES,
                                'selectedItem' => $this->context->language,
                                'jsFunction' => "
                                     function clickFunction(item) {
                                      document.location.href = '/adminx/translation/change-language?language=' + item;
                                     }
                                ",
                        ]);
                        ?>

                        <?php
                        /*
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
                        */
                        ?>
                    </div>
                </div>
                <div class="col-md-1">
                    <div >
                        <?php
                        if (Yii::$app->user->isGuest){
                            echo HtmlMC::a(\Yii::t('app', "Вход"), '/adminx/user/login');
                        } else {
                            echo HtmlMC::img(Url::to('@web/images/no-avatar.png'), [
                                'class' => 'circle',
                                'height' => '35px',
                                'width' => 'auto',
                            ]);
                            echo HtmlMC::a(\Yii::t('app', "Выход"), '/adminx/user/logout',
                                [
                                    'data-method' => 'post',
                                    'style' => 'color: black; padding-left: 10px',
                                ]);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </nav>

    </div>
</header>

<main>
    <div class="container">
        <div id="flashMessage" style="display: none">
        </div>
        <?= $content ?>
        <div class="footer-copyright">
        </div>

    </div>
</main>

<footer class="page-footer" style="background: none">
    <div class="container">
        © 2019 Copyright Text
    </div>
</footer>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
