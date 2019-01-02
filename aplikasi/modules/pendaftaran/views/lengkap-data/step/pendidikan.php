<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/30/2017
 * Time: 2:38 PM
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use app\components\Lang;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm; */
/* @var $pendidikan \app\models\Pendidikan[] */

$kab['s1'] = $pendidikan['s1']->kab == null ? [] : ArrayHelper::map(\app\modelsDB\KabupatenKota::find()
    ->where(['kode' => $pendidikan['s1']->kab])->all(), 'kode', 'namaID');
$kec['s1'] = $pendidikan['s1']->kec == null ? [] : ArrayHelper::map(\app\modelsDB\Kecamatan::find()
    ->where(['kode' => $pendidikan['s1']->kec])->all(), 'kode', 'namaID');
$des['s1'] = $pendidikan['s1']->des == null ? [] : ArrayHelper::map(\app\modelsDB\DesaKelurahan::find()
    ->where(['kode' => $pendidikan['s1']->des])->all(), 'kode', 'namaID');

$kab['s2'] = $pendidikan['s2']->kab == null ? [] : ArrayHelper::map(\app\modelsDB\KabupatenKota::find()
    ->where(['kode' => $pendidikan['s2']->kab])->all(), 'kode', 'namaID');
$kec['s2'] = $pendidikan['s2']->kec == null ? [] : ArrayHelper::map(\app\modelsDB\Kecamatan::find()
    ->where(['kode' => $pendidikan['s2']->kec])->all(), 'kode', 'namaID');
$des['s2'] = $pendidikan['s2']->des == null ? [] : ArrayHelper::map(\app\modelsDB\DesaKelurahan::find()
    ->where(['kode' => $pendidikan['s2']->des])->all(), 'kode', 'namaID');

$orang = Yii::$app->user->identity->orang != null ? Yii::$app->user->identity->orang : new \app\models\Orang();

$showEn = $orang->negara_id >1 ? false : true;
//print_r($pendidikan['s1']);die;
?>

<?= $form->field($pendidikan['s1'], 'idPT')->hiddenInput()->label(false) ?>
<?= $form->field($pendidikan['s2'], 'idPT')->hiddenInput()->label(false) ?>

