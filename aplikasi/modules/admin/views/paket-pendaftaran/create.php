<?php
/**
 * Created by
 * User: Wisard17
 * Date: 25/02/2018
 * Time: 10.45 PM
 */

/* @var $this yii\web\View */
/* @var $model \app\modules\admin\models\paketPendaftaran\FormPaketPendaftaran */

$this->title = Yii::t('app', 'Create Paket Pendaftaran');
$this->params['breadcrumbs'][] = $this->title;

$this->params['sidebar'] = 0;

?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">Paket Pendaftaran</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
        <?= $this->render('_form', ['model' => $model])?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
