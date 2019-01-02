<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/4/2017
 * Time: 8:25 PM
 */


use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm; */
/* @var $rencanabiaya \app\modules\pendaftaran\models\lengkapdata\PerencanaanBiaya */

$orang = Yii::$app->user->identity->orang != null ? Yii::$app->user->identity->orang : new \app\models\Orang();

echo $form->field($rencanabiaya, 'id')->hiddenInput()->label(false);
if ($orang->negara_id >1)
    $rencanabiaya->jenisPembiayan_id = 8;
?>

    <div class="row panel">
        <div class="col-lg-6 col-md-6">

            <?= $orang->negara_id > 1 ? $form->field($rencanabiaya, 'jenisPembiayan_id')->hiddenInput()->label(false)
                : $form->field($rencanabiaya, 'jenisPembiayan_id', [
                'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>',
                'labelOptions' => ['class' => 'col-md-7 control-label'],
            ])->dropDownList(
                $rencanabiaya->listJenis, ['prompt' => Yii::t('pmb', 'Choose')]) ?>

            <br>
            <div id="sebutkan">
                <?= $form->field($rencanabiaya, 'deskripsi')->textInput() ?>
            </div>

        </div>
    </div>

<?php

$link = \yii\helpers\Url::to(['/pendaftaran/lengkap-data/api']);
$jenis = Html::getInputId($rencanabiaya, 'jenisPembiayan_id');

$jsCode = <<< JS

var savehtml = $('#sebutkan').html();

if ($.inArray(parseInt($('#$jenis').val()), [1,2,3,4,5,9,10]) !== -1)
    $('#sebutkan').html('');

$('select#$jenis').on('change', function(e) {
    
    if ($.inArray(parseInt($(this).val()), [1,2,3,4,5,9,10]) !== -1) {
        $('#sebutkan').html(''); 
    } else {
        $('#sebutkan').html(savehtml);
    }  
});

JS;

$this->registerJs($jsCode, View::POS_READY, 'biaya')
?>