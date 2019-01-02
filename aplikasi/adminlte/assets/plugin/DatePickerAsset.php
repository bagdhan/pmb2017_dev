<?php
/**
 * Created by
 * User: wisard17
 * Date: 11/4/2016
 * Time: 6:53 AM
 */

namespace app\adminlte\assets\plugin;

use yii\web\AssetBundle;

/**
 * Date picker assets
 */
class DatePickerAsset extends AssetBundle
{

    public $sourcePath = '@app/adminlte/plugins/datepicker';
    public $css = [
        'datepicker3.css',
    ];
    public $js = [
        'bootstrap-datepicker.js',
        'locales\bootstrap-datepicker.id.js',
    ];
    public $depends = [
        'app\adminlte\assets\AdminLTEAsset',
    ];

}

