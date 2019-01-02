<?php

/* @var $this yii\web\View */


use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Pengumuman Penerimaan';
$this->params['breadcrumbs'][] = $this->title;

$ta = $tahun.'/'.($tahun+1);
// print_r($paket . $pleno);die();
?>

    <div class="row">
        <div class="col-lg-12">
           

            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><p style="font-size: 20px">Pengumuman Hasil Seleksi Penerimaan Mahasiswa Baru (PMB) Sekolah Pascasarjana IPB</p></h3>
                  </div>
                <div class="box-body">
                    <?php if($pleno == 'Kelas Khusus'){?>
                    
                    <p style="font-family:Cambria; font-size:16px;">Informasi pengumumahan hasil seleksi bisa menghubungi panitia PMB Sekolah Pascasarjana IPB.
                    </p><br />

                    <?php } else if($hasil == 4 || $hasil == 5 || $menunda == 1){?>

                    <p style="font-family:Cambria; font-size:16px;"><strong style="font-size: 18px;">Selamat dan sukses kami sampaikan kepada <br/> <?= $nama;?></strong> <br/><br/> Berdasarkan keputusan Panitia PMB Sekolah Pascasarjana IPB Tahun Akademik <?= $ta ?> dinyatakan <strong>DITERIMA</strong> sebagai calon mahasiswa pada:<br />
                      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
                          <tr>
                            <td width="120" id="tdtxtnama" style="font-size: 16px;">Strata</td>
                            <td width="7" colspan="-1" style="font-size: 16px;">:</td>
                            <td width="100" colspan="5" style="font-size: 16px;"><?= $strata ?></td>
                          </tr>
                          <tr>
                            <td style="font-size: 16px;">Program Studi</td>
                            <td width="7" colspan="-1" style="font-size: 16px;">:</td>
                            <td colspan="5" style="font-size: 16px;"><?= $ps ?></td>
                          </tr>
                          <tr>
                            <td style="font-size: 16px;">Status</td>
                            <td width="7" colspan="-1" style="font-size: 16x;">:</td>
                            <td colspan="5" style="font-size: 16px;"><?= $status ?></td>
                          </tr>
                        </table>

                      <!-- Strata       <span style="padding:40px;">: <?= $strata;?></span><br />
                      Program Studi <span style="padding:1px;"> : <?= $ps;?></span><br />
                      Status            <span style="padding:58px;">: <?= $status;?></span> -->
                      <br />
                      Surat penerimaan dan informasi lebih lengkap dapat dilihat dengan mengunduh dokumen dibawah ini.<br/>
                      <input type="submit" value="Surat Hasil Seleksi" onClick="cetak_surat()" class="savebutton">

                      <?php if(date('Y-m-d H:i:s') >= '2017-08-14 08:00:00' && $pleno != 'Kelas Khusus'){?>
                      <br /><br />
                      Surat panggilan verifikasi dapat dilihat dengan mengunduh dokumen dibawah ini.<br/>
                      <input type="submit" value="Surat Panggilan Verifikasi" onClick="cetak_surat_verifikasi()" class="savebutton">
                      <?php } ?>

                      <br /><br />
                      Kartu Verifikasi*) dapat diunduh dibawah ini.<br/>
                      <input type="submit" value="Cetak Kartu" onClick="cetak_kartu()" class="savebutton"><br /><br />
                      <strong> *) Kartu harap dicetak dan dibawa pada saat verifikasi.</strong>

                      <?php if($setuju == 0 || $berkas == 0){?>
                      <br />
                      <br />
                      <strong>Catatan: </strong><br/>Harap segera melengakapi data dan menyatakan data lengkap serta mengunggah berkas yang disyaratkan!</p>
                      <?php } ?>
                  </p><br />

                  <?php } else if($hasil == 6 || $pleno == 2){ ?>

                  

                  <p style="font-family:Cambria; font-size:16px;"><strong style="font-size: 18px;">Permohonan maaf kami sampaikan kepada<br/> <?= $nama;?></strong> <br/><br/> Terima kasih kami sampaikan atas pilihan Saudara untuk melanjutkan pendidikan Pascasarjana di Institut Pertanian Bogor. Berdasarkan hasil seleksi yang kami lakukan dengan menyesal kami sampaikan bahwa lamaran Saudara diyatakan <strong>DITOLAK (TIDAK DITERIMA)</strong> pada Program Studi yang Saudara pilih.<br /><br />

                      Surat hasil seleksi dapat dilihat dengan mengunduh dokumen dibawah ini.<br/>
                      <input type="submit" value="Surat Hasil Seleksi" onClick="cetak_surat()" class="savebutton">
                  </p><br />
                  <?php }elseif(($paket != $paketPleno && $paket !='Tahap 2' && $hasil =='') ||  ($paket != $paketPleno && $paket !='Tahap 2' && $pleno == 0)) {?>
                  <p style="font-family:Cambria; font-size:16px;">Berdasarkan keputusan Panitia PMB Sekolah Pascasarjana IPB status hasil seleksi Saudara <strong>dialihkan</strong> ke TAHAP 2 Tahun Akademik <?= $ta ?>.<br /><br />

                      Pengumuman hasil seleksi Tahap Dua akan dilakukan sesuai jadwal pada: <a href="<?= Url::to(['/info/jadwal-pmb']);?>" target="_blank">Jadwal PMB</a>
                  </p><br />

                  <?php }else{?>
                  <p style="font-family:Cambria; font-size:16px;">Pengumuman hasil seleksi akan dilakukan sesuai jadwal pada: <a href="<?= Url::to(['/info/jadwal-pmb']);?>" target="_blank">Jadwal PMB</a>
                  </p><br />
                  <?php }?>
                   
                </div>

               

               

            </div>
          
        </div>
    </div>

    <script>
    function cetak_surat(){

    var url = '<?= Url::to(["/site/cetak","id"=>$nopendaftaran]);?>';
    var redirectWindow = window.open(url, '_blank');
    redirectWindow.location;
        
        
};
    function cetak_surat_verifikasi(){

    var url = '<?= Url::to(["/site/cetak","id"=>$nopendaftaran,"verifikasi"=>1]);?>';
    var redirectWindow = window.open(url, '_blank');
    redirectWindow.location;
        
        
};
  
    function cetak_kartu(){

    var url = '<?= Url::to(["/site/kartu","id"=>$nopendaftaran]);?>';
    var redirectWindow = window.open(url, '_blank');
    redirectWindow.location;
        
        
};
    </script>

