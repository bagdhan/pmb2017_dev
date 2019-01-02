<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/25/2017
 * Time: 9:02 AM
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm; */
/* @var $pekerjaan \app\modules\pendaftaran\models\lengkapdata\StatusPekerjaan */

$kab = $pekerjaan->kab == null ? [] : ArrayHelper::map(\app\modelsDB\KabupatenKota::find()
    ->where(['kode' => $pekerjaan->kab])->all(), 'kode', 'namaID');
$kec = $pekerjaan->kec == null ? [] : ArrayHelper::map(\app\modelsDB\Kecamatan::find()
    ->where(['kode' => $pekerjaan->kec])->all(), 'kode', 'namaID');
$des = $pekerjaan->des == null ? [] : ArrayHelper::map(\app\modelsDB\DesaKelurahan::find()
    ->where(['kode' => $pekerjaan->des])->all(), 'kode', 'namaID');


echo $form->field($pekerjaan, 'inOrIt')->hiddenInput()->label(false);
echo $form->field($pekerjaan, 'idInOrIt')->hiddenInput()->label(false);

$orang = Yii::$app->user->identity->orang != null ? Yii::$app->user->identity->orang : new \app\models\Orang();

$showEn = $orang->negara_id >1 ? false : true;

?>
<div class="row panel">
    <div class="col-lg-12 col-md-12">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <?= $form->field($pekerjaan, 'jenisInstansi_id')
                    ->dropDownList($pekerjaan->listJenis, ['style' => 'width: 50%;']) ?>
            </div>
        </div>
        <div class="row" id="hiddenfield">
            <div class="col-lg-6 col-md-6">
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($pekerjaan, 'namaInstansi')->textInput() ?>
                    </div>
                </div>
                <?= $form->field($pekerjaan, 'jabatan')->textInput() ?>

                <?= $form->field($pekerjaan, 'noIdentitas')->textInput() ?>

                <?= $form->field($pekerjaan, 'tanggalMasuk')
                    ->textInput(['style' => 'width: 50%;', 'class' => ' form-control dateinput']) ?>

                <?= $form->field($pekerjaan, 'tlp')->textInput() ?>

                <?= $form->field($pekerjaan, 'fax')->textInput() ?>

                <?= $form->field($pekerjaan, 'email')->textInput() ?>


            </div>
            <div class="col-lg-6 col-md-6">
                <h3><?= \app\components\Lang::t('Alamat Instansi', 'Office Address')?></h3>

                <?= $form->field($pekerjaan, 'jalan')->textInput(['placeholder' => Yii::t('app', 'jl. pajajaran No. 3')]) ?>

                <?= !$showEn ? '' : $form->field($pekerjaan, 'prov')->dropDownList(\app\modules\pendaftaran\models\lengkapdata\Alamat::listProvinsi(), [
                    'class' => 'form-control', 'prompt' => 'Pilih',
                    'onchange' => '
                        $.get( "' . Yii::$app->urlManager->createUrl('/api/lists-wilayah?jenis=kab&codeprov=') .
                        '"+$(this).val(), function( data ) {
                                      $( "select#' . Html::getInputId($pekerjaan, 'kab') . '" ).select2("val", "");
                                      $( "select#' . Html::getInputId($pekerjaan, 'kab') . '" ).html( data );
                                      $( "select#' . Html::getInputId($pekerjaan, 'kec') . '" ).select2("val", "");
                                      $( "select#' . Html::getInputId($pekerjaan, 'des') . '" ).select2("val", "");
                                    });'
                ]) ?>

                <?= !$showEn ? '' : $form->field($pekerjaan, 'kab')->dropDownList($kab, [
                    'class' => ' form-control', 'prompt' => 'Pilih',
                    'onchange' => '
                        $.get( "' . Yii::$app->urlManager->createUrl('/api/lists-wilayah?jenis=kec&codekab=') .
                        '"+$(this).val(), function( data ) {
                                      $( "select#' . Html::getInputId($pekerjaan, 'kec') . '" ).html( data );
                                      $( "select#' . Html::getInputId($pekerjaan, 'des') . '" ).select2("val", "");
                                    });'
                ]) ?>

                <?= !$showEn ? '' : $form->field($pekerjaan, 'kec')->dropDownList($kec, [
                    'class' => ' form-control', 'prompt' => 'Pilih',
                    'onchange' => '
                        $.get( "' . Yii::$app->urlManager->createUrl('/api/lists-wilayah?jenis=des&codekec=') .
                        '"+$(this).val(), function( data ) {
                                      $( "select#' . Html::getInputId($pekerjaan, 'des') . '" ).html( data );
                                    });'
                ]) ?>

                <?= !$showEn ? '' : $form->field($pekerjaan, 'des')->dropDownList($des, [
                    'class' => ' form-control', 'prompt' => 'Pilih',
                ]) ?>
            </div>
        </div>
    </div>
</div>


<?php

$link = \yii\helpers\Url::to(['/pendaftaran/lengkap-data/api?type=kerja']);
$jenisKerja = Html::getInputId($pekerjaan, 'jenisInstansi_id');
$namainstusi = Html::getInputId($pekerjaan, 'namaInstansi');
$idinstitusi = Html::getInputId($pekerjaan, 'idInOrIt');
$provid = Html::getInputId($pekerjaan, 'prov');
$kabid = Html::getInputId($pekerjaan, 'kab');
$kecid = Html::getInputId($pekerjaan, 'kec');
$desid = Html::getInputId($pekerjaan, 'des');

$tanggalMasuk = Html::getInputId($pekerjaan, 'tanggalMasuk');

$jsCode = <<< JS

var options = {
    url: "$link",
    getValue: "nama",
    template: {
        type: "description"
    },

    list: {
        match: {
            enabled: true
        },
        onChooseEvent: function() {
            var value = $("#$namainstusi").getSelectedItemData().id;
            // $("#").val(value);
            
		},
		onSelectItemEvent: function() {
//		    $("#").val('');
		}
    }
};

var hidden = $('#hiddenfield').html();

if ($('#$jenisKerja').val() == 1)
    $('#hiddenfield').html('');

    $("#$provid").select2({allowClear: true});
    $("#$kabid").select2();
    $("#$kecid").select2();
    $("#$desid").select2();

$('select#$jenisKerja').on('change', function(e) {
    
    if ($(this).val() == 1) {
        $('#hiddenfield').html('');
    } else {
        $('#hiddenfield').html(hidden);
        $('#$tanggalMasuk').datepicker({
            format: "dd-mm-yyyy"
        });
        $("#$provid").select2({allowClear: true});
        $("#$kabid").select2();
        $("#$kecid").select2();
        $("#$desid").select2();
        $("#$namainstusi").easyAutocomplete(options);
        
    }  
});


JS;

$this->registerJs($jsCode, View::POS_READY, 'kerja')
?>


