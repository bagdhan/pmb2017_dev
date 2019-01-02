<?php
namespace app\adminlte\assets\plugin;

use yii\web\AssetBundle;

/**
 * DataTable asset AssetBundle
 * @version 0.1
 */
class DataTableAsset extends AssetBundle
{
    public $sourcePath = '@app/adminlte/plugins/datatables/';
    public $css = [
//        'css/jquery.dataTables.min.css',
        'css/dataTables.bootstrap.min.css',
    ];
    public $js = [
        'js/jquery.dataTables.min.js',
        'js/dataTables.bootstrap.min.js',
    ];
    public $depends = [
        'app\adminlte\assets\AdminLTEAsset',
    ];

}
