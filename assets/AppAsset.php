<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot/assets';
    public $sourcePath = '@app/assets';

    // public $basePath = '@webroot';
  //  public $baseUrl = '@web';
 //   public $basePath = '@webroot/assets';
 //   public $sourcePath = '@app/assets';
 //   public $publishOptions = ['forceCopy' => true];
    public $css = [
        'css/site.css',
        'css/xle.css',
        'css/common.css',
        'css/admin-materialize.css',
    ];
    public $js = [
       // 'js/menux.js',
        'js/common.js',

    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
