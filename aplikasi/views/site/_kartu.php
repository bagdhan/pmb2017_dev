<?php 

?>

<html>
<head>
<title><?php print "Kartu-" . $nopendaftaran . " - " . $nama?></title>
<style>
body {
font-family:Verdana, Arial, Helvetica, sans-serif;
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
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:14px;
text-align: justify;
}

.rounded {
 border:0.1mm solid #220044;

 border-radius: 5mm;
 background-clip: border-box;
 padding: 3em;
}

</style>
</head>
<body>
<div class="rounded">

    <!-- <title><h2>Formulir A</h2></title> -->
    <!-- <barcode code="<?= $row['nopendaftaran'];?>" size="0.8" type="QR" error="M" class="barcode" /> -->
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
                
                    <tr>
                    
                      <td style="text-align: left;font-size: 16pt"> <strong>KARTU VERIFIKASI</strong></td>
                      <td style="text-align: right;"><div style="text-align: right; font-family: Arial, Helvetica,
                sans-serif; font-weight: bold;font-size: 40pt; padding: 0.2em; vertical-align: middle; "><span><?= $kodefak ?> </span>  <barcode code="<?= $nopendaftaran ?>" size="0.8" type="QR" error="M" class="barcode" /></div></td>
                      
                    </tr>
                    
                 
    </table>
   
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
      <tr>
        <td width="120" valign="top" id="tdtxtnama">Nama </td>
        <td width="7" colspan="-1" valign="top">:</td>
        <td width="100" colspan="5"><?php print $nama ?></td>
      </tr>
      <tr>
        <td >No. Pendaftaran</td>
        <td width="7" colspan="-1">:</td>
        <td colspan="5"><?php print $nopendaftaran ?></td>
      </tr>
      <tr>
        <td >Program</td>
        <td width="7" colspan="-1">:</td>
        <td colspan="5"><?php print $strata ?></td>
      </tr>
      <tr>
        <td valign="top" >Program Studi</td>
        <td valign="top" width="7" colspan="-1">:</td>
        <td colspan="5"><?php print $ps ?></td>
      </tr>
      <tr>
        <td >Kode Bayar</td>
        <td width="7" colspan="-1">:</td>
        <td colspan="5"><?php print $kodebayar ?></td>
      </tr>
    </table>
  
</div>

</body>

  

</html>
    
