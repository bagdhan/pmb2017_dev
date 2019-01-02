<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */

use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

$this->title = 'Confirmasi Akun';
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
    .form-horizontal .control-label {
        text-align: left;
    }
</style>
<div class="site-signup">

    <p>Silahkan lengkapi folmulir berikut. </p>

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
                    <h3 class="box-title">Akun</h3>
                </div>
                <div class="box-body">
                    <p class="help-block">
                        <strong style="color: red">*</strong> WAJIB DIISI
                    </p>
                    <?= $form->field($model, 'username')
                        ->textInput(['autofocus' => true])
                        ->hint('awalan username harus berupa huruf, username tidak boleh mengandung spasi. ')
                    ?>

                    <?= $form->field($model, 'password')->passwordInput()
                        ->hint('password minimal terdiri dari 6 karakter, huruf kapital dan huruf kecil berbeda.') ?>

                    <?= $form->field($model, 'password2')->passwordInput()
                        ->hint('') ?>

                    <?= $form->field($model, 'email')
                        ->textInput(['readonly' => ''])
                        ->hint('')
                    ?>

                    <?= $form->field($model, 'noPendaftaran')
                        ->textInput()
                        ->hint('apabilah anda telah melakukan pembayaran di bank BNI, pastikan No Pendaftaran yang anda masukan 
                            sama dengan bukti pembayaran. apabila belum melakukan pembayaran masukan no pendaftaran yang akan digunakan
                            pada pembayaran di BNI')
                    ?>
                </div>

                <div class="box-footer">

                    <?= Html::submitButton('Submit', ['class' => 'btn btn-info pull-right', 'name' => 'signup-button']) ?>

                </div>

            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
