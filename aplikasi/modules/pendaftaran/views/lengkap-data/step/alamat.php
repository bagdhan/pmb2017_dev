<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/25/2017
 * Time: 9:02 AM
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\modelsDB\StatusPerkawinan;

/* @var $this yii\web\View */
/* @var $form \yii\widgets\ActiveForm */
/* @var $alamat \app\modules\pendaftaran\models\lengkapdata\Alamat*/

$listStatusKawin = ArrayHelper::map(StatusPerkawinan::find()->all(), 'id', 'status');
$kab = $alamat->kab == null ? [] : ArrayHelper::map(\app\modelsDB\KabupatenKota::find()
    ->where(['kode' => $alamat->kab])->all(),'kode','namaID');
$kec = $alamat->kec == null ? [] : ArrayHelper::map(\app\modelsDB\Kecamatan::find()
    ->where(['kode' => $alamat->kec])->all(),'kode','namaID');
$des = $alamat->desaKelurahan_kode == null ? [] : ArrayHelper::map(\app\modelsDB\DesaKelurahan::find()
    ->where(['kode' => $alamat->desaKelurahan_kode])->all(),'kode','namaID');


?>
<div class="row panel">
    <div class="col-lg-12 col-md-12">

        <?= $form->field($alamat, 'jalan')->textarea(['placeholder' => \app\components\Lang::t('jl. pajajaran No. 3', '')]) ?>
        <?php if ($alamat->st == 1) {?>
        <div class=" form-group">
            <label class="col-lg-4 col-md-4 control-label" for="datapribadi-gelardepan">RT/RW</label>
            <div class="col-lg-8 col-md-8">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <?= $form->field($alamat, 'rt', ['template' => "{input}",])
                            ->textInput(['maxlength' => 10, 'placeholder' => 'RT']) ?>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <?= $form->field($alamat, 'rw', ['template' => "{input}",])
                            ->textInput(['maxlength' => 10, 'placeholder' => 'RW']) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>
        <?= $form->field($alamat, 'kodePos')->textInput(['style' => 'width: 50%;'])?>

        <?= $alamat->st == 1 ? $form->field($alamat, 'prov')->dropDownList(\app\modules\pendaftaran\models\lengkapdata\Alamat::listProvinsi(),[
            'class' => 'select2 form-control', 'prompt' => 'Pilih',
            'onchange'=>'
                        $.get( "'.Yii::$app->urlManager->createUrl('/api/lists-wilayah?jenis=kab&codeprov=').
                '"+$(this).val(), function( data ) {
                                      $( "select#'.Html::getInputId($alamat, 'kab').'" ).select2("val", "");
                                      $( "select#'.Html::getInputId($alamat, 'kab').'" ).html( data );
                                      $( "select#'.Html::getInputId($alamat, 'kec').'" ).select2("val", "");
                                      $( "select#'.Html::getInputId($alamat, 'desaKelurahan_kode').'" ).select2("val", "");
                                    });'
        ]) : ''?>

        <?= $alamat->st == 1 ? $form->field($alamat, 'kab')->dropDownList($kab,[
            'class' => 'select2 form-control', 'prompt' => 'Pilih',
            'onchange'=>'
                        $.get( "'.Yii::$app->urlManager->createUrl('/api/lists-wilayah?jenis=kec&codekab=').
                '"+$(this).val(), function( data ) {
                                      $( "select#'.Html::getInputId($alamat, 'kec').'" ).html( data );
                                      $( "select#'.Html::getInputId($alamat, 'desaKelurahan_kode').'" ).select2("val", "");
                                    });'
        ]) : ''?>

        <?= $alamat->st == 1 ? $form->field($alamat, 'kec')->dropDownList($kec,[
            'class' => 'select2 form-control', 'prompt' => 'Pilih',
            'onchange'=>'
                        $.get( "'.Yii::$app->urlManager->createUrl('/api/lists-wilayah?jenis=des&codekec=').
                '"+$(this).val(), function( data ) {
                                      $( "select#'.Html::getInputId($alamat, 'desaKelurahan_kode').'" ).html( data );
                                    });'
        ]) : ''?>

        <?= $alamat->st == 1 ? $form->field($alamat, 'desaKelurahan_kode')->dropDownList($des,[
            'class' => 'select2 form-control', 'prompt' => 'Pilih',
        ]) : ''?>

    </div>
</div>




