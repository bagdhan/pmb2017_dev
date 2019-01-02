<?php 
// setlocale(LC_ALL, 'IND');
$ta = $tahun.'/'.($tahun+1);
// $tglSurat = strftime('%d %B %Y',strtotime($tanggal));
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
$tglSurat = date("d", strtotime($tanggal)).' '.$BulanIndo[(int)date("m", strtotime($tanggal))-1].' '.date("Y", strtotime($tanggal));
$sd = ($jenisKelamin == 1)? 'Sdr. ' : 'Sdri. ';

?>

<html>
<head>
<title><?php print "Surat-" . $nopendaftaran . " - " . $nama?></title>
<style>
body {
font-family:"Times New Roman", Georgia, Serif;
font-size:14px;
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
font-size:14px;
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
                    
                      <td width="50" id="tdtxtnama" style="font-size: 14px;">Nomor </td>
                      <td width="7" colspan="-1" style="font-size: 14px;">:</td>
                      <td width="100" colspan="5" style="font-size: 14px;"><?= $nomor ?></td>
                      <td align="right" style="font-size: 14px;"><?= $tglSurat ?> </td>
                    </tr>
                    <tr>
                     
                      <td valign="top" style="font-size: 14px;">Hal </td>
                      <td valign="top" colspan="-1" style="font-size: 14px;">:</td>
                      <td colspan="5" style="font-size: 14px;"><?= $perihal ?> <br/>Tahun Akademik <?= $ta ?></td>
                    </tr>
                 
    </table>
    <br/>
    <span>Kepada Yth.</span><br/>
    <span><?= $sd.$nama?></span>
    <br/>
    <table width="100%" border="0" align="left" cellpadding="0" cellspacing="2">
      <tr>
        <td width="500" style="font-size: 14px;"><?= $alamat ?></td>
        <td width="7" colspan="-1"></td>
        <td width="50" colspan="2"></td>
      </tr>
      <tr>
        <td width="400" style="font-size: 14px;"><?= $kelurahan ?></td>
        <td width="7" colspan="-1"></td>
        <td width="50" colspan="2"></td>
      </tr>
      <tr>
        <td style="font-size: 14px;"><?= $hp ?></td>
      </tr>
    </table>
    <br/>
    <span>Terima kasih kami sampaikan atas pilihan Saudara untuk melanjutkan pendidikan Pascasarjana di Institut Pertanian Bogor. 
    Berdasarkan hasil seleksi, dengan menyesal kami sampaikan bahwa Saudara <strong>tidak dapat diterima/DITOLAK</strong> pada program studi yang saudara pilih.<br/><br/>
    Demikian hal ini disampaikan. Atas perhatian Saudara kami ucapkan terima kasih.
    </span>
    <br/>
    <br/><br/><br/>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
              
                    <tr>
                    
                      <td width="50" id="tdtxtnama"></td>
                      <td width="7" colspan="-1"></td>
                      <td width="100" colspan="5"></td>
                      <td align="left" style="font-size: 14px;">Wakil Rektor Bidang Pendidikan 
                                      <br>dan Kemahasiswaan/Ketua PPMB IPB,
                      <br/><br/><br/><br/><br/>
                      Dr. Ir. Drajat Martianto, MS<br/>
                      NIP. 19640324 198903 1 004<br>
                      <img src="<?php echo dirname(__FILE__) . '/../../arsip/img/';?>ttdwrakk.png" style="position: absolute; margin: -4cm -5cm 0 -3cm"/></td>
                    </tr>
    </table>

</div>
</body>
</html>
    
