<?php


/* @var $this yii\web\View */
/* @var $model \app\modules\admin\models\paketPendaftaran\PaketPendaftaran */

$this->title = Yii::t('app', 'Manajemen Paket Pendaftaran');
$this->params['breadcrumbs'][] = $this->title;

$this->params['sidebar'] = 0;

?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">List Paket Pendaftaran</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
        <?= $model->renderTable()?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
