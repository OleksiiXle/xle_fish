<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use \macgyer\yii2materializecss\assets\MaterializeAsset;
use \yii\helpers\Url;

use \macgyer\yii2materializecss\lib\Html as HtmlMC;
use \app\components\widgets\menuX\MenuXWidget;
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

//$this->title = \Yii::t('app', 'Главная');
?>
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

        <div id="topXle" class="topXle">
            <div class="col-md-1">
                <div id="menu-switch-btn" class="topItem">
                    <a href="#!" ><span class="glyphicon glyphicon-chevron-left"></span></a>
                </div>
            </div>
            <div class="col-md-7">

            </div>
            <div class="col-md-2">

            </div>
            <div class="col-md-2">
                <div class="topItem">
                    <?php
                    if (Yii::$app->user->isGuest){
                        echo HtmlMC::a(\Yii::t('app', "Вход"), '/adminx/user/login');
                    } else {
                        echo HtmlMC::a(\Yii::t('app', "Выход(" . Yii::$app->user->identity->username . ')'), '/adminx/user/logout',
                            [
                                'data-method' => 'post',
                                'style' => 'color: black',
                            ]);
                    }
                    ?>
                </div>
            </div>

        </div>

        <div id="leftSide">
            <div id="headerXle" class="headerXle">
                <a href="<?=Yii::$app->homeUrl;?>" class="appName" ><b><?=Yii::$app->name;?></b></a>


            </div>
            <div id="menuXle" class="menuXle">
                    <img src="<?=Url::to('@web/images/no-avatar.png');?>"
                         class="circle" height="7%" width="auto" alt="lorem" style="padding:10px;">
                    <b><?=\Yii::t('app', 'Добро пожаловать');?></b>
                <div class="row" align="right" style="padding-left: 60%; padding-right: 2%">
                    <?php
                    echo SelectXleWidget::widget([
                        'listData' => Translation::LIST_LANGUAGES,
                        'selectedItem' => $this->context->language,
                        'jsFunction' => "
                                     function clickFunction(item) {
                                      document.location.href = '/adminx/translation/change-language?language=' + item;
                                     }
                                ",
                        'userStyles' => [
                             'listItem' => [
                                 'font-weight' => 300,
                                 'font-size' => 'small',
                                 'color' => 'blue',
                             ],
                             'itemsArea' => [
                                 'background' => '#eeeeee',
                                 'border' => '2px solid #bdbdbd',
                             ],
                        ],
                    ]);

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
                <div class="row">
                    <div class="menuTree">
                        <?=MenuXWidget::widget([
                            'model' => '',
                            'attribute' => 'kjgh',
                            'name' => '',
                        ]) ;?>
                    </div>
                </div>

            </div>
        </div>


        <div id="contentXle" class="contentXle">
            <div id="flashMessage" style="display: none">
            </div>
            <?= $content ?>
            <div class="footer-copyright">
                <div class="container">
                    © 2019 Copyright Text
                    <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
                </div>
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
    jQuery('#menu-switch-btn').click(function () {
        if($('#topXle').hasClass("topXle")){
            $('#leftSide').hide(1000);
            $('#topXle').removeClass("topXle");
            $('#topXle').addClass("topXleResize");
            $('#topXle').width("96%");
            $('#contentXle').removeClass("contentXle");
            $('#contentXle').addClass("contentXleResize");
            $('#contentXle').width("99%");
            this.innerHTML='<span class="glyphicon glyphicon-chevron-right"></span>';
        } else {
            $('#leftSide').show(1000);
            $('#topXle').removeClass("topXleResize");
            $('#topXle').addClass("topXle");
            $('#topXle').width("82%");
            $('#contentXle').removeClass("contentXleResize");
            $('#contentXle').addClass("contentXle");
            $('#contentXle').width("85%");
            this.innerHTML='<span class="glyphicon glyphicon-chevron-left"></span>';
        }
    });
</script>