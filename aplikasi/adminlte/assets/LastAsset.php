<?php
namespace app\adminlte\assets;

use yii\web\AssetBundle;

/**
 * Limit theme AssetBundle
 * @version 0.1
 */
class LastAsset extends AssetBundle
{
    public $sourcePath = '@app/adminlte/dist';
    public $css = [
        'css/AdminLTE.min.css',
        'css/skins/_all-skins.min.css',
        'css/appstyle.css',
    ];
    public $js = [
        'js/app.min.js',
        'js/demo.js',
//        'js/blockui.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'app\adminlte\assets\plugin\Select2Asset',
        'app\adminlte\assets\plugin\BlockUiAsset',
    ];

}
