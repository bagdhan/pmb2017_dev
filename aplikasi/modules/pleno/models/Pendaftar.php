<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 4/18/2017
 * Time: 10:30 AM
 */

namespace app\modules\pleno\models;

use app\models\JenisBerkas;
use app\models\SyaratBerkas;
use app\modules\pendaftaran\models\lengkapdata\StatusPekerjaan;
use Yii;
use app\modelsDB\Pendidikan;
use app\modules\pendaftaran\models\Pendaftaran;

/**
 * Class Pendaftar
 *
 * @property string fullName
 * @property string s1ptasal
 * @property string s1fakultas
 * @property string s1programstudi
 * @property string s1akreditasi
 * @property double s1jumlahsks
 * @property double s1ipk
 * @property string s1tanggallulus
 * @property string s2ptasal
 * @property string s2fakultas
 * @property string s2programstudi
 * @property string s2akreditasi
 * @property double s2jumlahsks
 * @property double s2ipk
 * @property string s2tanggallulus
 *
 * @property int strata
 * @property Pendidikan s1
 * @property Pendidikan s2
 * @property string foto
 * @property string tpaTofl
 * @property string beasiswa
 * @property string rekom
 * @property string gender
 * @property string gelarDepan
 * @property string gelarBelakang
 * @property string ttl
 * @property string negara
 * @property string statusKawin
 * @property string namaIbu
 * @property string identitasKontak
 * @property string alamatTable
 * @property string berkas
 *
 * @property string kodeProdi1
 * @property string kodeProdi2
 * @property string lastIpk
 * @property string lastAkr
 * @property string lastTpa
 *
 * @package app\modules\pleno\models
 */
