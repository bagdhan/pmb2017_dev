<?php 
$ta = $tahun.'/'.($tahun+1);
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
$tglSurat = date("d", strtotime($tanggal)).' '.$BulanIndo[(int)date("m", strtotime($tanggal))-1].' '.date("Y", strtotime($tanggal));
  
$sd = ($jenisKelamin == 1)? 'Sdr. ' : 'Sdri. ';

if($strata=='Doktor')
    $biaya = '12.000.000';
  else
    $biaya = '9.000.000';

$awal = $kodefak;

?>

<html>
<head>
<title><?php print "Surat-verifikasi-" . $nopendaftaran . " - " . $nama?></title>
<style>
body {
font-family:"Times New Roman", Georgia, Serif;
font-size:10px;
}
span {
  text-align: justify;
  text-justify: inter-word;
}
div {
  text-align: justify;
  text-justify: inter-word;
}
table {
font-family:"Times New Roman", Georgia, Serif;
text-align: justify;
font-size:10px;
}
</style>
</head>
<body>
<div class="coba">
    <img src="<?php echo dirname(__FILE__) . '/../../arsip/img/';?>kop_ipb.png" />
    <!-- <title><h2>Formulir A</h2></title> -->
    <!-- <barcode code="<?= $row['nopendaftaran'];?>" size="0.8" type="QR" error="M" class="barcode" /> -->
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
                
                    <tr>
                    
                      <td width="50" id="tdtxtnama">Nomor </td>
                      <td width="7" colspan="-1">:</td>
                      <td width="100" colspan="5"><?= $nomor ?></td>
                      <td align="right"><?= $tglSurat ?></td>
                    </tr>
                    <tr>
                     
                      <td valign="top">Hal </td>
                      <td valign="top" colspan="-1">:</td>
                      <td colspan="5"><?= $perihal?> Tahun Akademik  <?= $ta ?></td>
                    </tr>
                 
    </table>
    <br/>
    <span>Kepada Yth.</span><br/>
    <span><strong><?= $sd.$nama?></strong></span>
    <br/>
    <table width="100%" border="0" align="left" cellpadding="0" cellspacing="2">
      <tr>
        <td width="500"><?= $alamat ?></td>
        <td width="7" colspan="-1"></td>
        <td width="50" colspan="2"></td>
      </tr>
      <tr>
        <td width="400"><?= $kelurahan ?></td>
        <td width="7" colspan="-1"></td>
        <td width="50" colspan="2"></td>
      </tr>
      <tr>
        <td><?= $hp ?></td>
      </tr>
    </table>
    <br/>
    <span>Selamat dan sukses kami sampaikan kepada Saudara yang berdasarkan keputusan Panitia Penerimaan Mahasiswa Baru Program Pascasarjana IPB Tahun Akademik <?= $ta ?> dinyatakan diterima sebagai calon mahasiswa pada:
    </span><br/><br/>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
      <tr>
        <td width="110" id="tdtxtnama">Program</td>
        <td width="7" colspan="-1">:</td>
        <td width="100" colspan="5"><?php print $strata ?></td>
      </tr>
      <tr>
        <td >Program Studi</td>
        <td width="7" colspan="-1">:</td>
        <td colspan="5"><?php print $ps ?></td>
      </tr>
      <tr>
        <td >Status</td>
        <td width="7" colspan="-1">:</td>
        <td colspan="5"><?php print $status ?></td>
      </tr>
    </table>
    <br/>
    <span>Semoga Saudara dapat mengikuti studi dengan baik sehingga dapat menyelesaikan pendidikan dengan tepat waktu. Untuk dapat diterima secara resmi sebagai mahasiswa SPs-IPB mulai Tahun Akademik <?= $ta ?>, Saudara wajib mengikuti beberapa hal sebagai berikut:<br/><br/>
    <strong>A. Verifikasi</strong><br>
    Saudara <strong>WAJIB</strong> hadir untuk melakukan verifikasi pada Tanggal <strong>27 atau 28 Agustus 2018, Pukul 09.00 WIB</strong> (sesuai jadwal yang dapat dilihat pada laman <strong>http://pasca.ipb.ac.id</strong>) di Common Class Room (CCR), Kampus IPB Darmaga Bogor, dengan membawa berkas/dokumen sebagai berikut:</span><br>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
      <tr>
        <td width="15" valign="top">1. </td>
        <td width="100" colspan="5">Tanda bukti pembayaran Biaya Pendidikan sebesar <strong>Rp. <?= $biaya;?></strong> atas nama Saudara, dengan nomor tagihan <strong> <?= $kodebayar?></strong> sebagai referensi untuk melakukan pembayaran melalui bank mitra IPB. Tata cara pembayaran biaya pendidikan dapat diakses di laman <strong>http://spp.ipb.ac.id</strong>. Pembayaran biaya pendidikan dapat dilakukan mulai tanggal 23 - 28 Agustus 2018.</td>
      </tr>
      <tr>
        <td valign="top">2. </td>
        <td colspan="5">Keterangan Bukti Diri (KTP/SIM) Asli</td>
      </tr>
      <tr>
        <td valign="top">3.</td>
        <td colspan="5">Ijazah <strong>Asli</strong> Strata Pendidikan Sebelumnya</td>
      </tr>
      <tr>
        <td valign="top">4. </td>
        <td colspan="5">Transkrip Akademik <strong>Asli</strong> Strata Pendidikan Sebelumnya</td>
      </tr>
      <tr>
        <td valign="top">5.</td>
        <td colspan="5">Melengkapi hal-hal sebagai berikut (jika belum mengumpulkan):<br/>
                        <?php if($strata!= 'Doktor'){?>
                        -  Surat Rekomendasi dari 3 (tiga) orang bergelar Magister atau Doktor<br/>
                        <?php }else{?>
                        -  Surat Rekomendasi dari 3 (tiga) orang yang bergelar Doktor<br/>
                        <?php } ?>
                        -  Surat ijin belajar dari atasan bagi yang sudah bekerja<br/>
                        -  Surat keabsahan dokumen (Form E) dan surat jaminan biaya (Form D) bermaterai Rp. 6.000<br/>
                        -  Surat keterangan kesehatan yang mencantumkan juga hasil pemeriksaan fisik (tensi, nadi, respirasi) dan pemeriksaan laboratorium (urine: protein, ph, glukosa)<br/>
                        -  Membawa fotokopi Kartu Asuransi Kesehatan (BPJS/Askes/dll).</td>
      </tr>
    </table>
    <span><br/>
    Kami ingatkan juga bahwa hak Saudara menjadi mahasiswa baru akan tergantung pada hasil verifikasi dokumen lamaran. Status penerimaan Saudara dapat dibatalkan apabila tidak dapat menunjukkan dokumen asli yang dipersyaratkan atau terdapat perbedaan data antara dokumen asli dengan dokumen/informasi yang Saudara kirimkan saat pendaftaran.
    <br/><br/><strong>B. <i>Placement Test </i>Bahasa Inggris</strong><br/>
    Semua mahasiswa diwajibkan mengikuti <i>placement test</i> Bahasa Inggris yang dilaksanakan di IPB. Tanggal dan tempat pelaksanaan tes akan ditetapkan pada saat verifikasi.</span><br/><br/>

    <span><strong>C. Kuliah dan Penjelasan Umum</strong><br/>
    Agar dapat memahami filosofi pendidikan pascasarjana dan sistem pendidikan di SPs-IPB, Saudara diwajibkan hadir pada kuliah dan penjelasan umum yang akan diselenggarakan pada tanggal <strong>30 Agustus 2018, Pukul 08.00 WIB</strong> di Graha Widya Wisuda (GWW), Kampus IPB Darmaga Bogor. Kuliah semester I (Ganjil) Tahun Akademik 2018/2019 akan dimulai pada tanggal <strong>3 September 2018</strong>.</span><br/><br/>

    <span>Informasi ini juga dapat diakses melalui <strong>http://pmbpasca.ipb.ac.id</strong> dengan menggunakan <i>username</i> saudara dan disajikan pada laman <strong>http://pasca.ipb.ac.id</strong>. Biaya Pendidikan yang telah dibayarkan <strong>tidak dapat ditarik kembali</strong> dengan alasan apapun. <br/><br/>Demikian agar diketahui dan dilaksanakan sebagaimana mestinya. Atas perhatian Saudara, kami ucapkan terima kasih.</span>
    <br/><br/><br/>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">          
                    <tr>
                    
                      <td width="50" id="tdtxtnama"></td>
                      <td width="7" colspan="-1"></td>
                      <td width="100" colspan="5"></td>
                      <td align="left" style="font-size: 10px;">A.n. Rektor
					  <br>Wakil Rektor Bidang Pendidikan dan
                                       <br>Kemahasiswaan/Ketua PPMB IPB,
                      <br/><br/><br/><br/><br/>
                      Dr. Ir. Drajat Martianto, MS<br/>
                      NIP. 19640324 198903 1 004<br>
                      <img src="<?php echo dirname(__FILE__) . '/../../arsip/img/';?>ttdwrakkk.png" style="position: absolute; margin: -4cm -5cm 0 -3cm"/></td>
                    </tr>
    </table>

<span>Tembusan:<br/>
<?php if($awal == 'P'){?>
      1. Yth. Dekan Sekolah Pascasarjana IPB<br/>
      2. Yth. Direktur Administrasi Pendidikan dan Penerimaan Mahasiswa Baru IPB<br/>
      3. Yth. Ketua Program Studi <?=$ps?><br/>
      <?php }else{?>
      1. Yth. Dekan <?=$fakultas?><br/>
      2. Yth. Dekan Sekolah Pascasarjana IPB<br/>
      3. Yth. Direktur Administrasi Pendidikan dan Penerimaan Mahasiswa Baru IPB<br/>
      4. Yth. Ketua Program Studi <?=$ps?>
      <?php } ?>
      </span>
</div>

</body>

  

</html>
    
