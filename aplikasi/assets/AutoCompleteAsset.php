<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * Auto Complate asset AssetBundle
 * @version 0.1
 */
class AutoCompleteAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/autocomplete/';
    public $css = [
        'easy-autocomplete.min.css',
        'easy-autocomplete.themes.min.css',
    ];
    public $js = [
        'jquery.easy-autocomplete.min.js',
    ];
    public $depends = [
        'app\adminlte\assets\AdminLTEAsset',
    ];

}
