<?php

namespace app\components\widgets\menuMDL;
use yii\web\AssetBundle;

class MenuMDLAssets extends AssetBundle
{
    public $baseUrl = '@web/components/widgets/menuMDL/assets';
    public $sourcePath = '@app/components/widgets/menuMDL/assets';
    public $publishOptions = ['forceCopy' => true];
    public $css = [
        'css/menuMDL.css',

    ];
    public $js = [
        'js/menuMDL.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
