<?php

/* @var $this yii\web\View */
/* @var $model \app\modules\pendaftaran\models\Pendaftaran */
//$username = Yii::$app->user->identity->username;

$linkreset = Yii::$app->urlManager->createAbsoluteUrl(['/site/request-password-reset']);

?>


Yth Bapak/Ibu pemilik email <?= $email?>

    Sebagai tindak lanjut Penerimaan Mahasiswa Baru (PMB) SPs IPB, pada tanggal <?= date('d-M-Y')?> SPs IPB telah membuatkan akun Bapak/Ibu untuk mengakses sistem seleksi online PMB, dengan user akses:

    Username   : <?= $username ?>
    Password    : <a href="<?= $linkreset?>"> Link Reset</a>
    Level Akses : <?= $jabatan ?>

    User akses ini dipergunakan oleh <?= $jabatan.' '.$level ?> untuk tahap seleksi calon mahasiswa baru pada laman http://pmbpasca.ipb.ac.id (pada jadwal yang telah disepakati pada Rapat Pleno 1)



            Hormat Kami,
            Panitia Penerimaan Mahasiswa Baru
            Sekolah Pascasarjana IPB
            http://pmbpasca.ipb.ac.id


    Email ini dikirimkan secara otomatis oleh sistem, kami tidak melakukan pengecakan email yang dikirimkan ke email ini.
    Jika ada pertanyaan, silahkan hubungi 0251-8628448
