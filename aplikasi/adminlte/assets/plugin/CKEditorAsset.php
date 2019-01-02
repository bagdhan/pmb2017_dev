<?php
namespace app\adminlte\assets\plugin;

use yii\web\AssetBundle;

/**
 * Limit theme AssetBundle
 * @version 0.1
 */
class CKEditorAsset extends AssetBundle
{
    public $sourcePath = '@app/adminlte/plugins/ckeditor/';
    public $css = [
    ];
    public $js = [
        'ckeditor.js',
    ];
    public $depends = [
        // 'app\adminlte\assets\LimitAsset',
        // 'app\adminlte\assets\plugin\LoaderAsset',
    ];

}
