<?php
/**
 * Created by
 * User: Wisard17
 * Date: 28/01/2018
 * Time: 05.07 PM
 */

use app\modules\admin\models\confirmasiPin\DataTable;

/* @var $this yii\web\View */


$this->title = Yii::t('app', 'Confirmasi PIN Mahasiswa Asing');
$this->params['breadcrumbs'][] = $this->title;

$this->params['sidebar'] = 0;

?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">List Data Pendaftar</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
        <?= DataTable::widget([
            'options' => [
                'order' => "[0, 'desc']",
            ],
            'columns' => [
                    'noPendaftaran',
                'name',
                'negara',
                'email',
                'tanggalDaftar',
                'action'
            ],
            'model' => new \app\modules\admin\models\confirmasiPin\Search(),
        ]);?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
