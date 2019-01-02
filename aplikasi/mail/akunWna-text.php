<?php

/* @var $this yii\web\View */
/* @var $model \app\modules\pendaftaran\models\Pendaftaran */
//$username = Yii::$app->user->identity->username;
$link = Yii::$app->urlManager->createAbsoluteUrl(['/pendaftaran/verifikasi/pin/']);
$linkreset = Yii::$app->urlManager->createAbsoluteUrl(['/site/request-password-reset']);

?>


Yth Bapak/Ibu pemilik email <?= $email?>

    Pada tanggal <?= date('d-M-Y')?> sistem kami menerima permintaan pendaftaran akun untuk Anda.

    Nama        : <?= $model->orang->nama?>
    Nama Akun   : <?= $username ?>
    Password    : <a href="<?= $linkreset?>"> Link Reset</a>
    Nomor pendaftaran anda : <?= $model->noPendaftaran?>

    Gunakan nomor pendaftaran tersebut untuk melakukan pembayaran melalui Bank BNI untuk memperoleh PIN.
    Tata Cara pembayaran silakan klik tautan berikut ini: <a href="http://pmb2017.dev/carabayarbni">Tata cara pembayaran</a>

    Setelah memperoleh PIN, langkah selanjutnya silahkan verifikasi PIN anda melalui http://pmbpasca.ipb.ac.id atau
    klik tautan berikut ini: <a href="<?= $link?>"> link verifikasi PIN</a>, lalu lengkapi data sesuai dengan tahapan yang tertera.

    Jika anda tidak merasa melakukan pendaftaran akun pada sistem kami, maka abaikan email ini.


            Hormat Kami,
            Panitia Penerimaan Mahasiswa Baru
            Sekolah Pascasarjana IPB
            http://pmbpasca.ipb.ac.id


    Email ini dikirimkan secara otomatis oleh sistem, kami tidak melakukan pengecakan email yang dikirimkan ke email ini.
    Jika ada pertanyaan, silahkan hubungi 0251-8628448
