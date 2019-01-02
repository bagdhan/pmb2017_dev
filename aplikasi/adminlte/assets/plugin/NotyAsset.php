<?php
namespace app\adminlte\assets\plugin;

use yii\web\AssetBundle;

/**
 * Limit theme AssetBundle
 * @version 0.1
 */
class NotyAsset extends AssetBundle
{
    public $sourcePath = '@app/adminlte/plugins/notifications/';
    public $css = [

    ];
    public $js = [
        'noty.min.js',
    ];
    public $depends = [
        'app\adminlte\assets\LimitAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];

}
