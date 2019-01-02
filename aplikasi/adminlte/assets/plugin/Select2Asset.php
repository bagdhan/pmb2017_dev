<?php
namespace app\adminlte\assets\plugin;

use yii\web\AssetBundle;

/**
 * Limit theme AssetBundle
 * @version 0.1
 */
class Select2Asset extends AssetBundle
{
    public $sourcePath = '@app/adminlte/plugins/select2/';
    public $css = [
        'select2.min.css',
    ];
    public $js = [
        'select2.full.min.js',
    ];
    public $depends = [
        'app\adminlte\assets\AdminLTEAsset',
    ];

}
