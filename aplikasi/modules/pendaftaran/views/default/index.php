<?php
use yii\helpers\Html;
use app\modules\pendaftaran\models\FormLengkapData;
$this->title = 'Lengkapi Data Selesai';
$folmulir = new FormLengkapData();

?>

<div class="pendaftaran-default-index">
<div class="alert alert-info alert-lg">
    <h2>Proses melengkapi data sudah selesai</h2> <br> <br><br>
    <?= Html::a('Cetak Folmulir A',['/pendaftaran/lengkap-data/cetak', 'id' => FormLengkapData::$noPendaftaran],
        ['class' => 'btn btn-success'])?>
</div>


</div>
