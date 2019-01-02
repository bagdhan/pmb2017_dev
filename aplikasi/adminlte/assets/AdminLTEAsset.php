<?php
namespace app\adminlte\assets;

use yii\web\AssetBundle;

/**
 * adminLTE theme AssetBundle
 * @version 0.1
 */
class AdminLTEAsset extends AssetBundle
{
    public $sourcePath = '@app/adminlte/dist';
    public $css = [
        'css/font-awesome/css/font-awesome.min.css',
        'css/ionicons/css/ionicons.min.css',
//        "https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css",
//        'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
    ];
    public $js = [
        "https://code.jquery.com/ui/1.11.4/jquery-ui.min.js",
        'https://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.5/waypoints.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}
