<?php
namespace app\adminlte\assets\plugin;

use yii\web\AssetBundle;

/**
 * Limit theme AssetBundle
 * @version 0.1
 */
class ButtonSpinAsset extends AssetBundle
{
    public $sourcePath = '@app/adminlte/plugins/buttons/';
    public $css = [
       // 'ladda.min.css',
       // 'ladda-themeless.min.css',
    ];
    public $js = [
        'hover_dropdown.min.js',
        'spin.min.js',
        'ladda.min.js',
        //'ladda.jquery.min.js',
    ];
    public $depends = [
        'app\adminlte\assets\AdminLTEAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];

}
