<?php 



?>

<html>
<head>
<title><?php print "Formulir A: " . $data->noPendaftaran. " - " . $data->orang->nama?></title>
<style>
body {
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:13px;
}
table {
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:13px;
}
</style>
</head>
<body>
<div class="coba">

    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
      <tr>
        <td><title><h2>Formulir A</h2></title></td>
        <td style="text-align: right;"><div style="text-align: right;" ><barcode code="<?= $data->noPendaftaran ?>" size="0.7" type="QR" error="M" class="barcode" /></div></td>
      </tr>
    </table>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
      <tr>
        <td width="20"><strong>1</strong></td>
        <td height="18" colspan="7" id="tdtxtnama"><strong>Data Pribadi </strong></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td width="200" id="tdtxtnama">Nama Lengkap (Tanpa Gelar ) </td>
        <td width="7" colspan="-1">:</td>
        <td width="100" colspan="5"><?= $data->orang->nama ?></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Gelar depan / Gelar belakang </td>
        <td colspan="-1">:</td>
        <td colspan="5"><?php $i= 1; foreach($data->orang->gelars as $gelars){ $gelar[$i] = $gelars->nama; $i++; } echo $gelar[1]. " / " . $gelar[2]?></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Tempat, Tanggal Lahir</td>
        <td width="7" colspan="-1">:</td>
        <td colspan="5"><?= $data->orang->tempatLahir. " / " . date('d-m-Y',strtotime($data->orang->tanggalLahir))?></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Kewarganegaraan</td>
        <td width="7" colspan="-1">:</td>
        <td colspan="5"><?= $data->orang->negara->nama ?></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Jenis Kelamin </td>
        <td width="7" colspan="-1">:</td>
        <td colspan="5"><?= $data->orang->jenisKelamin==1? "Laki-laki":"Perempuan"?></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Status Perkawinan </td>
        <td width="7" colspan="-1">:</td>
        <td colspan="5"><?= $data->orang->statusPerkawinan->status ?></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Nama Gadis Ibu Kandung </td>
        <td colspan="-1">:</td>
        <td colspan="5"><?php foreach($data->orang->keamanaans as $keamanan){ echo $keamanan->namaGadisIbu; } ?></td>
      </tr>
    </table>
    
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
      <tr>
        <td width="20">&nbsp;&nbsp;&nbsp; </td>
        <td height="18" colspan="6" ><strong>Alamat Tetap (Untuk surat-menyurat dan komunikasi dalam proses seleksi) </strong></td>
      </tr>
      <?php foreach($data->orang->alamats as $alamat):?>
      <tr>
        <td width="20">&nbsp;</td>
        <td width="140" >Jalan/Perumahan</td>
        <td width="10">:</td>
        <td><?= $alamat->jalan ?></td>
        <td colspan="1">RT/RW</td>
        <td>:</td>
        <td><?= $alamat->rt. " / " . $alamat->rw ?></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Kelurahan/Desa</td>
        <td>:</td>
        <td width="250"><?= $alamat->desaKelurahanKode->namaID ?></td>
        <td>Kecamatan </td>
        <td width="7">:</td>
        <td width="150"><?= $alamat->desaKelurahanKode->kecamatanKode->namaID ?></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Kabupaten/Kodya</td>
        <td>:</td>
        <td><?= $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID ?></td>
        <td>Propinsi</td>
        <td>:</td>
        <td><?= $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID ?></td>
      </tr>
      <?php $kodepos = $alamat->kodePos; endforeach;

      foreach($data->orang->identitas as $identitas):?>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Nomor <?= $identitas->jenisIdentitas->nama ?> </td>
        <td>:</td>
        <td colspan="4"><?= $identitas->identitas ?></td>
      </tr>
    <?php endforeach;

    foreach($data->orang->kontaks as $kontak){
    if($kontak->jenisKontak_id == 3){?>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Telepon</td>
        <td>:</td>
        <td><?= $kontak->kontak ?></td>
        <td>Fax</td>
        <td>:</td>
        <td>-</td>
      </tr>
      <?php }elseif($kontak->jenisKontak_id == 2){ ?>
      <tr>
        <td width="20">&nbsp;</td>
        <td >HP</td>
        <td>:</td>
        <td><?= $kontak->kontak ?></td>
        <td>Kode Pos </td>
        <td>:</td>
        <td><?= $kodepos ?></td>
      </tr>
      <?php }elseif($kontak->jenisKontak_id == 1){ ?>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Email</td>
        <td>:</td>
        <td colspan="4"><?= $kontak->kontak ?></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Alternatif Email </td>
        <td>:</td>
        <td colspan="4">-</td>
      </tr>
      <?php }} ?>
    </table>

    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
      <tr>
        <td width="20" height="18"><strong>2</strong></td>
        <td height="18" colspan="7" ><strong>Status Pekerjaan  </strong></td>
      </tr>
      <?php foreach($data->orang->pekerjaans as $pekerjaan): ?>
      <tr>
        <td width="20">&nbsp;</td>
        <td width="140">Pekerjaan</td>
        <td width="10">:</td>
        <td colspan="5" ><?= $pekerjaan->jenisInstansi->nama ?></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td width="140" >NIP/NIK</td>
        <td width="7" colspan="-1">:</td>
        <td colspan="5"><?= $pekerjaan->noIdentitas ?></td>
      </tr>
      <?php foreach($pekerjaan->instansis as $instansi): ?>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Nama Instansi </td>
        <td colspan="-1">:</td>
        <td colspan="5"><?= $instansi->nama?></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Jabatan </td>
        <td colspan="-1">:</td>
        <td colspan="5"><?= $pekerjaan->jabatan?></td>
      </tr>
      <?php foreach($instansi->alamats as $alamat): 

      if(isset($alamat)){

        $jalan = $alamat->jalan;
        $desa = $alamat->desaKelurahanKode->namaID;
        $kecamatan = $alamat->desaKelurahanKode->kecamatanKode->namaID;
        $kabkota = $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID;
        $propinsi = $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID;
        $kodepos = $alamat->kodePos;
      } endforeach;?>

      <tr>
        <td width="20">&nbsp;</td>
        <td >Jalan</td>
        <td colspan="-1">:</td>
        <td colspan="5"><?=  $jalan ?></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Kelurahan/Desa</td>
        <td colspan="-1">:</td>
        <td width="250"><?=  $desa ?></td>
        <td colspan="2">Kecamatan</td>
        <td width="7">:</td>
        <td width="150"><?=  $kecamatan ?></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td >Kabupaten/Kodya</td>
        <td colspan="-1">:</td>
        <td><?=  $kabkota ?></td>
        <td colspan="2">Propinsi</td>
        <td>:</td>
        <td><?=  $propinsi ?></td>
      </tr>
    
      <tr>
        <td width="20" height="24">&nbsp;</td>
        <td >Telepon</td>
        <td>:</td>
        <td colspan="-1"><?= $instansi->tlp ?></td>
        <td colspan="2">Fax</td>
        <td>:</td>
        <td><?= $instansi->fax?></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>

        <td >Kode Pos </td>
        <td colspan="-1">:</td>
        <td colspan="5"><?= $kodepos ?></td>
      </tr>
      <?php endforeach;endforeach;?>
    </table>

    <?php
    $pn = \app\models\Pendidikan::find()->where(['orang_id' => $data->orang->id]);
    /** @var \app\models\Pendidikan[] $pendidikan */
    $pendidikan = [];

    foreach($pn->all() as $value){
        if ($value->strata == 1)
            $pendidikan[1] = $value;
        elseif ($value->strata == 2)
            $pendidikan[2] = $value;
    }

    ?>

    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
      <tr>
        <td width="20" height="18"><strong>3</strong></td>
        <td height="18" colspan="6" ><p><strong>Riwayat Pendidikan</strong></p></td>
      </tr>
      <tr>
        <td width="20">&nbsp;</td>
        <td colspan="6" ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="2" id="stratas1">
          <tr>
            <td width="20" >a</td>
            <td width="170" >Tanggal Lulus S1 </td>
            <td width="7" colspan="-1">:</td>
            <td width="140" colspan="5"><?= isset($pendidikan[1]->tanggalLulus)? date('d-m-Y', strtotime($pendidikan[1]->tanggalLulus)) : ''; ?></td>
          </tr>
          <tr>
            <td width="20" >&nbsp;</td>
            <td >Nama Perguruan Tinggi Asal </td>
            <td colspan="-1">:</td>
            <td colspan="5"><?= $pendidikan[1]->institusi->nama?></td>
          </tr>
          <tr>
            <td width="20" >&nbsp;</td>
            <td >Fakultas</td>
            <td colspan="-1">:</td>
            <td colspan="5"><?= $pendidikan[1]->fakultas ?></td>
          </tr>
          <tr>
            <td width="20" >&nbsp;</td>
            <td >Program Studi</td>
            <td colspan="-1">:</td>
            <td colspan="5"><?= $pendidikan[1]->programStudi ?></td>
          </tr>
          <tr>
            <td width="20" >&nbsp;</td>
            <td >Alamat Perguruan Tinggi </td>
            <td colspan="-1">:</td>
            <td colspan="5"><?= $pendidikan[1]->jalan ?></td>
          </tr>
          <tr>
            <td width="20" >&nbsp;</td>
            <td >Kota, Propinsi</td>
            <td colspan="-1">:</td>
            <td colspan="5"><?= $pendidikan[1]->kotaAlamat ?></td>
          </tr>
          <tr>
            <td width="20" >&nbsp;</td>
            <td >Negara</td>
            <td colspan="-1">:</td>
            <td colspan="5"><?= $pendidikan[1]->negaraText ?></td>
          </tr>
          <tr>
            <td width="20" >&nbsp;</td>
            <td >Status Akreditasi</td>
            <td colspan="-1">:</td>
            <td colspan="5"><label for="s1akreditasi" class="error"><?= $pendidikan[1]->akreditasi ?></label></td>
          </tr>
          <tr>
            <td width="20" >&nbsp;</td>
            <td >IPK</td>
            <td colspan="-1">:</td>
            <td colspan="5"><?= $pendidikan[1]->ipk ?></td>
          </tr>
          <tr>
            <td width="20" >&nbsp;</td>
            <td >Jumlah SKS </td>
            <td colspan="-1">:</td>
            <td colspan="5" align="left"><?=  $pendidikan[1]->jumlahSKS ?></td>
          </tr>
          <tr>
            <td width="20" >&nbsp;</td>
            <td >Gelar S1 </td>
            <td colspan="-1">:</td>
            <td colspan="5" align="left"><?=  $pendidikan[1]->gelar ?></td>
          </tr>
          <tr>
            <td width="20" >&nbsp;</td>
            <td colspan="7" align="left">Apakah Pendidikan Sdr/i diperoleh melalui Jenjang dari diploma : </td>
          </tr>
        </table></td>
      </tr>

      <tr>
        <td width="20">&nbsp;</td>
        <td colspan="6" >
          <?php if (isset($pendidikan[2]))
          {
            ?>
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2" id="stratas2">
              <tr>
                <td width="20" >b</td>
                <td width="170" >Tanggal Lulus S2 </td>
                <td width="12" colspan="-1">:</td>
                <td width="90" colspan="5"><?=  isset($pendidikan[2]->tanggalLulus)? date('d-m-Y', strtotime($pendidikan[2]->tanggalLulus)) : '';  ?></td>
              </tr>
              <tr>
                <td width="24" >&nbsp;</td>
                <td >Nama Perguruan Tinggi Asal </td>
                <td colspan="-1">:</td>
                <td colspan="5"><?=  $pendidikan[2]->institusi->nama ?></td>
              </tr>
              <tr>
                <td width="24" >&nbsp;</td>
                <td >Fakultas</td>
                <td colspan="-1">:</td>
                <td colspan="5"><?=  $pendidikan[2]->fakultas ?></td>
              </tr>
              <tr>
                <td width="24" >&nbsp;</td>
                <td >Program Studi</td>
                <td colspan="-1">:</td>
                <td colspan="5"><?=  $pendidikan[2]->programStudi ?></td>
              </tr>
              <tr>
                <td width="24" >&nbsp;</td>
                <td >Alamat Perguruan Tinggi </td>
                <td colspan="-1">:</td>
                <td colspan="5"></td>
              </tr>
              <tr>
                <td width="24" >&nbsp;</td>
                <td >Kota, Propinsi </td>
                <td colspan="-1">:</td>
                <td colspan="5"></td>
              </tr>
              <tr>
                <td width="24" >&nbsp;</td>
                <td >Negara</td>
                <td colspan="-1">&nbsp;</td>
                <td colspan="5"></td>
              </tr>
              <tr>
                <td width="24" >&nbsp;</td>
                <td >Status Akreditasi</td>
                <td colspan="-1">:</td>
                <td colspan="5"><label for="s1akreditasi" class="error"><?=  $pendidikan[2]->akreditasi ?></label></td>
              </tr>
              <tr>
                <td width="24" >&nbsp;</td>
                <td >IPK</td>
                <td colspan="-1">:</td>
                <td colspan="5"><?=  $pendidikan[2]->ipk ?></td>
              </tr>
              <tr>
                <td width="24" >&nbsp;</td>
                <td >Jumlah SKS </td>
                <td colspan="-1">:</td>
                <td colspan="5" align="left"><?=  $pendidikan[2]->jumlahSKS ?></td>
              </tr>
              <tr>
                <td width="24" >&nbsp;</td>
                <td >Gelar S2 </td>
                <td colspan="-1">:</td>
                <td colspan="5" align="left"><?=  $pendidikan[2]->gelar ?></td>
              </tr>
            </table>
            <?php
          }
          ?></td>
        </tr>
        
      </table>
      <?php $pilihan=[]; $pilihan[1]=$pilihan[2]=''; $i = 1; 
            foreach($data->pendaftaranHasProgramStudis as $value){
                $pilihan[$i] = $value->programStudi->nama;
                $strata = $value->programStudi->strata;
                $i++;
      }
      ?>
      <br />
      <br />
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
        <tr>
          <td width="20" height="18"><strong>4</strong></td>
          <td width="20" height="18" ><strong>a </strong></td>
          <td height="18" colspan="6" ><strong>Strata Pendidikan yang ingin diikuti (pilih salah satu) </strong></td>
        </tr>
        <tr>
          <td width="20">&nbsp;</td>
          <td width="20" >&nbsp;</td>
          <td >Strata : <?= 'S'.$strata?></td>
          <td width="5" colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td width="20" >&nbsp;</td>
          <td >Program Studi Pilihan Ke-1 : <?= $pilihan[1] ?></td>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td width="20" >&nbsp;</td>
          <td >Program Studi Pilihan Ke-2 : <?= $pilihan[2]?></td>
          <td colspan="5">&nbsp;</td>
        </tr>

      </table>

      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
        <tr>
          <td width="20" height="18"><strong>5</strong></td>
          <td width="20" height="18" ><strong>a </strong></td>
          <td height="18" colspan="7" ><strong>Pernahkah anda mendaftarkan diri di Sekolah Pascasarjana IPB sebelumnya?</strong></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td width="20" >&nbsp;</td>
          <td colspan="7" ></td>
        </tr>
      </table>
      <br/>
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
        <tr>
          <td width="20" height="18"><strong>6</strong></td>
          <td width="20" height="18" ><strong>a </strong></td>
          <td height="18" ><strong> Siapakah yang akan menanggung biaya pendidikan di Sekolah Pascasarjana IPB? </strong></td>
        </tr>

        <tr>
          <td width="20">&nbsp;</td>
          <td width="20" >&nbsp;</td>
          <?php $pembiayaan = "Sumber Beasiswa : " . $data->rencanaPembiayaan->jenisPembiayan->title;
          $pembiayaan.= isset($data->rencanaPembiayaan->deskripsi)? "<br/>Pemberi Beasiswa : ".$data->rencanaPembiayaan->deskripsi : '';
          ?>
          <td><?=  $pembiayaan ?></td>
         </tr>

       </table>
       <br />
       <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
        <tr>
          <td width="20" height="18"><strong>7</strong></td>
          <td height="18" colspan="4" ><strong>Isi kolom berikut ini, jika anda pernah mengikuti test TOEFL atau test TPA atau kedua-duanya:</strong></td>
        </tr>
        <?php $syarat = []; foreach($data->syaratTambahans as $value){
          $syarat[$value->jenisSyaratTambahan->title]=$value;
        } ?>
        <tr>;
          <td width="20">&nbsp;</td>
          <td width="20" >a</td>
          <td width="130" >TOEFL, skor                    </td>
          <td>:</td>
          <td><?= $syarat['TOEFL']->score?></td>
        </tr>
        <tr>
          <td width="20">&nbsp;</td>
          <td width="20" >&nbsp;</td>
          <td >Tanggal</td>
          <td >:</td>
          <td><?= isset($syarat['TOEFL']->dateExercise)? date('d-m-Y', strtotime($syarat['TOEFL']->dateExercise)) : ''; ?></td>
        </tr>
        <tr>
          <td width="20">&nbsp;</td>
          <td width="20" >&nbsp;</td>
          <td >Penyelanggara test</td>
          <td width="10" >:</td>
          <td><?= $syarat['TOEFL']->organizer ?></td>
        </tr>
        <tr>
          <td width="20">&nbsp;</td>
          <td width="20" >b</td>
          <td >TPA, skor                  </td>
          <td>:</td>
          <td><?= $syarat['TPA']->score?></td>
        </tr>
        <tr>
          <td width="20">&nbsp;</td>
          <td width="20" >&nbsp;</td>
          <td >Tanggal</td>
          <td>:</td>
          <td><?= isset($syarat['TPA']->dateExercise)? date('d-m-Y', strtotime($syarat['TPA']->dateExercise)) : ''; ?></td>
        </tr>
        <tr>
          <td width="20">&nbsp;</td>
          <td width="20" >&nbsp;</td>
          <td >Penyelanggara test</td>
          <td>:</td>
          <td><?= $syarat['TPA']->organizer ?></td>
        </tr>
      </table>
      <br />
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
        <tr>
          <td width="20" height="18"><strong>8</strong></td>
          <td width="20" height="18" ><strong>a </strong></td>
          <td height="18" colspan="7" ><strong>Terkait dengan publikasi karya ilmiah</strong></td>
        </tr>
        <tr>
          <td width="20">&nbsp;</td>
          <td width="20" >&nbsp;</td>
          <td colspan="7" >
            <?php foreach($data->karyaIlmiahs as $value):
            print ($value->jurnalInternasional ==1?"Saya publikasikan di jurnal Internasional <br />":"");
            print ($value->jurnalNasionalAkreditasi ==1?"Saya publikasikan di jurnal Nasional terakreditasi<br />":"");
            print ($value->jurnalNasionalTakAkreditasi ==1?"Saya publikasikan di jurnal Nasional tak terakreditasi <br />":"");
            print ($value->belum ==1?"Belum saya publikasikan":"");
            endforeach;
            ?></td>
          </tr>
        </table>
        <br />
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
          <tr>
            <td width="20" height="18"><strong>9</strong></td>
            <td height="18" ><strong>3 nama pemberi rekomendasi sebagai syarat untuk mendaftar ke Sekolah Pascasarjana IPB?</strong></td>
          </tr>
    
          <tr>
            <td width="20">&nbsp;</td>
            <td align="left" >
              <div id="namarekomendasi">
              <?php $rekomendasi=[]; $rekomendasi[1]=$rekomendasi[2]=$rekomendasi[3]=''; 
                      $i = 1; foreach($data->pemberiRekomendasis as $value){
                        $rekomendasi[$i] = $value->nama;
                        $i++;
              } ?>
                <table width="100%" border="0">
                  <tr>
                    <td width="150">Nama dan Gelar Pemberi Rekomendasi 1 </td>
                    <td width="10">:</td>
                    <td><?= $rekomendasi[1] ?></td>
                  </tr>
                  <tr>
                    <td>Nama dan Gelar  Pemberi Rekomendasi 2 </td>
                    <td>:</td>
                    <td><?= $rekomendasi[2] ?></td>
                  </tr>
                  <tr>
                    <td>Nama dan Gelar Pemberi Rekomendasi 3 </td>
                    <td>:</td>
                    <td><?= $rekomendasi[3] ?></td>
                  </tr>
                </table>
              </div>                  </td>
            </tr>

          </table>
          <br />
          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
            <tr>
              <td width="20" height="18" align="left" valign="top"><strong>10</strong></td>
              <td height="18" colspan="3" ><strong>Jusul Skripsi Dan Tesis </strong></td>
            </tr>
            <tr id="skripsirow">
              <td width="20">&nbsp;</td>
              <td width="150" >Judul Skripsi </td>
              <td width="11" >:</td>
              <td><?= $pendidikan[1]->judulTA
                ?></td>
              </tr>
              <?php if (isset($pendidikan[2]))
              {
                ?>
                <tr id="thesisrow">
                  <td width="20">&nbsp;</td>
                  <td >Judul Tesis</td>
                  <td >:</td>
                  <td ><?= $pendidikan[2]->judulTA ?></td>
                </tr>
                <?php
              }
              ?>

            </table>
            <br/>
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
              <tr>
                <td width="20" height="18" align="left" valign="top"><strong>11</strong></td>
                <td height="18" ><strong>Dokumen yang diupload </strong></td>
              </tr>
              <?php 
              Yii::setAlias('@arsip2', realpath(dirname(__FILE__).'/../../../../'));
              $dirpen = Yii::getAlias('@arsip2')."/uploaded/arsip/" . $data->noPendaftaran ."/";
              if (is_dir($dirpen))
              {
                if ($dir = opendir($dirpen))
                {
                  while ( ($file = readdir($dir))!== false)
                  {
                    if ($file!="." && $file!=".." && $file != 'foto.jpg' && $file != 'foto_profile.jpg')
                    {
                      ?>  
                      <tr>
                        <td width="20">&nbsp;</td>
                        <td><?= $file ?></td>
                      </tr>
                      <?php
                    }
                  }
                }
              }
              ?>

            </table>
            


</div>
</body>
</html>