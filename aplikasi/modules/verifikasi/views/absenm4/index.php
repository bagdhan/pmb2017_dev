<?php

/* @var $this yii\web\View */

/* @var $absenform \app\modules\verifikasi\models\Absenm4Form */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;


$this->title = Yii::t('app', 'Tahap Pemeriksaan Bahasa, Kesehatan dan Berkas (M4)');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Verifikasi2'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <h1>Absen Verifikasi</h1>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>

<?= $form->field($absenm4Form, 'noPendaftaran')->textInput(['autoFocus' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'submit'), ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>


<?php
if (isset($_GET['Absenm4Form']['noPendaftaran']) && $_GET['Absenm4Form']['noPendaftaran'] != '') {
    echo "<div class=\"box box-default color-palette-box\">
    <div class=\"box-header with-border\">
        <h3 class=\"box-title\">Detail Mahasiswa</h3>
    </div>
    <div class=\"box-body\">
        ";
    echo $absenm4Form->getDetail($_GET['Absenm4Form']['noPendaftaran']);

    echo "</div>
    </div>";


    $inputan = Html::getInputId($absenm4Form, 'noPendaftaran');

    $this->registerJs("$('#$inputan').val('');", View::POS_READY, 'clearinput');
}

