<?php

use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);
\macgyer\yii2materializecss\assets\MaterializeAsset::register($this);

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

<body class="has-fixed-sidenav">
<header>
    <div class="navbar-fixed">
        <nav class="navbar white">
            <div class="nav-wrapper">
                <a href="#!" class="brand-logo grey-text text-darken-4">Home</a>
                <a href="#!" data-target="sidenav-left" class="sidenav-trigger left" style="display: block">
                    <i class="material-icons black-text">menu</i></a>
                <ul id="nav-mobile" class="right">
                    <li class="hide-on-med-and-down">
                        <a href="#!">Buy Now!</a>
                    </li>
                    <li>
                        <a href="#!" data-target="chat-dropdown" class="dropdown-trigger waves-effect">
                            <i class="material-icons">search</i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <ul id="sidenav-left" class="sidenav sidenav-fixed" >
        <li><a href="#!" class="logo-container">Admin<i class="material-icons left">search</i></a></li>
        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
                <li class="bold waves-effect"><a class="collapsible-header" tabindex="0">Pages<i class="material-icons chevron">chevron_left</i></a>
                    <div class="collapsible-body" style="">
                        <ul>
                            <li><a href="#!" class="waves-effect active">Dashboard<i class="material-icons">web</i></a></li>
                            <li><a href="#!" class="waves-effect">Fixed Chart<i class="material-icons">list</i></a></li>
                            <li><a href="#!" class="waves-effect">Grid<i class="material-icons">dashboard</i></a></li>
                            <li><a href="#!" class="waves-effect">Chat<i class="material-icons">chat</i></a></li>
                        </ul>
                    </div>
                </li>
                <li class="bold waves-effect"><a class="collapsible-header" tabindex="0">Charts<i class="material-icons chevron">chevron_left</i></a>
                    <div class="collapsible-body">
                        <ul>
                            <li><a href="#!" class="waves-effect">Line Charts<i class="material-icons">show_chart</i></a></li>
                            <li><a href="#!" class="waves-effect">Bar Charts<i class="material-icons">equalizer</i></a></li>
                            <li><a href="#!" class="waves-effect">Area Charts<i class="material-icons">multiline_chart</i></a></li>
                            <li><a href="#!" class="waves-effect">Doughnut Charts<i class="material-icons">pie_chart</i></a></li>
                            <li><a href="#!" class="waves-effect">Financial Charts<i class="material-icons">euro_symbol</i></a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
    </ul>



</header>
<main>
    <div class="container">
        <?= $content ?>
    </div>
</main>
<footer class="page-footer">
    <div class="container">
        <div class="row">
        </div>
    </div>
</footer>
<!-- Scripts -->


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<div id="preloaderCommonLayout" style="display: none">
    <div class="page-loader-circle"></div>
    <div id="preloaderText"></div>
</div>