<?= $form->field($pendidikan['s1'], 'institusi_id')->hiddenInput()->label(false) ?>
<?= $form->field($pendidikan['s2'], 'institusi_id')->hiddenInput()->label(false) ?>

    <div class="row panel">
        <div class="col-lg-6 col-md-6">
            <h4><?= Lang::t('Sarjana', 'Bachelor degree')?></h4>

            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <?= $form->field($pendidikan['s1'], 'namaUniversitas')->textInput()
                        ->hint(Lang::t('Penulisan nama Perguruan Tinggi harus lengkap. Contoh : Institut Pertanian Bogor',
                            'The University’s name should be written completely.'))?>
                </div>
            </div>
            <?= $form->field($pendidikan['s1'], 'fakultas')->textInput() ?>
            <?= $form->field($pendidikan['s1'], 'programStudi')->textInput() ?>

            <?= !$showEn ? '' : $form->field($pendidikan['s1'], 'akreditasi')->dropDownList([
                null => Yii::t('pmb', 'Choose'),
                'U' => 'Unggul',
                'A' => 'A',
                'B' => 'B',
                'C' => 'C',
                'T' => 'Belum Terakreditasi',
            ], ['style' => 'width: 30%;']) ?>

            <?= $form->field($pendidikan['s1'], 'ipk')->textInput(['style' => 'width: 30%;'])
                ->hint(Lang::t('Format IPK x.xx, contoh 3.10','GPA format x, xx, example 3.10.')) ?>

            <?= $form->field($pendidikan['s1'], 'jumlahSKS')->textInput(['style' => 'width: 30%;']) ?>

            <?= $form->field($pendidikan['s1'], 'tahunMasuk')->textInput(['style' => 'width: 40%;']) ?>

            <?= $form->field($pendidikan['s1'], 'tanggalLulus')->textInput(['style' => 'width: 50%;', 'class' => 'form-control dateinput']) ?>

            <?= $form->field($pendidikan['s1'], 'gelar')->textInput(['style' => 'width: 30%;']) ?>

            <?= $form->field($pendidikan['s1'], 'noIjazah')->textInput(['style' => 'width: 50%;']) ?>

            <?= $form->field($pendidikan['s1'], 'judulTA')->textarea() ?>

            <?= $form->field($pendidikan['s1'], 'jalan')->textarea() ?>


            <?= !$showEn ? '' : $form->field($pendidikan['s1'], 'prov')->dropDownList(\app\modules\pendaftaran\models\lengkapdata\Alamat::listProvinsi(), [
                'class' => 'select2 form-control', 'prompt' => 'Pilih',
                'onchange' => '
                        $.get( "' . Yii::$app->urlManager->createUrl('/api/lists-wilayah?jenis=kab&codeprov=') .
                    '"+$(this).val(), function( data ) {
                                      $( "select#' . Html::getInputId($pendidikan['s1'], 'kab') . '" ).select2("val", "");
                                      $( "select#' . Html::getInputId($pendidikan['s1'], 'kab') . '" ).html( data );
                                      $( "select#' . Html::getInputId($pendidikan['s1'], 'kec') . '" ).select2("val", "");
                                      $( "select#' . Html::getInputId($pendidikan['s1'], 'des') . '" ).select2("val", "");
                                    });'
            ]) ?>

            <?= !$showEn ? '' : $form->field($pendidikan['s1'], 'kab')->dropDownList($kab, [
                'class' => 'select2 form-control', 'prompt' => 'Pilih',
                'onchange' => '
                        $.get( "' . Yii::$app->urlManager->createUrl('/api/lists-wilayah?jenis=kec&codekab=') .
                    '"+$(this).val(), function( data ) {
                                      $( "select#' . Html::getInputId($pendidikan['s1'], 'kec') . '" ).html( data );
                                      $( "select#' . Html::getInputId($pendidikan['s1'], 'kec') . '" ).select2("val", "");
                                      $( "select#' . Html::getInputId($pendidikan['s1'], 'des') . '" ).select2("val", "");
                                    });'
            ]) ?>

            <?= !$showEn ? '' : $form->field($pendidikan['s1'], 'kec')->dropDownList($kec, [
                'class' => 'select2 form-control', 'prompt' => 'Pilih',
                'onchange' => '
                        $.get( "' . Yii::$app->urlManager->createUrl('/api/lists-wilayah?jenis=des&codekec=') .
                    '"+$(this).val(), function( data ) {
                                      $( "select#' . Html::getInputId($pendidikan['s1'], 'des') . '" ).html( data );
                                    });'
            ]) ?>

            <?= !$showEn ? '' : $form->field($pendidikan['s1'], 'des')->dropDownList($des, [
                'class' => 'select2 form-control', 'prompt' => 'Pilih',
            ]) ?>

        </div>
        <div class="col-lg-6 col-md-6">
            <?php if ($model->strata == 3) { ?>

                <h4><?= Lang::t('Magister', 'Master')?></h4>
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <?= $form->field($pendidikan['s2'], 'namaUniversitas')->textInput()
                            ->hint(Lang::t('Penulisan nama Perguruan Tinggi harus lengkap. Contoh : Institut Pertanian Bogor',
                                'The University’s name should be written completely.'))?>
                    </div>
                </div>
                <?= $form->field($pendidikan['s2'], 'fakultas')->textInput() ?>
                <?= $form->field($pendidikan['s2'], 'programStudi')->textInput() ?>

                <?= !$showEn ? '' : $form->field($pendidikan['s2'], 'akreditasi')->dropDownList([
                    null => Yii::t('pmb', 'Choose'),
                    'U' => 'Unggul',
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'T' => 'Belum Terakreditasi',
                ], ['style' => 'width: 30%;']) ?>

                <?= $form->field($pendidikan['s2'], 'ipk')->textInput(['style' => 'width: 30%;'])
                    ->hint(Lang::t('Format IPK x.xx, contoh 3.10','GPA format x, xx, example 3.10.')) ?>

                <?= $form->field($pendidikan['s2'], 'jumlahSKS')->textInput(['style' => 'width: 30%;']) ?>

                <?= $form->field($pendidikan['s2'], 'tahunMasuk')->textInput(['style' => 'width: 40%;']) ?>

                <?= $form->field($pendidikan['s2'], 'tanggalLulus')->textInput(['style' => 'width: 50%;', 'class' => 'form-control dateinput']) ?>

                <?= $form->field($pendidikan['s2'], 'gelar')->textInput(['style' => 'width: 30%;']) ?>

                <?= $form->field($pendidikan['s2'], 'noIjazah')->textInput(['style' => 'width: 50%;']) ?>

                <?= $form->field($pendidikan['s2'], 'judulTA')->textarea() ?>

                <?= $form->field($pendidikan['s2'], 'jalan')->textarea() ?>

                <?= !$showEn ? '' : $form->field($pendidikan['s2'], 'prov')->dropDownList(\app\modules\pendaftaran\models\lengkapdata\Alamat::listProvinsi(), [
                    'class' => 'select2 form-control', 'prompt' => 'Pilih',
                    'onchange' => '
                        $.get( "' . Yii::$app->urlManager->createUrl('/api/lists-wilayah?jenis=kab&codeprov=') .
                        '"+$(this).val(), function( data ) {
                                      $( "select#' . Html::getInputId($pendidikan['s2'], 'kab') . '" ).select2("val", "");
                                      $( "select#' . Html::getInputId($pendidikan['s2'], 'kab') . '" ).html( data );
                                      $( "select#' . Html::getInputId($pendidikan['s2'], 'kec') . '" ).select2("val", "");
                                      $( "select#' . Html::getInputId($pendidikan['s2'], 'des') . '" ).select2("val", "");
                                    });'
                ]) ?>

                <?= !$showEn ? '' : $form->field($pendidikan['s2'], 'kab')->dropDownList($kab, [
                    'class' => 'select2 form-control', 'prompt' => 'Pilih',
                    'onchange' => '
                        $.get( "' . Yii::$app->urlManager->createUrl('/api/lists-wilayah?jenis=kec&codekab=') .
                        '"+$(this).val(), function( data ) {
                                      $( "select#' . Html::getInputId($pendidikan['s2'], 'kec') . '" ).html( data );
                                      $( "select#' . Html::getInputId($pendidikan['s2'], 'kec') . '" ).select2("val", "");
                                      $( "select#' . Html::getInputId($pendidikan['s2'], 'des') . '" ).select2("val", "");
                                    });'
                ]) ?>

                <?= !$showEn ? '' : $form->field($pendidikan['s2'], 'kec')->dropDownList($kec, [
                    'class' => 'select2 form-control', 'prompt' => 'Pilih',
                    'onchange' => '
                        $.get( "' . Yii::$app->urlManager->createUrl('/api/lists-wilayah?jenis=des&codekec=') .
                        '"+$(this).val(), function( data ) {
                                      $( "select#' . Html::getInputId($pendidikan['s2'], 'des') . '" ).html( data );
                                    });'
                ]) ?>

                <?= !$showEn ? '' : $form->field($pendidikan['s2'], 'des')->dropDownList($des, [
                    'class' => 'select2 form-control', 'prompt' => 'Pilih',
                ]) ?>
            <?php } ?>

        </div>
    </div>

