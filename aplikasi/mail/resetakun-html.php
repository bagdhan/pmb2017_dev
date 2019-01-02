<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \app\usermanager\models\User */
/* @var $model \app\models\Orang */
//$username = Yii::$app->user->identity->username;



$link = Yii::$app->urlManager->createAbsoluteUrl(['/pendaftaran/verifikasi/pin/']);
$linkreset = Yii::$app->urlManager->createAbsoluteUrl(['/site/request-password-reset']);
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-username-password', 'token' => $user->passwordResetToken]);
?>
<div class="password-reset">

    <h2>Yth Bapak/Ibu pemilik email <?= $user->email ?></h2>

    <p>Dikarnakan ada perbaikan sistem dimohonkan untuk mengisi kembali <strong>username dan password</strong> anda pada formulir
        dengan mengakses link dibawa ini (link hanya berlaku dua hari sejak email ini dikirimkan).
        <br>
        <br>
        <?= Html::a(Html::encode($resetLink), $resetLink) ?>
        <br>
        <br>

        Akun anda memiliki No Pendaftaran : <?= $listNopen?><br>
        apabilah telah melakukan pembayaran di bank BNI, pastikan No Pendaftaran yang ada di bukti pembayaran sama dengan
        No Pendaftaran pada Formulir di atas, kemudian verifikasi PIN anda.<br>
        apabila belum melakukan pembayaran pilih salah satu nomor pendaftaran tersebut untuk melakukan pembayaran melalui Bank BNI untuk memperoleh PIN.
        Tata Cara pembayaran silakan klik tautan berikut ini: <a href="http://pmb2017.dev/carabayarbni">Tata cara pembayaran</a>
        <br>
        <br>
        Langkah selanjutnya silahkan verifikasi PIN anda melalui http://pmbpasca.ipb.ac.id atau
        klik link berikut ini: <a href="<?= $link ?>"> link verifikasi PIN</a>, lalu lengkapi data sesuai dengan
        tahapan yang tertera.
        <br><br><br>
        Jika anda tidak merasa melakukan pendaftaran akun pada sistem kami, maka abaikan email ini.
    </p>

    <p>
    Hormat Kami,<br>
    Panitia Penerimaan Mahasiswa Baru <br>
    Sekolah Pascasarjana IPB <br>
    http://pmbpasca.ipb.ac.id <br>

        <br>
    Email ini dikirimkan secara otomatis oleh sistem, kami tidak melakukan pengecakan email yang dikirimkan ke email
    ini.
    </p>
    <p>
    Jika ada pertanyaan, silahkan hubungi 0251-8628448
    </p>
</div>