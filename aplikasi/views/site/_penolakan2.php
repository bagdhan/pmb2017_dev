<?php 



?>

<html>
<head>
<title><?php print "Surat-" . $nopendaftaran . " - " . $nama?></title>
<style>
body {
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:11px;
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
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:11px;
}
</style>
</head>
<body>
<div class="coba">
    <img src="<?php echo dirname(__FILE__) . '/../../web/img/';?>kop_ipb.png" />
    <!-- <title><h2>Formulir A</h2></title> -->
    <!-- <barcode code="<?= $row['nopendaftaran'];?>" size="0.8" type="QR" error="M" class="barcode" /> -->
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
                
                    <tr>
                    
                      <td width="50" id="tdtxtnama">Nomor </td>
                      <td width="7" colspan="-1">:</td>
                      <td width="100" colspan="5">8744/IT3/PP/2016</td>
                      <td align="right">10 Agustus 2016 </td>
                    </tr>
                    <tr>
                     
                      <td valign="top">Prihal. </td>
                      <td valign="top" colspan="-1">:</td>
                      <td colspan="5">Hasil Seleksi Calon Mahasiswa Baru <br/>Tahun Akademik  2016/2017</td>
                    </tr>
                 
    </table>
    <br/>
    <span>Kepada Yth.</span><br/>
    <span><strong><?php print $nama?></strong></span>
    <br/><br/>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
      <tr>
        <td width="300"><?= $alamat ?></td>
        <td width="7" colspan="-1"></td>
        <td width="50" colspan="2"></td>
      </tr>
    </table>
    <br/>    

    <span>Terima kasih kami sampaikan atas pilihan saudara untuk melanjutkan pendidikan Pascasarjana di IPB. Berdasarkan hasil seleksi yang kami lakukan, dengan menyesal kami sampaikan bahwa lamaran saudara <strong>belum dapat diterima tahun ini</strong> pada Program Studi yang saudara pilih. <br/><br/>Demikian hal ini disampaikan. Atas perhatian saudara kami ucapkan terima kasih.</span><br><br>

    <br/>
    <br/><br/>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
              
                    <tr>
                    
                      <td width="50" id="tdtxtnama"></td>
                      <td width="7" colspan="-1"></td>
                      <td width="100" colspan="5"></td>
                      <td align="left">Wakil Rektor Bidang Akademik <br>
                                      dan Kemahasiswaan / Ketua PPMB IPB
                      <br/><br/><br/><br/><br/>
                      Prof. Dr. Ir. Yonny Koesmaryono, MS<br/>
                      NIP. 19581228 198503 1 003<br>
                      <img src="<?php echo dirname(__FILE__) . '/../../web/img/';?>ttdwrak.png" style="position: absolute; margin: -4cm -5cm 0 -3cm"/></td>
                    </tr>
                    
                 
    </table>
<span>Tembusan:<br/>
      1. Yth. Dekan Sekolah Pascasarjana IPB<br/>
      2. Yth. Direktur Administrasi Pendidikan IPB<br/>

      </span>
</div>

</body>

  

</html>
    
