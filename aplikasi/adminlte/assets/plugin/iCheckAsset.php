<?php
namespace app\adminlte\assets\plugin;

use yii\web\AssetBundle;

/**
 * Limit theme AssetBundle
 * @version 0.1
 */
class iCheckAsset extends AssetBundle
{
    public $sourcePath = '@app/adminlte/plugins/iCheck/';
    public $css = [
        'all.css',
    ];
    public $js = [
        'icheck.min.js',
    ];
    public $depends = [
        'app\adminlte\assets\AdminLTEAsset',
    ];

}