<?php

$link = \yii\helpers\Url::to(['/pendaftaran/lengkap-data/api']);
$idUniv = Html::getInputId($pendidikan['s1'], 'namaUniversitas');
$idIstitu = Html::getInputId($pendidikan['s1'], 'institusi_id');
$jalan  = Html::getInputId($pendidikan['s1'], 'jalan');

$idUniv2 = Html::getInputId($pendidikan['s2'], 'namaUniversitas');
$idIstitu2 = Html::getInputId($pendidikan['s2'], 'institusi_id');
$jalan2  = Html::getInputId($pendidikan['s2'], 'jalan');

$jsCode = <<< JS

var options = {
    url: function(phrase) {
		return "$link" + "?phrase=" + phrase ;
	},
    getValue: "nama",
    template: {
        type: "description",
        fields: {
            description: "kodePT"
        }
    },

    list: {
        match: {
            enabled: true
        },
        onChooseEvent: function() {
            var data = $("#$idUniv").getSelectedItemData();
            console.log(data);
            
            $("#$idIstitu").val(data.id);
            $("#$jalan").val(data.alamat_jalan);
		},
		onSelectItemEvent: function() {
		    $("#$idIstitu").val('');
		}
    },
    
    requestDelay: 500
};

var options2 = {
    url: function(phrase) {
		return "$link" + "?phrase=" + phrase ;
	},
    getValue: "nama",
    template: {
        type: "description",
        fields: {
            description: "kodePT"
        }
    },

    list: {
        match: {
            enabled: true
        },
        onChooseEvent: function() {
            var data = $("#$idUniv2").getSelectedItemData();
            console.log(data);
            
            $("#$idIstitu2").val(data.id);
            $("#$jalan2").val(data.alamat_jalan);
		},
		onSelectItemEvent: function() {
		    $("#$idIstitu2").val('');
		}
    }, 
    requestDelay: 500
};

$("#$idUniv").easyAutocomplete(options);
$("#$idUniv2").easyAutocomplete(options2);
JS;

$this->registerJs($jsCode, View::POS_READY, 'pendi')
?>