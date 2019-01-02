<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/9/2017
 * Time: 2:59 PM
 */

use app\modelsDB\Fakultas;
use app\modelsDB\ManajemenJalurMasuk;
use app\modelsDB\PaketPendaftaranHasManajemenJalurMasuk;
use yii\helpers\ArrayHelper;
use app\models\PaketPendaftaran;
use app\components\Lang;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm; */

$this->title = Lang::t('Program Studi yang ditawarkan', 'List Programs');
// $this->params['breadcrumbs'][] = $this->title;
$this->params['sidebar'] = 0;

$get = Yii::$app->request->get();

$paketPendaftar = PaketPendaftaran::findOne(['uniqueUrl' => isset($get['jenismasuk']) ? $get['jenismasuk'] : "tahap1_2018"]);
$paketId = empty($paketPendaftar) ? 1 : $paketPendaftar->id;

$fakultas = Fakultas::find()->all();

$listMj = ArrayHelper::getColumn(PaketPendaftaranHasManajemenJalurMasuk::find()
    ->where(['paketPendaftaran_id' => $paketId])->asArray()->all(), 'manajemenJalurMasuk_id');

if ($listMj == null)
    throw new \yii\web\NotAcceptableHttpException('Not Available');

$arrayProdi = ArrayHelper::getColumn(ManajemenJalurMasuk::find()
    ->where(" id IN (" . join(',', $listMj) . ")")->andWhere(" aktif <> 0 ")->all(), 'programStudi_id');

$s2 = '';
$s3 = '';

/** @var Fakultas $fak */
foreach ($fakultas as $fak) {
    $sowfak2 = $paketPendaftar->cekAda($fak->id, 2, "fak");
    $sowfak3 = $paketPendaftar->cekAda($fak->id, 3, "fak");
    if ($sowfak2) {
        $s2 .= "
<div class='box box-info'>
    <div class='box-header'>
        <i class='fa fa-plus'></i>
        <h3 class='box-title'>" . (Lang::id() ? $fak->nama : $fak->nama_en) . "</h3>
        
        <div class='pull-right box-tools'>
            <button class='btn btn-info btn-sm' data-widget='remove' data-toggle='tooltip' title='Remove'>
                <i class='fa fa-times'></i></button>
        </div>
    </div>
    <div class='box-body'>";
        $s2 .= "<table class='table table-bordered'><tr >
            <th >".(Lang::t('Kode', 'Code'))."</th>
            <th >".(Lang::t('Program Studi', 'Study Program'))."</th>
            <th >".Lang::t('Kode PS','Abbreviation')."</th>
            <th >".Lang::t('Strata','Program')."</th>
        </tr>";
    }
    if ($sowfak3) {
        $s3 .= "<div class='box box-info'>
    <div class='box-header'>
        <i class='fa fa-plus'></i>
        <h3 class='box-title'>" . (Lang::id() ? $fak->nama : $fak->nama_en) . "</h3>
        
        <div class='pull-right box-tools'>
            <button class='btn btn-info btn-sm' data-widget='remove' data-toggle='tooltip' title='Remove'>
                <i class='fa fa-times'></i></button>
        </div>
    </div>
    <div class='box-body'>";


        $s3 .= "<table class='table table-bordered'><tr >
            <th >".(Lang::t('Kode', 'Code'))."</th>
            <th >".(Lang::t('Program Studi', 'Study Program'))."</th>
            <th >".Lang::t('Kode PS','Abbreviation')."</th>
            <th >".Lang::t('Strata','Program')."</th>
        </tr>";
    }
    foreach ($fak->departemens as $dep) {
        $sowdep2 = $paketPendaftar->cekAda($dep->id, 2);
        $sowdep3 = $paketPendaftar->cekAda($dep->id, 3);
        if (!empty($dep->programStudis)) {
            if ($sowdep2)
            $s2 .= "<tr><td colspan='4'><span class='text-aqua'>" . (Lang::id() ? $dep->nama : $dep->nama_en) . "</span></td></tr>";
            if ($sowdep3)
            $s3 .= "<tr><td colspan='4'><span class='text-aqua'>" . (Lang::id() ? $dep->nama : $dep->nama_en) . "</span></td></tr>";

            foreach ($dep->programStudis as $programStudi) {
                if (in_array($programStudi->id, $arrayProdi)) {
                    if ($programStudi->strata == 2)
                        $s2 .= "
                            <tr>
                                <td>$programStudi->kode</td>
                                <td>" . (Lang::id() ? $programStudi->nama : $programStudi->nama_en) . "</td>
                                <td>$programStudi->inisial</td>
                                <td>".(Lang::id() ? "S".$programStudi->strata : "Master")."</td>
                            </tr>
                        ";
                    else
                        $s3 .= "
                            <tr>
                                <td>$programStudi->kode</td>
                                <td>". (Lang::id() ? $programStudi->nama : $programStudi->nama_en) ."</td>
                                <td>$programStudi->inisial</td>
                                <td>".(Lang::id() ? "S".$programStudi->strata : "Doctoral")."</td>
                            </tr>
                        ";
                }
            }

        }
    }
    if ($sowfak2) {
        $s2 .= "</table>";
        $s2 .= "</div></div>";
    }
    if ($sowfak3) {
        $s3 .= "</table>";
        $s3 .= "</div></div>";
    }


}

?>

<div class="row">
    <div class=" col-lg-6 col-md-12"><h2 class="page-header"><?= Lang::t('Program Magister',' Master Program')?></h2><?= $s2?></div>
    <div class=" col-lg-6 col-md-12"><h2 class="page-header"><?= Lang::t('Program Doktor','Doctoral Program')?></h2><?= $s3?></div>
</div>
