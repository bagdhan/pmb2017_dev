<?php 
//	include "../../config.php";
use app\components\Lang;

$this->title = Yii::t('app', '');
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
$BulanEng = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$HariIndo = array("Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu");
$HariEng = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

$dayStart['1']=$dateStart['1'] = $dayEnd['1']=$dateEnd['1'] = $dayStart['2']=$dateStart['2'] = $dayEnd['2']=$dateEnd['2'] = '';
$dayPleno1['1']=$datePleno1['1'] = $dayPleno1['2']=$datePleno1['2'] = $dayPleno2['1']=$datePleno2['1'] = $dayPleno2['2']=$datePleno2['2'] = '';
$i = 1; foreach($model as $value){
  ;
  $dayStart[$i] = Lang::id()? $HariIndo[(int)date("N", strtotime($value->dateStart))-1]:$HariEng[(int)date("N", strtotime($value->dateStart))-1];
  $dateStart[$i] = Lang::id()? date("d", strtotime($value->dateStart)).' '.$BulanIndo[(int)date("m", strtotime($value->dateStart))-1].' '.date("Y H:i:s", strtotime($value->dateStart)) : $BulanEng[(int)date("m", strtotime($value->dateStart))-1].' '.date("d", strtotime($value->dateStart)).', '.date("Y H:i:s", strtotime($value->dateStart));
  
  $dayEnd[$i] = Lang::id()? $HariIndo[(int)date("N", strtotime($value->dateEnd))-1] : $HariEng[(int)date("N", strtotime($value->dateEnd))-1];
  $dateEnd[$i] = Lang::id()? date("d", strtotime($value->dateEnd)).' '.$BulanIndo[(int)date("m", strtotime($value->dateEnd))-1].' '.date("Y H:i:s", strtotime($value->dateEnd)) : $BulanEng[(int)date("m", strtotime($value->dateEnd))-1].' '.date("d", strtotime($value->dateEnd)).', '.date("Y H:i:s", strtotime($value->dateEnd));

  $sidang = $value->sidangs;
  foreach($sidang as $sidang){
    $pleno = $sidang->jenisSidang_id;
    $day = Lang::id()? $HariIndo[(int)date("N", strtotime($sidang->tanggalSidang))-1] : $HariEng[(int)date("N", strtotime($sidang->tanggalSidang))-1];
    $date = Lang::id()? date("d", strtotime($sidang->tanggalSidang)).' '.$BulanIndo[(int)date("m", strtotime($sidang->tanggalSidang))-1].' '.date("Y", strtotime($sidang->tanggalSidang)) : $BulanEng[(int)date("m", strtotime($sidang->tanggalSidang))-1].' '.date("d", strtotime($sidang->tanggalSidang)).', '.date("Y", strtotime($sidang->tanggalSidang));

    if($pleno == 1){
      $dayPleno1[$i] = $day;
      $datePleno1[$i] = $date;
    }elseif($pleno == 2){
      $dayPleno2[$i] = $day;
      $datePleno2[$i] = $date;
    }
  }

  $i++;
}
$this->params['sidebar'] = 0;
$this->title = Lang::id()? $post->title : $post->title_en;
?>

  <div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title"><?= Lang::id()? $post->subtitle : $post->subtitle_en;?></h3>
  </div>
  <div class="box-body">


