<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 4/26/2017
 * Time: 7:24 PM
 */

namespace app\adminlte\assets\plugin;


use yii\web\AssetBundle;

/**
 * Class BlockUiAsset
 * @package app\adminlte\assets\plugin
 */
class BlockUiAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'http://malsup.github.io/min/jquery.blockUI.min.js',
    ];
    public $depends = [
        'app\adminlte\assets\AdminLTEAsset',
    ];
}