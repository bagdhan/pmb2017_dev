<?php 
// setlocale(LC_ALL, 'IND');
$ta = $tahun.'/'.($tahun+1);
// $tglSurat = strftime('%d %B %Y',strtotime($tanggal));
$BulanAsing = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$tglSurat = date("d", strtotime($tanggal)).' '.$BulanAsing[(int)date("m", strtotime($tanggal))-1].' '.date("Y", strtotime($tanggal));
  
// $sd = ($jenisKelamin == 1)? 'Sdr. ' : 'Sdri. ';
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
    <img src="<?php echo dirname(__FILE__) . '/../../arsip/img/';?>kop_ipb2.png" />
    <!-- <title><h2>Formulir A</h2></title> -->
    <!-- <barcode code="<?= $row['nopendaftaran'];?>" size="0.8" type="QR" error="M" class="barcode" /> -->
   
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
                
                    <tr>
                    
                      <td width="50" id="tdtxtnama" style="font-size: 14px;">Ref. No. </td>
                      <td width="7" colspan="-1" style="font-size: 14px;">:</td>
                      <td width="100" colspan="5" style="font-size: 14px;"><?= $nomor ?></td>
					  <!--<td align="right" style="font-size: 14px;">22 Mei 2018</td>!-->
                      <td align="right" style="font-size: 14px;"><?= $tglSurat ?> </td>
                    </tr>
                    <tr>
                     
                      <td valign="top" style="font-size: 14px;">Subject </td>
                      <td valign="top" colspan="-1" style="font-size: 14px;">:</td>
                      <td colspan="5" style="font-size: 14px;"><?= $perihal ?> <!--<br/>Tahun Akademik <?= $ta ?></td>!-->
                    </tr>
                 
    </table>

    <br/>
    <span>Dear</span><br/>
    <span><b><?= $nama?></b></span>
    <br/>
    <!--<table width="100%" border="0" align="left" cellpadding="0" cellspacing="2">
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
    <br/>!-->
    <span>On behalf of the Graduate School of IPB, we gratefully announce that you are accepted as Master Student on Study Program of 
	<?= $ps ?>, started from odd semester academic year <?= $ta ?>.<br/><br/>
	The classes will be started on <b>September, 4th 2018</b>. You are requested to register and verify the necessary documents on <b>August, 28th 2018,
	01.00 PM at Graha Widya Wisuda Building IPB, Kampus IPB Darmaga Bogor</b>.<br/><br/>
	Please kindly show the following documents at the verification processes:
    </span><br/><br/>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
	<thead>
      <tr>
        <td width="120" id="tdtxtnama" style="font-size: 14px;">1.	Prove of payment to <b>Rector IPB</b> including:</td>
		<td width="120" id="tdtxtnama" style="font-size: 14px;"><center><b>No</b></center></td>
        <td width="7" colspan="-1" style="font-size: 14px;"><center><b>Component of Cost</b></center></td>
        <td width="100" colspan="5" style="font-size: 14px;"><center><b>USD</b></center></td>
		<td width="100" colspan="5" style="font-size: 14px;"><center><b>Account No.</b></center></td>
		</thead>
 <tbody>
  <tr>
   <td align="center">1</td>
   <td>Registration Fee</td>
   <td align="center">100</td>
   <td rowspan="2" align="center">1330007992613 at Mandiri Bank
via ATM, <i>internet banking</i>, or <i>counter teller</i> at all branch of Mandiri Bank Indonesia:
</td>
  </tr>
  <tr>
   <td align="center">2</td>
   <td>Tuition Fee for Semester I</td>
   <td align="center">2.000</td>
  </tr>
  <tr>
   <td></td>
   <td>Total</td>
   <td align="center">2.100</td>
   <td></td>
  </tr>
      </tr></tbody></table>
      <tr>
        <td style="font-size: 14px;">2.	ID Card (<i>Resident Card/Driving License</i>)</td>
      </tr>
      <tr>
        <td style="font-size: 14px;">3.	Certificate of degree and academic transcript of Undergraduate Degree (original)</td>
      </tr>
	  <tr>
        <td style="font-size: 14px;">4.	Completing the following (please disobey if you have sent the documents):<br/>
		a. Study permit from the respective institution<br/>
		b. Three letters of recommendation<br/>
		c. Letter of statement on the financial resource for the study<br/>
		d. Three pieces of photograph (3x4)<br/>
		e. Health Certificate</td>
      </tr>
    </table>
    <br/>
    <span> 
    You are also requested to attend the stadium general on graduate education at IPB on <b>September, 4th 2017,
	13.00 PM,</b> held <b>at Graha Widya Wisuda Kampus IPB Darmaga Bogor</b>. Your opportunity as a new student
	of <b><?= $ps ?></b> will be canceled if you do not attend the registration and verification process as scheduled, or documents are invalid.
	</span>
     <!-- dan Saudara <strong>wajib</strong> mengikuti verifikasi sebagai mahasiswa baru sesuai jadwal yang ditentukan.</span> -->
    <br/>
    <br/><br/><br/>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
              
                    <tr>
                    
                      <td width="50" id="tdtxtnama"></td>
                      <td width="7" colspan="-1"></td>
                      <td width="100" colspan="5"></td>
                      <td align="justify" style="font-size: 14px;">Vice Rector for Education and Student Affairs,
                      <br/><br/><br/><br/><br/>
                      Dr. Ir. Drajat Martianto, M.Sc<br/>
                      NIP. 19640324 198903 1 004<br>
                      <img src="<?php echo dirname(__FILE__) . '/../../arsip/img/';?>ttdwrakk.png" style="position: absolute; margin: -4cm -5cm 0 -3cm"/></td>
                    </tr>
    </table><br/><br/><br/>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
      <tr>
        <td width="120" id="tdtxtnama" style="font-size: 14px;">Cc.</td>
        <td width="7" colspan="-1" style="font-size: 14px;">:</td>
        <td width="100" colspan="5" style="font-size: 14px;">1. Dean of Graduate School</td>
      </tr>
	  <tr>
        <td colspan="5" style="font-size: 14px;">2. Program Coordinator <?= $ps ?></td>
      </tr>
   
</div>

</body>

  

</html>
    
