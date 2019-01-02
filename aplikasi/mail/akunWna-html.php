<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\pendaftaran\models\Pendaftaran */
//$username = Yii::$app->user->identity->username;
$link = Yii::$app->urlManager->createAbsoluteUrl(['/pendaftaran/verifikasi/pin/']);
$linkreset = Yii::$app->urlManager->createAbsoluteUrl(['/site/request-password-reset']);

?>
<?php if (\app\components\Lang::id()) {?>
<div class="password-reset">

    <h2>Yth Bapak/Ibu pemilik email <?= $email?></h2>

    <p>Pada tanggal <?= date('d-M-Y') ?> sistem kami menerima permintaan pendaftaran akun untuk Anda.</p>
    <p>
        Nama : <?= $model->orang->nama ?><br>
        Nama Akun : <?= $username ?><br>
        Password : <a href="<?= $linkreset ?>"> Link Reset</a><br>
        Nomor pendaftaran anda : <?= $model->noPendaftaran ?><br>
        <br>
        Gunakan nomor pendaftaran tersebut untuk melakukan pembayaran melalui Bank BNI untuk memperoleh PIN.
        Tata Cara pembayaran silakan klik tautan berikut ini: <a href="http://pmb2017.dev/carabayarbni">Tata cara pembayaran</a>
        <br>
        <br>
        Setelah memperoleh PIN, langkah selanjutnya silahkan verifikasi PIN anda melalui http://pmbpasca.ipb.ac.id atau
        klik tautan berikut ini: <a href="<?= $link ?>"> link verifikasi PIN</a>, lalu lengkapi data sesuai dengan
        tahapan yang tertera.
    </p>
    <p>
        Jika anda tidak merasa melakukan pendaftaran akun pada sistem kami, maka abaikan email ini.
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

<?php } else {?>

    <div class="password-reset">

        <h2>Yth Bapak/Ibu pemilik email <?= $email?></h2>

        <p>Pada tanggal <?= date('d-M-Y') ?> sistem kami menerima permintaan pendaftaran akun untuk Anda.</p>
        <p>
            Nama : <?= $model->orang->nama ?><br>
            Nama Akun : <?= $username ?><br>
            Password : <a href="<?= $linkreset ?>"> Link Reset</a><br>
            Nomor pendaftaran anda : <?= $model->noPendaftaran ?><br>
            <br>
            Gunakan nomor pendaftaran tersebut untuk melakukan pembayaran melalui Bank BNI untuk memperoleh PIN.
            Tata Cara pembayaran silakan klik tautan berikut ini: <a href="http://pmb2017.dev/carabayarbni">Tata cara pembayaran</a>
            <br>
            <br>
            Setelah memperoleh PIN, langkah selanjutnya silahkan verifikasi PIN anda melalui http://pmbpasca.ipb.ac.id atau
            klik tautan berikut ini: <a href="<?= $link ?>"> link verifikasi PIN</a>, lalu lengkapi data sesuai dengan
            tahapan yang tertera.
        </p>
        <p>
            Jika anda tidak merasa melakukan pendaftaran akun pada sistem kami, maka abaikan email ini.
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
<?php }?>
