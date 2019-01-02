<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 4/18/2017
 * Time: 10:23 AM
 *
 * @var \app\modules\pleno\models\Pendaftar $dataPelamar
 * @var \app\modules\pleno\models\ProsesSidang $pSidang
 */
$lbr = 8;
$lbrfto = 4;

$s2 = '';
if ($dataPelamar->strata == 3) {
    $lbr = 5;
    $lbrfto = 2;

    $s2 = "<div class=' col-md-5 col-lg-5'>
            <div class='panel panel-info'>
                <div class='panel-heading'>
                    <h3 class='panel-title'>
                        <a role='button' data-toggle='collapse' data-parent='#accordion'
                           href='#Pendidikans2' aria-expanded='true' aria-controls='collapseOne'>
                            Pendidikan S2 </a></h3>
                </div>
                <div id='Pendidikans2' class='panel-collapse collapse in' role='tabpanel'
                     aria-labelledby='headingOne'>
                    <div class='panel-body'>
                        <table class='table table-user-information col-sm-6'>
                            <tbody>
                            <tr>
                                <td>PT Asal</td>
                                <td>$dataPelamar->s2ptasal</td>
                            </tr>
                            <tr>
                                <td>Fakultas</td>
                                <td>$dataPelamar->s2fakultas</td>
                            </tr>
                            <tr>
                                <td>Program Studi</td>
                                <td>$dataPelamar->s2programstudi </td>
                            </tr>
                            <tr>
                                <td>Akreditasi</td>
                                <td>$dataPelamar->s2akreditasi </td>
                            </tr>
                            <tr>
                                <td>IPK</td>
                                <td>$dataPelamar->s2ipk </td>
                            </tr>
                            <tr>
                                <td>Jumlah SKS</td>
                                <td>$dataPelamar->s2jumlahsks </td>
                            </tr>
                            <tr>
                                <td>Tanggal Lulus</td>
                                <td>$dataPelamar->s2tanggallulus </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>";
}
?>
<div class='detailpelamar'>

    <div class='row'>
        <div class='col-md-<?= $lbrfto?> col-lg-<?= $lbrfto?>' align='center'>
            <img alt='User Pic' width='90%' src='<?= $dataPelamar->foto?>' class='img-circle img-responsive'>
        </div>
        <div class=' col-md-<?= $lbr?> col-lg-<?= $lbr?>'>
            <div class='panel panel-info'>
                <div class='panel-heading'>
                    <h3 class='panel-title'>
                        <a role='button' data-toggle='collapse' data-parent='#accordion'
                           href='#Pendidikans1<?= $dataPelamar->noPendaftaran?>' aria-expanded='true' aria-controls='collapseOne'>
                            Pendidikan S1 </a></h3>
                </div>
                <div id='Pendidikans1<?= $dataPelamar->noPendaftaran?>' class='panel-collapse collapse in' role='tabpanel'
                     aria-labelledby='headingOne'>
                    <div class='panel-body'>
                        <table class='table table-user-information'>
                            <tbody>
                            <tr>
                                <td>PT Asal</td>
                                <td><?= $dataPelamar->s1ptasal?></td>
                            </tr>
                            <tr>
                                <td>Fakultas</td>
                                <td><?= $dataPelamar->s1fakultas?></td>
                            </tr>
                            <tr>
                                <td>Program Studi</td>
                                <td><?= $dataPelamar->s1programstudi ?></td>
                            </tr>
                            <tr>
                                <td>Akreditasi</td>
                                <td><?= $dataPelamar->s1akreditasi ?></td>
                            </tr>
                            <tr>
                                <td>IPK</td>
                                <td><?= $dataPelamar->s1ipk ?></td>
                            </tr>
                            <tr>
                                <td>Jumlah SKS</td>
                                <td><?= $dataPelamar->s1jumlahsks ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Lulus</td>
                                <td><?= $dataPelamar->s1tanggallulus ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?= $s2?>

    </div>
    <div class='col-md-12 col-lg-12 '>
        <div class='panel panel-info' align='center'>
            <a class='btn btn-default' role='button' data-toggle='collapse' data-parent='#accordion'
               href='#r2<?= $dataPelamar->noPendaftaran?>' aria-expanded='true' aria-controls='collapseOne'>
                <i class='fa fa-angle-double-down' aria-hidden='true'></i>
                Informasi Tambahan & Hasil Keputusan <i class='fa fa-angle-double-down' aria-hidden='true'></i> </a>
            <div id='r2<?= $dataPelamar->noPendaftaran?>' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='headingOne'>
                <div class='panel-body'>
                    <div class=' col-md-3 col-lg-3 '>
                        <h3>
                            <a role='button' data-toggle='collapse' data-parent='#accordion'
                               href='#infopend<?= $dataPelamar->noPendaftaran?>' aria-expanded='true' aria-controls='collapseOne'>
                                Info Pendaftaran </a></h3>
                        <table class='table table-user-information'>
                            <tbody>
                            <tr>
                                <td>No Pendaftaran</td>
                                <td><?= $dataPelamar->noPendaftaran?></td>
                            </tr>
                            <?= $dataPelamar->tpaTofl?>
                            <tr>
                                <td>Biaya Pendidikan</td>
                                <td><?= $dataPelamar->beasiswa?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class=' col-md-3 col-lg-3'>
                        <h3>
                            <a role='button' data-toggle='collapse' data-parent='#accordion'
                               href='#rekom<?= $dataPelamar->noPendaftaran?>' aria-expanded='true' aria-controls='collapseOne'>
                                Pemberi Rekomendasi </a></h3>
                        <table class='table table-user-information'>
                            <tbody>
                            <?= $dataPelamar->rekom?>
                            </tbody>
                        </table>
                    </div>
                    <div class=' col-md-3 col-lg-3'>
                        <h3>
                            <a role='button' data-toggle='collapse' data-parent='#accordion'
                               href='#hasilseleksi<?= $dataPelamar->noPendaftaran?>' aria-expanded='true' aria-controls='collapseOne'>
                                Hasil Seleksi </a></h3>
                        <table class='table table-user-information'>
                            <tbody>
                            <tr>
                                <td><?= $pSidang->hasilSeleksi?></td>
                            </tr>
                            <tr>
                                <td><?= $pSidang->psSeleksi?></td>
                            </tr>
                            <tr>
                                <td><?= $pSidang->ketSeleksi?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class=' col-md-3 col-lg-3'>
                        <h3>
                            <a role='button' data-toggle='collapse' data-parent='#accordion'
                               href='#hasilpleno<?= $dataPelamar->noPendaftaran?>' aria-expanded='true' aria-controls='collapseOne'>
                                Hasil Keputusan </a></h3>
                        <div class='cbtn' >
                            <label>
                                <textarea class='form-control' name='textkeputusan'
                                    <?php echo $pSidang->hasilKeputusan_id > 0 ? 'disabled' : ''?>
                                rows='6'><?= $pSidang->keterangan?></textarea>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id='r3<?= $dataPelamar->noPendaftaran?>' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='headingOne'>
        <div class=' col-md-12 col-lg-12'>
            <div class='panel panel-info' align='center'>
                <div class=''>
                    <h3 class='panel-title'>
                        <a class='btn btn-default' role='button' data-toggle='collapse' data-parent='#accordion'
                           href='#dp<?= $dataPelamar->noPendaftaran?>' aria-expanded='true' aria-controls='collapseOne'>
                            <i class='fa fa-angle-double-down' aria-hidden='true'></i>
                            Data Pribadi <i class='fa fa-angle-double-down' aria-hidden='true'></i> </a></h3>
                </div>
                <div id='dp<?= $dataPelamar->noPendaftaran?>' class='panel-collapse collapse ' role='tabpanel'
                     aria-labelledby='headingOne'>
                    <div class='panel-body'>
                        <div class='row'>
                            <div class=' col-sm-6'>
                                <h3><a>Informasi</a></h3>
                                <table class='table table-user-information'>
                                    <tbody>
                                    <tr>
                                        <td>Nama</td>
                                        <td> <?= $dataPelamar->fullName?></td>
                                    </tr>
                                    <tr>
                                        <td>Gelar</td>
                                        <td> <?= "$dataPelamar->gelarBelakang/$dataPelamar->gelarBelakang"?></td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Kelamin</td>
                                        <td><?= $dataPelamar->gender?></td>
                                    </tr>
                                    <tr>
                                        <td>Tempat, Tanggal Lahir</td>
                                        <td><?= $dataPelamar->ttl?></td>
                                    </tr>
									<tr>
                                        <td>Usia</td>
                                        <td><?= $dataPelamar->usia?> Tahun</td>
                                    </tr>
                                    <tr>
                                        <td>Kewarganegaraan</td>
                                        <td><?= $dataPelamar->negara?></td>
                                    </tr>
                                    <tr>
                                        <td>Status Perkawinan</td>
                                        <td><?= $dataPelamar->statusKawin?></td>
                                    </tr>
                                    <tr>
                                        <td>Nama Gadis Ibu Kandung</td>
                                        <td><?= $dataPelamar->namaIbu?></td>
                                    </tr>
                                    <?= $dataPelamar->identitasKontak?>
                                    </tbody>
                                </table>
                            </div>
                            <div class=' col-sm-6'>
                                <h3><a>Alamat</a></h3>
                                <?= $dataPelamar->getAlamatTable('table table-user-information')?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='panel panel-info col-md-12 col-lg-12'>
        <div class='' align='center'>
            <a class='btn btn-default' role='button' data-toggle='collapse' data-parent='#accordion'
               href='#r4<?= $dataPelamar->noPendaftaran?>' aria-expanded='true' aria-controls='collapseOne'>
                <i class='fa fa-angle-double-down' aria-hidden='true'></i>
                Berkas Unggahan Upload dan Pekerjaan <i class='fa fa-angle-double-down' aria-hidden='true'></i> </a>
            <div id='r4<?= $dataPelamar->noPendaftaran?>' class='panel-collapse collapse' role='tabpanel' aria-labelledby='headingOne'>
                <div class=' col-md-3 col-lg-3' align='left'>
                    <h3>
                        <a role='button' data-toggle='collapse' data-parent='#accordion'
                           href='#fileU<?= $dataPelamar->noPendaftaran?>' aria-expanded='true' aria-controls='collapseOne'>
                            Berkas Unggahan </a></h3>
                    <hr>
                    <?= $dataPelamar->berkas?>
                </div>
                <div class=' col-md-9 col-lg-9'>
                    <h3>
                        <a role='button' data-toggle='collapse' data-parent='#accordion'
                           href='#kerja<?= $dataPelamar->noPendaftaran?>' aria-expanded='true' aria-controls='collapseOne'>
                            Status Pekerjaan </a></h3>
                    <?= $dataPelamar->getPekerjaan()?>
                </div>
            </div>
        </div>

    </div>
</div>