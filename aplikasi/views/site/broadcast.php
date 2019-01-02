<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 4/26/2017
 * Time: 11:55 AM
 */

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Broadcast Email';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('brcFormSubmitted')): ?>

        <div class="alert alert-success">
            List kirim email. <br><br>
            <?= Yii::$app->session->getFlash('brcFormSubmitted')?>

        </div>

        <p>
            Note that if you turn on the Yii debugger, you should be able
            to view the mail message on the mail panel of the debugger.
            <?php if (Yii::$app->mailer->useFileTransport): ?>
                Because the application is in development mode, the email is not sent but saved as
                a file under <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
                                                                                                    Please configure the <code>useFileTransport</code> property of the <code>mail</code>
                application component to be false to enable email sending.
            <?php endif; ?>
        </p>

    <?php endif; ?>

        <p>
            list mahasiswa yang sudah verifikasi tapi belum melengkapi data.
        </p>

        <div class="row">
            <div class="col-lg-12">

                <?php $form = ActiveForm::begin(['id' => 'boadcast-form']); ?>

                <?= $form->field($model, 'paket')->dropDownList(
                        \yii\helpers\ArrayHelper::map(\app\models\PaketPendaftaran::find()->all(), 'id', 'uniqueUrl'),
                    ['prompt' => 'Pilih']) ?>

                <?= $form->field($model, 'subject') ?>

                <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>


                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>


</div>
