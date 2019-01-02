<?php
namespace app\adminlte\assets\plugin;

use yii\web\AssetBundle;

/**
 * Limit theme AssetBundle
 * @version 0.1
 */
class DateTimePickerAsset extends AssetBundle
{
    public $sourcePath = '@app/adminlte/plugins/pickers/';
    public $css = [

    ];
    public $js = [
        'moment.min.js',
        'anytime.min.js',
        'datepicker.js',
        'daterangepicker.js',
        'pickadate/picker.js',
        'pickadate/picker.date.js',
        'pickadate/picker.time.js',
        'pickadate/legacy.js',
    ];
    public $depends = [
        'app\adminlte\assets\LimitAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];

}
