<?php
/* @var $this yii\web\View */

$this->title = 'DOKUMENTASI API';
?>
<section id="introduction">
    <h2 class="page-header"><a href="#introduction">Introduction</a></h2>
    <p class="lead">
        <b>PMB API</b> API adalah sebuah bahasa dan format pesan yang digunakan oleh program aplikasi untuk
        berkomunikasi dengan system operasi atau program pengendalian lainnnya seperti system manajemen database (DBMS)
        atau komunikasi protocol.
    </p>
</section><!-- /#introduction -->


<!-- ============================================================= -->

<section id="getparameter">
    <h2 class="page-header"><a href="#getparameter">get Parameter</a></h2>
    <p class="lead">
        parameter get.
        <ol class="bring-up">
        <li>token : token izin mengambil data</li>
        <li>np : Nomor Pendaftaran</li>
    </ol>
    </p>
    output:
    <pre class="hierarchy bring-up">
        <code class="language-json" data-lang="bash">
    {
      "data":
        {
          "institusi":"Sekolah Pascasarjana IPB",
          "nama":"Nama Mahasiswa",
          "pin":"000000000000",
          "noPendaftaran":"17000001",
          "jumlahBayar":"750000"
        },
      "message":true
    }
        </code></pre>
</section>



