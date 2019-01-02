<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\pendaftaran\models\Pendaftaran */
//$username = Yii::$app->user->identity->username;
$linkreset = Yii::$app->urlManager->createAbsoluteUrl(['/site/request-password-reset']);

?>
<div class="password-reset">

    <h2>Yth Bapak/Ibu pemilik email <?= $email?></h2>

    <p>Sebagai tindak lanjut Penerimaan Mahasiswa Baru (PMB) SPs IPB, pada tanggal <?= date('d-M-Y') ?> SPs IPB telah membuatkan akun Bapak/Ibu untuk mengakses sistem seleksi online PMB, dengan user akses:</p>
    <p>

        Username : <?= $username ?><br>
        Password : <a href="<?= $linkreset ?>"> Link Reset</a><br>
        Level Akses : <?= $jabatan ?><br>
        <br>
        User akses ini dipergunakan oleh <strong><?= $jabatan.' '.$level ?></strong> untuk tahap seleksi calon mahasiswa baru pada laman http://pmbpasca.ipb.ac.id (pada jadwal yang telah disepakati pada Rapat Pleno 1)
        <br>
        
    </p>

    <p>
    Hormat Kami,<br>
    Panitia Penerimaan Mahasiswa Baru <br>
    Sekolah Pascasarjana IPB <br>
    http://pmbpasca.ipb.ac.id <br>
    </p>

    <p>
    Email ini dikirimkan secara otomatis oleh sistem, kami tidak melakukan pengecakan email yang dikirimkan ke email
    ini.
    </p>
    <p>
    Jika ada pertanyaan, silahkan hubungi 0251-8628448
    </p>
</div>