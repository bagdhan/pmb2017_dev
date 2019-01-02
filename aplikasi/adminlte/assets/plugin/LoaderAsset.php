<?php
namespace app\adminlte\assets\plugin;

use yii\web\AssetBundle;

/**
 * Limit theme AssetBundle
 * @version 0.1
 */
class LoaderAsset extends AssetBundle
{
    public $sourcePath = '@app/adminlte/plugins/loaders/';
    public $css = [

    ];
    public $js = [
        'pace.min.js',
        'blockui.min.js',
        'progressbar.min.js',
    ];
    public $depends = [
        'app\adminlte\assets\LimitAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];

}
