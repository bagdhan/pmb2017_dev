<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/25/2017
 * Time: 9:02 AM
 */

use app\modelsDB\StatusPerkawinan;
use app\modelsDB\Agama;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use app\components\Lang;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm; */
/* @var $datapribadi \app\modules\pendaftaran\models\lengkapdata\DataPribadi */

$this->title = Yii::t('pmb', 'Personal');
$this->params['breadcrumbs'][] = $this->title;

$listStatusKawin = ArrayHelper::map(StatusPerkawinan::find()->all(), 'id', 'status');
$trStatusKawin = [
    'Kawin' => 'Married',
    'Belum Kawin' => 'Single',
    'Janda/Duda' => 'Widow/Widower',
];
foreach ($listStatusKawin as $idx => $value) {
    $listStatusKawin[$idx] = Lang::t($value, isset($trStatusKawin[$value]) ? $trStatusKawin[$value] : $value);
}

$listAgama = ArrayHelper::map(Agama::find()->all(), 'id', 'agama');
$trAgama = [
    'Islam' => 'Islam',
    'Kristen Protestan' => 'Christian Protestant',
    'Kristen Katolik' => 'Christian Catholic',
	'Hindu' => 'Hindu',
    'Buddha' => 'Buddha',
    'Khonghucu' => 'Confucianism',
];
foreach ($listAgama as $idx => $value) {
    $listAgama[$idx] = Lang::t($value, isset($trAgama[$value]) ? $trAgama[$value] : $value);
}

$inputNegara = $form->field($datapribadi, 'inputNegara', ['template' => '{input}{error}'])->label(false);

?>
<div class="row panel">
    <div class="col-lg-6 col-md-6">

        <?= $form->field('namaLengkap' =>$this->namaLengkap)->textInput(['placeholder' => Yii::t('pmb', 'Full Name')])
        ->hint(Lang::t('Nama sesuai Ijazah sebelumnya', 'Enter a name without a title.'))?>

        <div class=" form-group">
            <label class="col-lg-4 col-md-4 control-label"
                   for="datapribadi-gelardepan"><?= Yii::t('pmb', 'Degree') ?></label>
            <div class="col-lg-8 col-md-8">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <?= $form->field($datapribadi, 'gelarDepan', ['template' => "{input}",])
                            ->textInput(['maxlength' => 50, 'placeholder' => 'Ir, Drs']) ?>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <?= $form->field($datapribadi, 'gelarBelakang', ['template' => "{input}",])
                            ->textInput(['maxlength' => 50, 'placeholder' => 'ST, SSi, SE']) ?>
                    </div>
                </div>
            </div>
        </div>

        <?= $form->field($datapribadi, 'tempatLahir')->textInput(['placeholder' =>
            Lang::t('', 'Enter the name of the birthplace.')]) ?>

        <?= $form->field($datapribadi, 'tanggalLahir')->textInput(['class' => 'form-control dateinput']) ?>

        <?= $form->field($datapribadi, 'jenisKelamin')->dropDownList(
            [1 => Yii::t('pmb', 'Male'), 0 => Yii::t('pmb', 'Female')], ['style' => 'width: 50%;']) ?>

        <?= $form->field($datapribadi, 'statusPerkawinan_id')->dropDownList(
            $listStatusKawin, ['style' => 'width: 50%;', 'prompt' => Yii::t('pmb', 'Choose')]) ?>

        <?= $form->field($datapribadi, 'namagadisibu', [
            'template' => "{label}\n<div class='col-md-7'>{input}{hint}{error}</div>",
            'labelOptions' => ['class' => 'col-md-5 control-label'],
        ])->textInput() ?>

        <?= $form->field($datapribadi, 'negara_id',
            ['template' => "{label}\n<div class='col-md-3 pilihan_negara'>
                            " . Html::activeDropDownList($datapribadi, 'kewarganegaraan', [1 => 'WNI', 2 => 'WNA/Foreign'],
                    ['class' => 'form-control', 'prompt' => Yii::t('pmb', 'Choose')]) .
                Html::error($datapribadi, 'kewarganegaraan') . "
                            </div><div class='col-md-5' id='inputnegaratemp'>{input}{hint}</div>{error}",])->dropDownList(
            \app\models\Orang::getCountry(), ['prompt' => Yii::t('pmb', 'Choose')]) ?>
		
		<?= $form->field($datapribadi, 'agama_id')->dropDownList(
            $listAgama, ['style' => 'width: 50%;', 'prompt' => Yii::t('pmb', 'Choose')]) ?>
		
		<?= $form->field($datapribadi, 'tinggibadan', [
            'template' => "{label}\n<div class='col-md-7'>{input}{hint}{error}</div>",
            'labelOptions' => ['class' => 'col-md-5 control-label'],
        ])->textInput() ?>
		
		<?= $form->field($datapribadi, 'beratbadan', [
            'template' => "{label}\n<div class='col-md-7'>{input}{hint}{error}</div>",
            'labelOptions' => ['class' => 'col-md-5 control-label'],
        ])->textInput() ?>
    </div>
    <div class="col-lg-6 col-md-6">

        <div class='col-md-$lbrfto col-lg-$lbrfto ' align='center'>
            <img alt='User Pic' width='50%' src='<?= $datapribadi->photo ?>' class='img-circle img-responsive'>
        </div>

        <?= $form->field($datapribadi, 'imageFile')->fileInput(['class' => 'inputfile inputfile-1']) ?>
    </div>
</div>


<?php

$link = \yii\helpers\Url::to(['/pendaftaran/lengkap-data/api?type=negara']);
$warga = Html::getInputId($datapribadi, 'kewarganegaraan');
$ipnegara = Html::getInputId($datapribadi, 'inputNegara');
$idnegara = Html::getInputId($datapribadi, 'negara_id');

$jsCode = <<< JS

var registrationForm = (function() {
    var body = $('body');
    
    var inputNegara = body.find('#inputnegaratemp');
    
    // inputNegara.hide(0);
    inputNegara.find('select').find('[value=1]').attr('hidden','');
    
    body.on('change', '#$warga', function(e) {
        var elm = $(e.target);
        
        if (elm.val() === '2') {
            inputNegara.show();
            inputNegara.find('select').val('').change();
        } else {
            inputNegara.hide(0);
            if (elm.val() === '1')
                inputNegara.find('select').val('1').change();
            else
                inputNegara.find('select').val('').change();
        }
        // alert(elm.val());
    });
    
    
})();


JS;

$this->registerJs($jsCode, View::POS_READY, 'dir')
?>

