<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/30/2017
 * Time: 3:49 PM
 */

use yii\helpers\Html;
use yii\web\View;
use app\components\Lang;


/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm; */
/* @var $pilihprodi \app\modules\pendaftaran\models\lengkapdata\PilihProdi */
/* @var $model \app\modules\pendaftaran\models\Pendaftaran */

\app\adminlte\assets\plugin\iCheckAsset::register($this);


//$listS2 = Json::encode($pilihprodi::listProdi(2));
//$listS3 = Json::encode($pilihprodi::listProdi(3));
$listS2 = $pilihprodi::listProdi(2);
$listS3 = $pilihprodi::listProdi(3);

$listpl = $pilihprodi->strata == 2 ? $pilihprodi::listArrayProdi(2) : $pilihprodi::listArrayProdi(3);

$prodi1 = $pilihprodi->pilihan1 == null ? 0 : $pilihprodi->pilihan1;


$exp = '';

if ($model::$modelPendaftaran->rencanaPembiayaan != null &&
    !in_array($model::$modelPendaftaran->rencanaPembiayaan->jenisPembiayan_id, [2,9,10])) {
    $exp = $form->field($pilihprodi, 'pilihan2')->dropDownList(
        $listpl, ['class' => 'form-control', 'prompt' => Yii::t('pmb', 'Choose'),]);
}

?>

    <div class="row panel">
        <div class="col-lg-6 col-md-6">

            <?= $form->field($pilihprodi, 'strata')->dropDownList(
                [2 => Lang::t('S2','Master (S2)'), 3 => Lang::t('S3', 'Doctoral (S3)')], ['style' => 'width: 50%;', 'prompt' => Yii::t('pmb', 'Choose')]) ?>

            <br>
            <br>
            <hr>

            <?= $form->field($pilihprodi, 'pilihan1')->dropDownList(
                $listpl, ['class' => 'form-control', 'prompt' => Yii::t('pmb', 'Choose'),]) ?>
            <div id="loading" style="display: none; text-align: center;"><i class="fa fa-refresh fa-x3 fa-spin"></i></div>
            <?=  $exp?>
        </div>
    </div>

    <div class="row panel" id="hideplace" style="display: none;">
        <div class="col-lg-6 col-md-6">
            <h3><?= Lang::t('Program Studi pilihan pertama memiliki program kerjasama', 'The first choice has a cooperation program')?></h3>
            <p><?= Lang::t('Apabila berminat mengikuti program silahkan pilih salah satu dari program di bawah ini', 'We also have many academic collaboration with partner universities
If you are interested in joining the programs, please select one of the programs below')?></p>
            <div id="placetable"></div>
            <a class="btn btn-flat btn-xs pull-right" id="clearbtn"><?= \app\components\Lang::t('Clear Pilihan', 'Clear')?></a>
        </div>
    </div>

<?php

$link = \yii\helpers\Url::to(['/pendaftaran/lengkap-data/api']);
$strata = Html::getInputId($pilihprodi, 'strata');
$plh1 = Html::getInputId($pilihprodi, 'pilihan1');
$plh2 = Html::getInputId($pilihprodi, 'pilihan2');

$idk = $pilihprodi->kerjasama > 0 ? $pilihprodi->kerjasama : 0;

$jsCode = <<< JS
$(function() {
    var pilihan1 = $('select#$plh1');
    var pilihan2 = $('select#$plh2');
    
    $('select#$strata').on('change', function(e) {
        pilihan1.select2("val", "");
        if ($(this).val() == 2) {
            pilihan1.html("$listS2");
        } else {
            pilihan1.html("$listS3");
        }
        if (pilihan2 !== undefined) {
        pilihan2.select2("val", "");
        if ($(this).val() == 2)
            pilihan2.html("$listS2");
        else 
            pilihan2.html("$listS3");
        }
    });
    pilihan1.select2({
      allowClear: true
    });
    pilihan2.select2({
      allowClear: true
    });
        
    var hplace = $('#hideplace');
    var placet = $('#placetable');
    var loading = $('#loading');
    
    hplace.slideUp(400);
    
    pilihan1.on('change', function(e) {
        var elm = $(this);
        setAjax(elm.val());
    });
    
    function setAjax(idprodi, valkj = 0) {
        $.ajax({
            url: "$link",
            dataType: 'json',
            data: {"type": "kerjasama", "prodi": idprodi, "idkjs": valkj},
            beforeSend: function () {
                hplace.slideUp(400);
                loading.slideDown(400);
            },
            success: function(result){
                placet.html(result.html);
                console.log(result);
                loading.slideUp(400);
                if (result.ada == '1')
                    hplace.slideDown(400);
                else 
                    hplace.slideUp(400);

                $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                  checkboxClass: 'icheckbox_minimal-blue',
                  radioClass: 'icheckbox_minimal-blue'
                });
               
            }
        });
    }
  
    $('#clearbtn').on('click', function(e) {
        $('input[type="radio"].minimal').iCheck('uncheck');
    });
    
    if ($prodi1 > 0)
        setAjax($prodi1, $idk);

  
});





JS;

$this->registerJs($jsCode, View::POS_READY, 'plhprodi')
?>