<?php
namespace common\adminlte\assets\plugin;

use yii\web\AssetBundle;

/**
 * Limit theme AssetBundle
 * @version 0.1
 */
class SwitcheryAsset extends AssetBundle
{
    public $sourcePath = '@app/adminlte/plugins/forms/styling/';
    public $css = [

    ];
    public $js = [
        'switchery.min.js',
    ];
    public $depends = [
        'app\adminlte\assets\LimitAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];

}
