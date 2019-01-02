<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */

use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;
use app\models\PaketPendaftaran;

$get = Yii::$app->request->get();
$paketPendaftar = PaketPendaftaran::findOne(['uniqueUrl' => isset($get['jenismasuk']) ? $get['jenismasuk'] : 1]);
$daftartitle = empty($paketPendaftar) ? "" : $paketPendaftar->title;

$this->title = Yii::t('pmb', 'Creating New Account') . ' ' . $daftartitle;
$this->params['breadcrumbs'][] = $this->title;


?>
<style>
    .form-horizontal .control-label {
        text-align: left;
    }
</style>
<div class="site-signup">

    <p><?= Yii::t('pmb', 'If you have previously registered, please create an account with a different email address.')?> </p>

    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin([
                'id' => 'form_registrasi',
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-8\">{input}{hint}{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-4 control-label'],
                ],
            ]); ?>

            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('pmb', 'Account')?></h3>
                </div>
                <div class="box-body">
                    <p class="help-block">
                        <strong style="color: red">*</strong> <?= Yii::t('pmb', 'required')?>
                    </p>
                    <?= $form->field($model, 'username')
                        ->textInput(['autofocus' => true])
                        ->hint(Yii::t('pmb', 'the username prefix must be a letter, username must not contain spaces.'))
                    ?>

                    <?= $form->field($model, 'password')->passwordInput()
                        ->hint(Yii::t('pmb', 'password consists of at least 6 characters, different capital letters and lowercase letters.')) ?>

                    <?= $form->field($model, 'password2')->passwordInput()
                        ->hint('') ?>

                </div>
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('pmb', 'Personal')?></h3>
                </div>
                <div class="box-body">

                    <?= $form->field($model, 'namaLengkap')->textInput(['style' => 'text-transform: capitalize;'])
                        ->hint(Yii::t('pmb', 'the name corresponds to the identity card, without an academic degree or certification.')) ?>

                    <?= $form->field($model, 'jenisKelamin')
                        ->dropDownList([1 => Yii::t('pmb', 'Male'), 0 => Yii::t('pmb', 'Female')], ['prompt' => Yii::t('pmb', 'Choose')]) ?>

                    <?= $form->field($model, 'idNegara',
                        ['template' => "{label}\n<div class='col-md-3 pilihan_negara'>
                            ". Html::activeDropDownList($model, 'choseNationality', [1 => 'WNI', 2=> 'WNA/Foreign'],
                                ['class' => 'form-control', 'prompt' => Yii::t('pmb', 'Choose')]).
                            Html::error($model,'choseNationality')."
                            </div><div class='col-md-5' id='inputnegaratemp'>{input}{hint}</div>{error}",])->dropDownList(
                        \app\models\Orang::getCountry(), ['prompt' => Yii::t('pmb', 'Choose')]) ?>

                    <?= $form->field($model, 'identitas', [
                        'template' => "{label}<div class='col-lg-2'>".
                            Html::activeDropDownList($model, 'jenisIdentitas',[],
                                ['class' => 'form-control', 'prompt' => Yii::t('pmb', 'Choose')]).
                            Html::error($model, 'identitas') ."</div>
                    <div class='col-lg-6'>{input}{error}</div>",
                    ]) ?>

                </div>
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('pmb', 'Contact')?></h3>
                </div>
                <div class="box-body">

                    <?= $form->field($model, 'email')->input('email')
                        ->hint(Yii::t('pmb', 'Write down your current primary email address used! <strong style="color: red;"> This address will be used to inform all information related to Student Registration </strong>')) ?>

                    <?= $form->field($model, 'email2') ?>

                    <?= $form->field($model, 'noTlp')
                        ->hint(Yii::t('pmb', 'Write down your contact phone number with the format + 62xxxxxxxxxxx (your area code without zero). Example: +622518628448 (+62 = code INDONESIA; 251 = kab / city BOGOR; 8628448 = phone number')) ?>

                    <?= $form->field($model, 'noHP')
                        ->hint(Yii::t('pmb', 'Enter your mobile phone contact number in + 628xxxxxxxxxxx format (WITHOUT ZERO NUMBER on its mobile number.) Example: +6281212344321 (+62 = INDONESIAN code; 81212344321 = mobile number). <Strong style = "color: red;"> SPs IPB will contact you via this contact number </strong>')) ?>
                </div>
                <div class="box-body">
                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ])->hint(Yii::t('pmb', 'enter the verification code above.')) ?>
                </div>
                <div class="box-footer">

                    <?= Html::submitButton(Yii::t('pmb', 'Register'), ['class' => 'btn btn-info pull-right', 'name' => 'signup-button']) ?>

                </div>

            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php

$idNasional = Html::getInputId($model, 'choseNationality');
$jId = Html::getInputId($model, 'jenisIdentitas');

$as = $model->getJenisId(2);
$id = $model->getJenisId(1);

$jsScript = <<< JS

var registrationForm = (function() {
    var body = $('body');
    
    var inputNegara = body.find('#inputnegaratemp');
    
    inputNegara.hide(0);
    inputNegara.find('select').find('[value=1]').attr('hidden','');
    
    var jid = body.find('#$jId');
    
    body.on('change', '#$idNasional', function(e) {
        var elm = $(e.target);
        
        if (elm.val() === '2') {
            inputNegara.show();
            inputNegara.find('select').val('').change();
            jid.html('');
            jid.append("$as"); 
        } else {
            inputNegara.hide(0);
            if (elm.val() === '1')
                inputNegara.find('select').val('1').change();
            else
                inputNegara.find('select').val('').change();
            jid.html('');
            jid.append("$id"); 
        }
        // alert(elm.val());
    });
    
    
})();

JS;

$this->registerJs($jsScript, \yii\web\View::POS_READY, 'registration_form');