<table width="791" cellpadding="0" cellspacing="0">
  <col width="119" />
  <col width="14" />
  <col width="307" />
      <tr height="41">
    <td height="19"><h2><strong><p style="font-family:Cambria;"><?= Lang::t('TAHAP I','PERIOD I')?></p></strong></h2></td>
    <td></td>
    <td width="586"></td>
  </tr>
  <tr height="41">  
    <td width="19" bgcolor="#EAEAEA"><strong>1. <?= Lang::t('Periode Pendaftaran','Registration Period')?></strong></td>
    <td width="10" bgcolor="#EAEAEA">:&nbsp;</td>
    <td width="586" bgcolor="#EAEAEA"><?= $dayStart['1'].", ".$dateStart['1'].
        " WIB ".Lang::t("s.d.","-")." <br/>".$dayEnd['1'].", ".$dateEnd['1']." WIB" ?></td>
  </tr>
  <tr height="41">  
    <td width="19" bgcolor="#EAEAEA"><strong>2. <?= Lang::t('Seleksi Calon Pelamar','Selection')?></strong></td>
    <td width="10" bgcolor="#EAEAEA">:&nbsp;</td>
    <td width="586" bgcolor="#EAEAEA"><?= $dayPleno1['1'].", ".$datePleno1['1'].
        " ".Lang::t("s.d.","-")." ".$dayPleno2['1'].", ".$datePleno2['1']?></td>
  </tr>
  <tr height="41"> 
    <td width="586" bgcolor="#EAEAEA"><strong>3. <?= Lang::t('Pengumuman Penerimaan','Announcement')?></strong></td>
    <td width="10" bgcolor="#EAEAEA">:&nbsp;</td>
    <td width="586" bgcolor="#EAEAEA"><?= Lang::t('25 Mei - 12 Juni 2018','May 25 - June 12, 2018')?></td>
  </tr>
  
  <td height="19"><h2><strong><p style="font-family:Cambria;"><?= Lang::t('TAHAP II','PERIOD II')?></p></strong></h2></td>
    <td></td>
    <td width="586"></td>
  </tr>
  <tr height="41">  
    <td width="19" bgcolor="#EAEAEA"><strong>1. <?= Lang::t('Periode Pendaftaran','Registration Period')?></strong></td>
    <td width="10" bgcolor="#EAEAEA">:&nbsp;</td>
    <td width="586" bgcolor="#EAEAEA"><?= $dayStart['2'].", ".$dateStart['2'].
        " WIB ".Lang::t("s.d.","-")." <br/>".$dayEnd['2'].", ".$dateEnd['2']." WIB" ?></td>
    <!-- <td width="586" bgcolor="#EAEAEA"><?php echo $dayStart['2'].", ".$dateStart['2'].
        " WIB s.d. <br/>"
        .$dayEnd['2'].", ".$dateEnd['2']." WIB" 
        ."Sabtu, 15 Juli 2017 00:00:00 WIB"
        ?></td> -->
  </tr>
  <tr height="41">  
    <td width="19" bgcolor="#EAEAEA"><strong>2. <?= Lang::t('Seleksi Calon Pelamar','Selection')?></strong></td>
    <td width="10" bgcolor="#EAEAEA">:&nbsp;</td>
    <td width="586" bgcolor="#EAEAEA"><?= $dayPleno1['2'].", ".$datePleno1['2'].
        " ".Lang::t("s.d.","-")." ".$dayPleno2['2'].", ".$datePleno2['2']?></td>
  </tr>
  <tr height="41"> 
    <td width="586" bgcolor="#EAEAEA"><strong>3. <?= Lang::t('Pengumuman Penerimaan','Announcement')?></strong></td>
    <td width="10" bgcolor="#EAEAEA">:&nbsp;</td>
    <td width="586" bgcolor="#EAEAEA"><?= Lang::t('06 Agustus - 09 Agustus 2018','August 06 - August 09, 2018')?></td>
  </tr>
  <td height="19"><h2><strong><p style="font-family:Cambria;"><?= Lang::t('Verifikasi & Kuliah Umum Perdana','Verification &amp; Studium generale')?></p></strong></h2></td>
    <td></td>
    <td width="586"></td>
  </tr>
  <tr height="41">  
    <td width="19" bgcolor="#EAEAEA"><strong>1. <?= Lang::t('Verifikasi Berkas (Asli)','Verification (Original Documents)')?></strong></td>
    <td width="10" bgcolor="#EAEAEA">:&nbsp;</td>
    <td width="586" bgcolor="#EAEAEA"><?= Lang::t('28 Agustus - 29 Agustus 2018','August 28 - August 29, 2018')?></td>
  </tr>
  <tr height="41">  
    <td width="19" bgcolor="#EAEAEA"><strong>2. <?= Lang::t('<em>Placement Test</em> Bahasa Inggris','English Placement Test')?></strong></td>
    <td width="10" bgcolor="#EAEAEA">:&nbsp;</td>
    <td width="586" bgcolor="#EAEAEA"><?= Lang::t('30 Agustus - 31 Agustus 2018','August 30 - August 31, 2018')?></td>
  </tr>
  <tr height="41"> 
    <td width="586" bgcolor="#EAEAEA"><strong>3. <?= Lang::t('Kuliah Umum Perdana','Studium Generale')?></strong></td>
    <td width="10" bgcolor="#EAEAEA">:&nbsp;</td>
    <td width="586" bgcolor="#EAEAEA"><?= Lang::t('03 September 2018','September 03, 2018')?></td>
  </tr>
 
</table>
</br>
<i>*)<?= Lang::t('Jika ada perubahan akan diberitahukan lebih lanjut','Any possible changes will be notified further')?></i>

    <?= Lang::id()? $post->content : $post->content_en;?>
  </div><!-- /.box-body -->
</div><!-- /.box -->

