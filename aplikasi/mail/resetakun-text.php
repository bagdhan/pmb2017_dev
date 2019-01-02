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


    Yth Bapak/Ibu pemilik email <?= $user->email ?>

    Dikarnakan ada perbaikan sistem dimohonkan untuk mengisi kembali <strong>username, password dan No Pendaftaran</strong> anda pada formulir
    dengan mengakses link dibawa ini (link hanya berlaku dua hari sejak email ini dikirimkan).



        <?= Html::a(Html::encode($resetLink), $resetLink) ?>



        Akun anda memiliki No Pendaftaran : <?= $listNopen?>
        apabilah telah melakukan pembayaran di bank BNI, pastikan No Pendaftaran yang ada di bukti pembayaran sama dengan
        No Pendaftaran pada Formulir di atas, kemudian verifikasi PIN anda.
        apabila belum melakukan pembayaran silahkan pilih salah satu nomor pendaftaran tersebut untuk melakukan pembayaran melalui Bank BNI untuk memperoleh PIN.
        Tata Cara pembayaran silakan klik tautan berikut ini: <a href="http://pmb2017.dev/carabayarbni">Tata cara pembayaran</a>


        Langkah selanjutnya silahkan verifikasi PIN anda melalui http://pmbpasca.ipb.ac.id atau
        klik link berikut ini: <a href="<?= $link ?>"> link verifikasi PIN</a>, lalu lengkapi data sesuai dengan
        tahapan yang tertera.



        Jika anda tidak merasa melakukan pendaftaran akun pada sistem kami, maka abaikan email ini.

    Hormat Kami,
    Panitia Penerimaan Mahasiswa Baru
    Sekolah Pascasarjana IPB
    http://pmbpasca.ipb.ac.id

    Email ini dikirimkan secara otomatis oleh sistem, kami tidak melakukan pengecakan email yang dikirimkan ke email
    ini.

    Jika ada pertanyaan, silahkan hubungi 0251-8628448

