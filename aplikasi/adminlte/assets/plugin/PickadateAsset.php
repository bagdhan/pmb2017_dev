<?php
namespace app\adminlte\assets\plugin;

use yii\web\AssetBundle;

/**
 * Limit theme AssetBundle
 * @version 0.1
 */
class PickadateAsset extends AssetBundle
{
    public $sourcePath = '@app/adminlte/plugins/pickers/';
    public $css = [
        'pickadate/css/classic.css',
        'pickadate/css/classic.date.css',
    ];
    public $js = [
        'pickadate/picker.js',
        'pickadate/picker.date.js',
        'pickadate/picker.time.js',
        'datepicker.js',
        'daterangepicker.js',
        'anytime.min.js',

    ];
    public $depends = [
        'app\adminlte\assets\AdminLTEAsset',
    ];

}
