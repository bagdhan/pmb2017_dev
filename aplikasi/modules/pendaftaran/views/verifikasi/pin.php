<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\pendaftaran\models\FormVerifikasiPIN */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\widgets\Captcha;

$this->title = 'Verifikasi PIN';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup">



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

                <div class="box-body">

                    <p>Silahkan masukan No Pendaftaran dan PIN. <br>
                        PIN diperoleh setelah melakukan pembayaran di Bank BNI</p>

                    <?= $form->field($model, 'noPendaftaran')
                        ->textInput(['autofocus' => true])
                    ?>

                    <?= $form->field($model, 'inputpin') ?>
                </div>

                <div class="box-body">
                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ])->hint('masukan kode verifikasi di atas.') ?>
                </div>

                <div class="box-footer">

                    <?= Html::submitButton('Verifikasi', ['class' => 'btn btn-info pull-right', 'name' => 'signup-button']) ?>

                </div>

            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
