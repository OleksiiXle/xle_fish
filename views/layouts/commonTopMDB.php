<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use \yii\helpers\Url;
use \app\components\widgets\menuMDL\MenuMDLWidget;
use \app\components\widgets\menuG\MenuGWidget;
use \app\components\widgets\selectXle\SelectXleWidget;
use \macgyer\yii2materializecss\assets\MaterializeAsset;
use \macgyer\yii2materializecss\lib\Html as HtmlMC;
use \app\models\Translation;


yii\jui\JuiAsset::register($this);
$this->registerJs('
$( function() {
            $("#speed").selectmenu();
            $("#speed").on( "selectmenuchange", function( event, ui ) {
                document.location.href = "/adminx/translation/change-language?language=" + this.value;
                 } );
        } );
        
',\yii\web\View::POS_HEAD);

AppAsset::register($this);
MaterializeAsset::register($this);

if (Yii::$app->session->getAllFlashes()){
    $fms = Yii::$app->session->getAllFlashes();
    $_fms = \yii\helpers\Json::htmlEncode($fms);
    $this->registerJs("var _fms = {$_fms};",\yii\web\View::POS_HEAD);
    $this->registerJs($this->render('showFlash.js'));
}

?>
<script>
</script>

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
<!-- Always shows a header, even in smaller screens. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">

    <header class="mdl-layout__header leftSideHeader">
        <div class="mdl-layout__header-row" >
            <div class="col-md-3" align="left">
                <!-- Title -->
                <span class="mdl-layout-title">
                   <a href="<?=Yii::$app->homeUrl;?>" class="appName" ><b><?=Yii::$app->name;?></b></a>
                </span>
            </div>
            <div class="col-md-6">
                <?= MenuGWidget::widget();?>
            </div>
            <div class="col-md-2">
                <?php
                $listData = Translation::LIST_LANGUAGES;
                $selectedItem = $this->context->language;
                ?>
                <select name="speed" id="speed" onchange="console.log(this.value);" style="color: black">
                    <option value="<?=$selectedItem?>" selected="selected"><?=$listData[$selectedItem]?></option>
                    <?php foreach ($listData as $key => $value):?>
                        <?php if ($key != $selectedItem):?>
                            <option value="<?=$key?>"><?=$listData[$key]?></option>
                        <?php endif;?>
                    <?php endforeach;?>
                </select>

            </div>
            <div class="col-md-1">
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
    </header>

    <main class="mdl-layout__content">
        <div class="page-content"><!-- Your content goes here -->
            <div class="container">
                <?= $content ?>

            </div>
        </div>
    </main>
</div>







<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

