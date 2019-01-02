<?php 


$nopendaftaran = $data->noPendaftaran;

$doc = array();
foreach ($document as $d2) {
        // $docid = $d2['docid'];
        if($d2['pendaftaran_noPendaftaran'] == $nopendaftaran){
                $docid = $d2->jenisBerkas->kode;
                $doc[$d2['pendaftaran_noPendaftaran']][$docid]['file']=$d2['file'];
                $doc[$d2['pendaftaran_noPendaftaran']][$docid]['cetak']=$d2['verifikasi'];
            }
    }
$formc = ($doc[$nopendaftaran]['formC1']['cetak']==1? 1 : 0) + ($doc[$nopendaftaran]['formC2']['cetak']==1? 1 : 0) + ($doc[$nopendaftaran]['formC3']['cetak']==1? 1 : 0);

$cek = $data->prodike(1); 
$prodi1 = null;
$kodefak = '';
if($cek){
  $cek = $data->prodike(1)->programStudi; 
  $strata = "S".$cek->strata;
  $dept = $cek->departemen->nama;
  $prodi1 = $cek->kode;
  $prodi1nama = $cek->nama;
  $kodefak = substr($cek->kode, 0, 1);
}

$cek2 = $data->prodike(2); 
$prodi2 = null;
if($cek2){
  $cek2 = $data->prodike(2)->programStudi; 
  $prodi2nama = $cek2->nama;
}

$pendidikan = []; $i = 1; foreach($data->orang->pendidikans as $value){

        $pendidikan[$i] = $value;
        $i++;
    }

$syarat = []; foreach($data->syaratTambahans as $value){
          $syarat[$value->jenisSyaratTambahan->title]=$value;
        }

?>

<html>
<head>
<title><?php print "Formulir A: " . $nopendaftaran . " - " . $data->orang->nama ?></title>
<style>
body {
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:12px;
}
table {
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:14px;
font-weight: bold;  
}
</style>
</head>
<body>
<div class="coba">
    <table width="100%" border="1" align="center" cellpadding="0" cellspacing="2">
        <tr style="margin:0; width:100%; border-bottom-style: solid;">
            <td width="20%" style="text-align: center;"><h2>Dept.<?= " ".$prodi1?></h2></td>
            <td width="60%" style="text-align: center;"><span style="font-size: 14px;"><strong>KELENGKAPAN BERKAS PELAMAR (COPY)<br/>
                SEKOLAH PASCASARJANA INSTITUT PERTANIAN BOGOR<br/>
                SEMESTER GANJIL TAHUN AJARAN 2015/2016</span></strong></td>
            <td width="20%" style="text-align: center;"><h1><?php echo $kodefak;?></h1></td>
        </tr>
    </table>
    <br/>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">

                    <tr>
           
                      <td width="200" id="tdtxtnama">Tanggal Masuk </td>
                      <td width="7" colspan="-1">:</td>
                      <td width="100" colspan="5"><?= date('d M Y');?></td>
                    </tr>
                    <tr>
    
                      <td >No. Berkas - No.Daftar </td>
                      <td colspan="-1">:</td>
                      <td colspan="5"><?php echo " ________ " . " - " . $nopendaftaran?></td>
                    </tr>
                    <tr>
        
                      <td >Nama</td>
                      <td width="7" colspan="-1">:</td>
                      <td colspan="5"><?php print $data->orang->nama?></td>
                    </tr>
                    <tr>
         
                      <td >Program / Rencana Biaya</td>
                      <td width="7" colspan="-1">:</td>
                      <td colspan="5"><?= $strata." / ".$data->rencanaPembiayaan->jenisPembiayan->title ?></td>
                    </tr>
                    <tr>
  
                      <td >Pilihan PS 1 </td>
                      <td width="7" colspan="-1">:</td>
                      <td colspan="5"><?= $prodi1nama ?></td>
                    </tr>
                    <tr>
         
                      <td >Pilihan PS 2</td>
                      <td width="7" colspan="-1">:</td>
                      <td colspan="5"><?= $prodi2nama ?></td>
                    </tr>
              </table>
                  <br />
                  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
                    <tr>
   
                      <td height="30" width="30" >Form A Cetak Online</td>
                      <td width="15" align="center">V</td>
                      <td width="30"> * Form G</td>
                      <td width="15" align="center"></td>
                      <td width="50">*** Ijazah S1, S2</td>
                      <td width="15" align="center"><?= $doc[$nopendaftaran]['s1ijazah']['cetak']==1?" V ":" - ";?> / <?= $doc[$nopendaftaran]['s2ijazah']['cetak']==1?" V ":" - ";?></td>
                    </tr>
                    <tr>
                      <td height="30" >Form B (S3)</td>
                      <td align="center"><?= $doc[$nopendaftaran]['formB']['cetak']==1?" V ":" - ";?></td>
                      <td >** Form H</td>
                      <td align="center"></td>
                      <td>*** Transkrip S1, S2</td>
                      <td align="center"><?= $doc[$nopendaftaran]['s1transkrip']['cetak']==1?" V ":" - ";?> / <?= $doc[$nopendaftaran]['s2transkrip']['cetak']==1?" V ":" - ";?></td>
                    </tr>
                    <tr>
                      <td height="30" >Form C (jumlah)</td>
                      <td align="center"><?= $formc;?></td>
                      <td>** Form I</td>
                      <td align="center"></td>
                      <td>*** IPK S1, S2</td>
                      <td align="center"><?= $pendidikan[1]->ipk . " / " . $pendidikan[1]->ipk ?></td>
                    </tr>
                    <tr>
                      <td height="30" >Form D </td>
                      <td align="center"><?= $doc[$nopendaftaran]['formD']['cetak']==1?" V ":" - ";?></td>
                      <td>Sinopsis</td>
                      <td align="center"><?= $doc[$nopendaftaran]['sinopsis']['cetak']==1?" V ":" - ";?></td>
                      <td>TOEFL</td>
                      <td align="center"><?= $syarat['TOEFL']->score ?></td>
                    </tr>
                    <tr>
                      <td height="30" >Form E</td>
                      <td align="center"></td>
                      <td>Bukti Bayar</td>
                      <td align="center"><?= $doc[$nopendaftaran]['buktibayar']['cetak']==1?" V ":" - ";?></td>
                      <td>TPA</td>
                      <td align="center"><?= $syarat["TPA"]->score ?></td>
                    </tr>
                    <tr>
                      <td height="30" >Form F</td>
                      <td align="center"></td>
                      <td colspan="4">Kekurangan : </td>
                    </tr>
                    <tr>
                      <td colspan="3" height="30" ><span style="font-size: 12px;">* Khusus BPPDN<br/>** Khusus Pendaftaran Jalur Penelitian<br/>*** Pendidikan Sebelumnya </span></td>
                      <td colspan="3"><span style="font-size: 12px;">Tanggal Penyerahan Kekurangan : </span></td>
                    </tr>
                </table>
                <br />
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
                  <tr>
                    <td style="text-align: right;"><div style="text-align: right;" ><barcode code="<?= $nopendaftaran ?>" size="0.7" type="QR" error="M" class="barcode" /></div></td>
                  </tr>
                </table>
    

</div>
</body>
</html>
    
