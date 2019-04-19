<?php
namespace app\components\widgets\xlegrid;

use yii\web\AssetBundle;

class XlegridAsset extends AssetBundle {
    public $baseUrl = '@web/components/widgets/xlegrid/assets';
    public $sourcePath = '@app/components/widgets/xlegrid/assets';
    public $publishOptions = ['forceCopy' => true];

    public $js = [
        'js/xlegrid.js',
    ];
    public $css = [

    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
?>
