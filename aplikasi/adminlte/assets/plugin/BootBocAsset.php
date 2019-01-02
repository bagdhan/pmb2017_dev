<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 4/26/2017
 * Time: 9:08 PM
 */

namespace app\adminlte\assets\plugin;


use yii\web\AssetBundle;

/**
 * Class BootBocAsset
 * @package app\adminlte\assets\plugin
 */
class BootBocAsset extends AssetBundle
{

    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js',
    ];

    public $depends = [
        'app\adminlte\assets\AdminLTEAsset',
    ];
}