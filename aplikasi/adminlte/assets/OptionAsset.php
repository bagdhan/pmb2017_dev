<?php
namespace app\adminlte\assets;

use yii\web\AssetBundle;

/**
 * Limit theme AssetBundle
 * @version 0.1
 */
class OptionAsset extends AssetBundle
{
    public $sourcePath = '@app/adminlte/dist';
    public $css = [
    ];
    public $js = [
        'js/optionJs.js',
    ];
    public $depends = [
        'app\adminlte\assets\plugin\DatePickerAsset',
        'app\adminlte\assets\plugin\Select2Asset',
        'app\adminlte\assets\plugin\PickadateAsset',
        'app\adminlte\assets\plugin\DataTableAsset',
        'app\adminlte\assets\plugin\BlockUiAsset',
        'app\adminlte\assets\plugin\BootBocAsset',
        'app\assets\JsAsset',
    ];

}