class Pendaftar extends Pendaftaran
{

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->orang->nama;
    }
	
	public function getUsia()
    {
        return $this->orang->usia;
    }

    /**
     * @return string
     */
    public function getStrata()
    {
        return empty($this->pendaftaranHasProgramStudis) ? 2 :
            $this->pendaftaranHasProgramStudis[0]->programStudi->strata;
    }

    public $_s1;
    public $_s2;


    /**
     * @return Pendidikan|array|null|\yii\db\ActiveRecord
     */
    public function getS1()
    {
        if ($this->_s1 == null) {
            $s1 = Pendidikan::find()->where(['orang_id' => $this->orang_id, 'strata' => 1])->one();
            $this->_s1 = empty($s1) ? new Pendidikan(['orang_id' => $this->orang_id, 'strata' => 1]) : $s1;
        }

        return $this->_s1;
    }

    /**
     * @return Pendidikan|array|null|\yii\db\ActiveRecord
     */
    public function getS2()
    {
        if ($this->_s2 == null) {
            $s2 = Pendidikan::find()->where(['orang_id' => $this->orang_id, 'strata' => 2])->one();
            $this->_s2 = empty($s2) ? new Pendidikan(['orang_id' => $this->orang_id, 'strata' => 2]) : $s2;
        }

        return $this->_s2;
    }

    /**
     * @return string
     */
    public function getS1ptasal()
    {
        return $this->s1->institusi_id != null ? $this->s1->institusi->nama : '';
    }

    /**
     * @return string
     */
    public function getS1fakultas()
    {
        return $this->s1->fakultas;
    }

    /**
     * @return string
     */
    public function getS1programstudi()
    {
        return $this->s1->programStudi;
    }

    /**
     * @return string
     */
    public function getS1akreditasi()
    {
        return $this->s1->akreditasi;
    }

    /**
     * @return int
     */
    public function getS1jumlahsks()
    {
        return $this->s1->jumlahSKS;
    }

    /**
     * @return string
     */
    public function getS1ipk()
    {
        return $this->s1->ipk;
    }

    /**
     * @return string
     */
    public function getS1tanggallulus()
    {
        return Yii::$app->formatter->asDate($this->s1->tanggalLulus, 'long');
    }

    /**
     * @return string
     */
    public function getS2ptasal()
    {
        return $this->s2->institusi_id != null ? $this->s2->institusi->nama : '';
    }

    /**
     * @return string
     */
    public function getS2fakultas()
    {
        return $this->s2->fakultas;
    }

    /**
     * @return string
     */
    public function getS2programstudi()
    {
        return $this->s2->programStudi;
    }

    /**
     * @return string
     */
    public function getS2akreditasi()
    {
        return $this->s2->akreditasi;
    }

    /**
     * @return int
     */
    public function getS2jumlahsks()
    {
        return $this->s2->jumlahSKS;
    }

    /**
     * @return string
     */
    public function getS2ipk()
    {
        return $this->s2->ipk;
    }

    /**
     * @return string
     */
    public function getS2tanggallulus()
    {
        return Yii::$app->formatter->asDate($this->s2->tanggalLulus, 'long');
    }

    /**
     * @return string
     */
    public function getFoto()
    {
        $dir = Yii::getAlias('@arsipdir') . '/' . $this->noPendaftaran;

        if (file_exists($dir . '/foto_profile.jpg')) {
            Yii::$app->assetManager->publish($dir);
            $directoryAsset = Yii::$app->assetManager->getPublishedUrl($dir) . '/foto_profile.jpg';
        } else {
            $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@app/adminlte/dist/') . '/img/user.jpg';
        }

        return $directoryAsset;
    }

    /**
     * @return string
     */
    public function getTpaTofl()
    {
        $out = '';
        foreach ($this->syaratTambahans as $syaratTambahan) {
            $out .= "<tr><td>" . $syaratTambahan->jenisSyaratTambahan->title . " (skor/date)</td>
                        <td> $syaratTambahan->score / " .
                Yii::$app->formatter->asDate($syaratTambahan->dateExercise) . "</td></tr>";
        }
        return $out;
    }

    /**
     * @return string
     */
    public function getBeasiswa()
    {
        if ($this->rencanaPembiayaan_id == null)
            return '';
        $title = $this->rencanaPembiayaan->jenisPembiayan->title;
        $desk = $this->rencanaPembiayaan->deskripsi;
        return $this->rencanaPembiayaan == null ? '' : ($this->rencanaPembiayaan_id >= 6 ?
            ($this->rencanaPembiayaan_id <= 8 ? "$title - $desk" : $title)
            : $title);
    }

    /**
     * @return string
     */
    public function getRekom()
    {
        $out = '';
        $i = 1;
        foreach ($this->pemberiRekomendasis as $rekomendasi) {
            $out .= "<tr><td>$i</td> <td> $rekomendasi->nama</td></tr>";
            $i++;
        }
        return $out;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        $switch = [1 => 'Laki-laki', 0 => 'Perempuan', '' => ''];
        return $switch[$this->orang->jenisKelamin];
    }

    /**
     * @return string
     */
    public function getGelarDepan()
    {
        $gelar = '';
        foreach ($this->orang->gelars as $item) {
            if ($item->depanBelakang == 0)
                $gelar .= $item->nama;
        }
        return $gelar;
    }

    /**
     * @return string
     */
    public function getGelarBelakang()
    {
        $gelar = '';
        foreach ($this->orang->gelars as $item) {
            if ($item->depanBelakang == 1)
                $gelar .= $item->nama;
        }
        return $gelar;
    }

    /**
     * @return string
     */
    public function getTtl()
    {
        $tgl = Yii::$app->formatter->asDate($this->orang->tanggalLahir, 'long');
        return $this->orang->tempatLahir . ", $tgl";
    }

    /**
     * @return string
     */
    public function getNegara()
    {
        if ($this->orang->negara_id == null)
            return '';
        return $this->orang->negara->nama;
    }

    /**
     * @return string
     */
    public function getStatusKawin()
    {
        if ($this->orang->statusPerkawinan_id == null)
            return '';
        return $this->orang->statusPerkawinan->status;
    }

    /**
     * @return string
     */
    public function getNamaIbu()
    {
        if ($this->orang->keamanaans == null)
            return '';
        return $this->orang->keamanaans[0]->namaGadisIbu;
    }

    public function getIdentitasKontak()
    {
        $out = '';

        foreach ($this->orang->identitas as $identitas) {
            $out .= "<tr><td>" . $identitas->jenisIdentitas->nama . "</td> <td> $identitas->identitas</td></tr>";
        }

        foreach ($this->orang->kontaks as $kontak) {
            $out .= "<tr><td>" . $kontak->jenisKontak->nama . "</td> <td> $kontak->kontak</td></tr>";
        }
        return $out;
    }

    public function getAlamatTable($class = '')
    {
        if ($this->orang->alamats == null)
            return '';
        $alamat = $this->orang->alamats[0];
        $des = '';
        if (!empty($alamat->desaKelurahanKode)) {
            $des = "<tr>
                        <td>Kelurahan/Desa</td>
                        <td>" . $alamat->desaKelurahanKode->namaID . "</td>
                    </tr>
                    <tr>
                        <td>Kecamatan</td>
                        <td>" . $alamat->desaKelurahanKode->kecamatanKode->namaID . "</td>
                    </tr>
                    <tr>
                        <td>Kabupaten/Kodya</td>
                        <td>" . $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID . "</td>
                    </tr>
                    <tr>
                        <td>Propinsi</td>
                        <td>" . $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID . "</td>
                    </tr>";
        }

        return "<table class='$class'>
                    <tbody>
                    <tr>
                        <td>Jalan/Perumahan</td>
                        <td>$alamat->jalan</td>
                    </tr>
                    <tr>
                        <td>RT/RW</td>
                        <td>$alamat->rt/$alamat->rw</td>
                    </tr>
                    $des
                    <tr>
                        <td>Kode Pos</td>
                        <td>$alamat->kodePos</td>
                    </tr>
                    </tbody>
                </table>";
    }

    public function getPekerjaan()
    {
        $out = "<div class='well'>
                Belum Bekerja
              </div>";
        $pekerjaan = StatusPekerjaan::findOne(['orang_id' => $this->orang_id]);
        if (!empty($pekerjaan)) {
            $pekerjaan->run();
            if ($pekerjaan->jenisInstansi_id != 1) {
                $out = "<div class='row'>
                    <div class=' col-sm-6'>
    					<table class='table table-user-information'>    
    						<tbody>
                                <tr><td>Pekerjaan</td><td> " . $pekerjaan->jabatan . "</td></tr>
                                <tr><td>NIP/NIK</td><td> $pekerjaan->noIdentitas </td></tr>
                                <tr><td>Nama Instansi</td><td>$pekerjaan->namaInstansi</td></tr>
    							<tr><td>Jalan</td><td>$pekerjaan->jalan</td></tr>
                				<tr><td>Kelurahan/Desa</td><td>$pekerjaan->des</td></tr>
                				<tr><td>Kecamatan</td><td>$pekerjaan->kec</td></tr>
    						</tbody>
    					</table>
                    </div>
                    <div class=' col-sm-6'>
                        <table class='table table-user-information'>    
    						<tbody>
                                <tr><td>Kabupaten/Kodya</td><td>$pekerjaan->kab</td></tr>
                                <tr><td>Propinsi</td><td>$pekerjaan->prov</td></tr>
                                <tr><td>Telepon</td><td>$pekerjaan->tlp</td></tr>
                                <tr><td>Fax</td><td>$pekerjaan->fax</td></tr>
                                
    						</tbody>
    					</table>
                    </div>
                </div>";
            }
        }
        return $out;
    }

    public function getBerkas()
    {
        $out = '';
        $files = JenisBerkas::find()->where(['strata' => $this->strata])->all();

        /** @var SyaratBerkas[] $dataUp */
        $dataUp = [];
        foreach ($this->syaratBerkas as $syaratBerkas) {
            $dataUp[$syaratBerkas->jenisBerkas_id] = SyaratBerkas::findOne($syaratBerkas->id);
        }

        /** @var JenisBerkas $file */
        foreach ($files as $file) {
            $blb = "<span title='Belum Unggah Berkas' class='glyphicon glyphicon-remove' aria-hidden='true'></span>";
            if (isset($dataUp[$file->id])) {
                $sb = $dataUp[$file->id];
                $blb = "<a href='$sb->urlBerkas/$sb->file' target='_blank'><span title='Lihat Berkas'
                    class='glyphicon glyphicon-search' aria-hidden='true'></span></a>";
            }
            $out .= "<tr style='margin:0; width:100%; border-bottom-style: dashed; border-width: thin;'>
                        <td>$file->nama</td>
                        <td align='center'>$blb</td>
                      </tr>";

        }

        return "<table style='margin:0; width:100%;'><tbody>$out</tbody></table>";
    }

    /**
     * @return string
     */
    public function getKodeProdi1()
    {
        foreach ($this->pendaftaranHasProgramStudis as $hasProgramStudi) {
            if ($hasProgramStudi->urutan == 1)
                return $hasProgramStudi->programStudi->inisial;
        }
        return "";
    }

    /**
     * @return string
     */
    public function getKodeProdi2()
    {
        foreach ($this->pendaftaranHasProgramStudis as $hasProgramStudi) {
            if ($hasProgramStudi->urutan == 2)
                return $hasProgramStudi->programStudi->inisial;
        }
        return "";
    }

    /**
     * @return string
     */
    public function getLastIpk()
    {
        if ($this->strata == 3)
            return $this->s2ipk;
        return $this->s1ipk;
    }

    /**
     * @return string
     */
    public function getLastAkr()
    {
        if ($this->strata == 3)
            return $this->s2akreditasi;
        return $this->s1akreditasi;
    }

    /**
     * @return string
     */
    public function getLastTpa()
    {
        $out = '';
        foreach ($this->syaratTambahans as $syaratTambahan) {
            if (strtolower($syaratTambahan->jenisSyaratTambahan->title)=='tpa')
            $out = $syaratTambahan->score ;
        }
        return $out;
    }
}