<?php

namespace app\components\widgets\menuUpdate;

use yii\web\AssetBundle;

class MenuUpdateAssets extends AssetBundle
{
    public $baseUrl = '@web/components/widgets/menuUpdate/assets';
    public $sourcePath = '@app/components/widgets/menuUpdate/assets';
    public $publishOptions = ['forceCopy' => true];
    public $css = [
        'css/menuUpdate.css',
    ];
    public $js = [
       // 'js/menuUpdate.js',
       // 'js/funcs.js',
        'js/xtree.js',
        'js/init.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
