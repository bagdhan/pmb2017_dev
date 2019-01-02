<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 4/18/2017
 * Time: 10:15 AM
 */

namespace app\modules\pleno\models;


use app\models\Pendaftaran;
use app\models\PaketPendaftaran;
use app\models\Fakultas;
use app\modelsDB\ProgramStudi;
use app\modelsDB\FinalStatus;
use app\modelsDB\UserHasDepartemen;
use app\modelsDB\UserHasFakultas;
use app\modelsDB\UserHasProgramStudi;
use Yii;
use app\modelsDB\RelasiSidang;


/**
 * Class Sidang
 *
 * @property RelasiSidang[] listChild
 *
 * @package app\modules\pleno\models
 */
class Sidang extends \app\modelsDB\Sidang
{


    /**
     * @return array
     */
    public function getListNopenByFakultas()
    {
        $out = [];

        foreach ($this->prosesSidangs as $prosesSidang) {
            $fakId = $prosesSidang->pendaftaranHasProgramStudi->programStudi->departemen->fakultas_id;
            $noPen = $prosesSidang->pendaftaranHasProgramStudi->pendaftaran_noPendaftaran;
            $urutan = $prosesSidang->pendaftaranHasProgramStudi->urutan;
            if (self::accessUserProdi($prosesSidang->pendaftaranHasProgramStudi->programStudi))
                $out[$fakId][$urutan][$prosesSidang->id] = $noPen;
        }
        /** @var FinalStatus[] $tunda */
        $tunda = FinalStatus::find()->where(['tahun' => (date('Y') - 1), 'statusMasuk_id' => 5])->all();
        $tunda = empty($tunda) ? [] : $tunda;
        foreach ($tunda as $item) {
            $fakId = $item->prosesSidang->pendaftaranHasProgramStudi->programStudi->departemen->fakultas_id;
            $noPen = $item->prosesSidang->pendaftaranHasProgramStudi->pendaftaran_noPendaftaran;
            if (self::accessUserProdi($item->prosesSidang->pendaftaranHasProgramStudi->programStudi))
                $out[$fakId][3][$item->prosesSidang->id] = $noPen;
        }
        return $out;
    }


    public function getProsesSidangs()
    {
        $prosesSidangs = ProsesSidang::find()
            ->innerJoinWith([
                'pendaftaranHasProgramStudi' => function ($query) {
                    /** @var $query yii\db\ActiveQuery */
                    $query->innerJoinWith('programStudi');
                },
            ])
            ->where([
                'sidang_id' => $this->id,
            ])
            ->orderBy(['kode'=>SORT_ASC,'prosesSidang.id'=>SORT_ASC])
            ->all();
        return $prosesSidangs;
    }

    public function showFak()
    {

    }

    public static function accessUserProdi(ProgramStudi $ps)
    {
        if (Yii::$app->user->isGuest)
            return false;
        $accessRule = Yii::$app->user->identity->accessRole_id;

        switch ($accessRule) {
            case 1 :
            case 2 :
            case 3 :
            case 8 :
                return true;
                break;
            case 4 :
                $ch = UserHasFakultas::findOne([
                    'fakultas_id' => $ps->departemen->fakultas_id,
                    'user_id' => Yii::$app->user->id,
                ]);
                if (!empty($ch))
                    return true;
                break;
            case 5 :
                $ch = UserHasDepartemen::findOne([
                    'departemen_id' => $ps->departemen_id,
                    'user_id' => Yii::$app->user->id,
                ]);
                if (!empty($ch))
                    return true;
                break;
            case 6 :
                $ch = UserHasProgramStudi::findOne([
                    'programStudi_id' => $ps->id,
                    'user_id' => Yii::$app->user->id,
                ]);
                if (!empty($ch))
                    return true;
                break;
            default :
                return false;
                break;
        }

        return false;
    }

    public function getListChild()
    {
        $child = $this->relasiSidangs;

        if (empty($child) || (isset($_POST['rechild']) && $_POST['rechild'] == 1)) {
            if ($this->jenisSidang_id == 1) {
                /** @var self[] $sidang */
                $sidang = self::find()->where(['paketPendaftaran_id' => $this->paketPendaftaran_id])
                    ->andWhere(" jenisSidang_id <> 1")->all();
                $i = 1;
                foreach ($sidang as $item) {
                    $opt = [
                        'sidang_id' => $this->id,
                        'child' => $item->id,
                    ];
                    $child[$i] = RelasiSidang::findOne($opt);
                    if (empty($child[$i]))
                        $child[$i] = new RelasiSidang($opt);
                    $child[$i]->seleksi = $item->jenisSidang_id == 3 ? 1 : null;
                    $child[$i]->save();
                    $i++;
                }
            }
        }
        return $child;
    }

    public static function getDataTable($fak = '', $level=0 , $url)
    {
        $_POST['url'] = $url;
        $_POST['jenisSidang'] = 3;
        
        $i = 1;
        $data['data']['tot'][3] = 0;
        $data['data']['tot'][4] = 0;
        $data['data']['tot'][5] = 0;
        $data['data']['tot'][6] = 0;
        $data['data']['tot'][7] = 0;
        $data['data']['tot'][8] = 0;
        $data['data']['tot'][9] = 0;
        $data['data']['tot'][10] = 0;
        $data['data']['tot'][11] = 0;

        $paketPendaftaran = PaketPendaftaran::find()->where(['uniqueUrl'=>$url])->one();
        $jalurMasuk = isset($paketPendaftaran)? $paketPendaftaran->getJalurMasuk() : '';
        if ($level == 4 || $level == 5 || $level == 6) {
            $prodi = \app\models\ProgramStudi::getTblMayor($fak,$jalurMasuk);
            foreach ($prodi as $d) {
                $_POST['sql'] = ['ps.inisial'=>$d['inisial']];
                
                $jumS2pleno1 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>$_POST['jenisSidang']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                $jumS3pleno1 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>$_POST['jenisSidang']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();


                $jumS2seleksi = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where('pps.hasilKeputusan_id IS NOT NULL');},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>$_POST['jenisSidang']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $jumS3seleksi = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where('pps.hasilKeputusan_id IS NOT NULL');},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>$_POST['jenisSidang']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $data['data'][$d['inisial']][1] = $i;
                $data['data'][$d['inisial']][2] = $d['nama'] . ' (' . $d['inisial'] . ')';
                $data['data'][$d['inisial']][3] = sizeof($jumS2pleno1);
                $data['data'][$d['inisial']][4] = sizeof($jumS3pleno1);
                $data['data'][$d['inisial']][5] = $data['data'][$d['inisial']][3] + $data['data'][$d['inisial']][4];
                $data['data'][$d['inisial']][6] = sizeof($jumS2seleksi);
                $data['data'][$d['inisial']][7] = sizeof($jumS3seleksi);
                $data['data'][$d['inisial']][8] = $data['data'][$d['inisial']][6] + $data['data'][$d['inisial']][7];

                $limpahan = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql']);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>$_POST['jenisSidang']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $data['data'][$d['inisial']][10] = sizeof($limpahan);
    
                $limpahan = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql']);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where('pps.hasilKeputusan_id IS NOT NULL');},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>$_POST['jenisSidang']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                $data['data'][$d['inisial']][11] = sizeof($limpahan);

                if ($data['data'][$d['inisial']][5] > 0)
                    $persen = round((($data['data'][$d['inisial']][8] + $data['data'][$d['inisial']][11]) /
                            ($data['data'][$d['inisial']][5] + $data['data'][$d['inisial']][10])) * 100) . ' %';
                else
                    $persen = '0 %';
                $data['data'][$d['inisial']][9] = $persen;
                if (!isset($data['data'][$d['inisial']]['jm'])) {
                    $data['data']['tot'][3] += $data['data'][$d['inisial']][3];
                    $data['data']['tot'][4] += $data['data'][$d['inisial']][4];
                    $data['data']['tot'][5] += $data['data'][$d['inisial']][5];
                    $data['data']['tot'][6] += $data['data'][$d['inisial']][6];
                    $data['data']['tot'][7] += $data['data'][$d['inisial']][7];
                    $data['data']['tot'][8] += $data['data'][$d['inisial']][8];
                    $data['data']['tot'][10] += $data['data'][$d['inisial']][10];
                    $data['data']['tot'][11] += $data['data'][$d['inisial']][11];

                    $data['data'][$d['inisial']]['jm'] = 1;
                }
                $i++;
            }
            if ($data['data']['tot'][5] > 0)
                $persentotal = round(($data['data']['tot'][8] / $data['data']['tot'][5]) * 100) . ' %';
            else
                $persentotal = '0 %';
            $data['data']['tot'][9] = $persentotal;
        } else {
            if ($fak != '') {
                $fak = explode(',', $fak);
            } else {
                $fak = [];
            }
            $fakultas = Fakultas::getFakultas($fak);
            foreach ($fakultas as $f) {
                $_POST['sql'] = ['substring(ps.kode,1,1)'=>$f['kode']];
                
                $jumS2pleno1 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>$_POST['jenisSidang']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                $jumS3pleno1 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>$_POST['jenisSidang']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();


                $jumS2seleksi = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where('pps.hasilKeputusan_id IS NOT NULL');},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>$_POST['jenisSidang']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $jumS3seleksi = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where('pps.hasilKeputusan_id IS NOT NULL');},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>$_POST['jenisSidang']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $data['data'][$i][1] = $i;
                $data['data'][$i][2] = $f['nama'] . ' (' . $f['inisial'] . ')';
                $data['data'][$i][3] = sizeof($jumS2pleno1);
                $data['data'][$i][4] = sizeof($jumS3pleno1);
                $data['data'][$i][5] = $data['data'][$i][3] + $data['data'][$i][4];
                $data['data'][$i][6] = sizeof($jumS2seleksi);
                $data['data'][$i][7] = sizeof($jumS3seleksi);
                $data['data'][$i][8] = $data['data'][$i][6] + $data['data'][$i][7];

                $limpahan = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql']);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>$_POST['jenisSidang']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $data['data'][$i][10] = sizeof($limpahan);

                $limpahan = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql']);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where('pps.hasilKeputusan_id IS NOT NULL');},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>$_POST['jenisSidang']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $data['data'][$i][11] = sizeof($limpahan);

                if ($data['data'][$i][5] != 0)
                    $data['data'][$i][9] = round((($data['data'][$i][8] + $data['data'][$i][11]) /
                            ($data['data'][$i][5] + $data['data'][$i][10])) * 100) . ' %';
                else
                    $data['data'][$i][9] = "0 %";

                $data['data']['tot'][3] += $data['data'][$i][3];
                $data['data']['tot'][4] += $data['data'][$i][4];
                $data['data']['tot'][5] += $data['data'][$i][5];
                $data['data']['tot'][6] += $data['data'][$i][6];
                $data['data']['tot'][7] += $data['data'][$i][7];
                $data['data']['tot'][8] += $data['data'][$i][8];
                $data['data']['tot'][10] += $data['data'][$i][10];
                $data['data']['tot'][11] += $data['data'][$i][11];
                //$data['data']['tot'][9] += $data['data'][$i][9];
                $i++;

            }if ($data['data']['tot'][5] != 0)
                $data['data']['tot'][9] = round(($data['data']['tot'][8] / $data['data']['tot'][5]) * 100) . ' %';
            else
                $data['data']['tot'][9] = "0 %";
        }
        return $data;
    }

    public static function getDataChart($datatable)
    {
        $i = 1;
        switch (Yii::$app->user->identity->levelAccess) {
            case 3:
                $name = 'Fakultas';
                break;
            case 4:
                $name = 'Program Studi';
                break;
            case 5:
                $name = 'per Program Studi';
                break;
            case 6:
                $name = 'per Program Studi';
                break;
            default:
                $name = '';
            
        }
        $data['s2']['data'][0][] = 'Jum';
        $data['s3']['data'][0][] = 'Jum';
        $data['s']['data'][0][] = 'Jum';
        
        $data['s2']['title'] = 'Pendaftar S2 ' . $name;
        $data['s3']['title'] = 'Pendaftar S3 ' . $name;
        $data['s']['title'] = 'Pendaftar ' . $name;
        $data['s2']['data'][0][] = $name;
        $data['s3']['data'][0][] = $name;
        $data['s']['data'][0][] = $name;
        
        foreach ($datatable['data'] as $idx => $dt) {
            if ($idx != 'tot') {
                $arrexplode = explode('(', $dt[2]);
                $nameitem = str_replace(')', '', $arrexplode[1]);
                
                $data['s2']['data'][$i][] = $nameitem;
                $data['s2']['data'][$i][] = $dt[6];
                
                $data['s3']['data'][$i][] = $nameitem;
                $data['s3']['data'][$i][] = $dt[7];
                
                $data['s']['data'][$i][] = $nameitem;
                $data['s']['data'][$i][] = $dt[8];
                $i++;
            }
        };
        $data['s2']['data'][$i][] = 'Belum Distatuskan';
        $data['s2']['data'][$i][] = $datatable['data']['tot'][3] - $datatable['data']['tot'][6];
        
        $data['s3']['data'][$i][] = 'Belum Distatuskan';
        $data['s3']['data'][$i][] = $datatable['data']['tot'][4] - $datatable['data']['tot'][7];
        
        $data['s']['data'][$i][] = 'Belum Distatuskan';
        $data['s']['data'][$i][] = $datatable['data']['tot'][5] - $datatable['data']['tot'][8];
        
        
        return $data;
    }

    public static function getDataTable1($fak = '', $level=0 , $url)
    {
        $_POST['url'] = $url;
        
        $i = 1;
        $data['data']['tot'][3] = 0;
        $data['data']['tot'][4] = 0;
        $data['data']['tot'][5] = 0;
        $data['data']['tot'][6] = 0;
        $data['data']['tot'][7] = 0;
        $data['data']['tot'][8] = 0;
        $data['data']['tot'][9] = 0;
        $data['data']['tot'][10] = 0;
        $data['data']['tot'][11] = 0;

        $paketPendaftaran = PaketPendaftaran::find()->where(['uniqueUrl'=>$url])->one();
        $jalurMasuk = isset($paketPendaftaran)? $paketPendaftaran->getJalurMasuk() : '';
        if ($level == 4 || $level == 5 || $level == 6) {
            $prodi = \app\models\ProgramStudi::getTblMayor($fak,$jalurMasuk);
            foreach ($prodi as $d) {
               
                $_POST['sql'] = ['ps.inisial'=>$d['inisial']];
                

                $jumS2pleno1 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                $jumS3pleno1 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();


                $jumS2pleno2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where('pps.hasilKeputusan_id IS NOT NULL');},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $jumS3pleno2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where('pps.hasilKeputusan_id IS NOT NULL');},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $data['data'][$d['inisial']][1] = $i;
                $data['data'][$d['inisial']][2] = $d['nama'] . ' (' . $d['inisial'] . ')';
                $data['data'][$d['inisial']][3] = sizeof($jumS2pleno1);
                $data['data'][$d['inisial']][4] = sizeof($jumS3pleno1);
                $data['data'][$d['inisial']][5] = $data['data'][$d['inisial']][3] + $data['data'][$d['inisial']][4];
                $data['data'][$d['inisial']][6] = sizeof($jumS2pleno2);
                $data['data'][$d['inisial']][7] = sizeof($jumS3pleno2);
                $data['data'][$d['inisial']][8] = $data['data'][$d['inisial']][6] + $data['data'][$d['inisial']][7];

                $limpahan = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql']);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $data['data'][$d['inisial']][10] = sizeof($limpahan);
    
                $limpahan = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql']);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where('pps.hasilKeputusan_id IS NOT NULL');},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                $data['data'][$d['inisial']][11] = sizeof($limpahan);

                if ($data['data'][$d['inisial']][5] > 0)
                    $persen = round((($data['data'][$d['inisial']][8] + $data['data'][$d['inisial']][11]) /
                            ($data['data'][$d['inisial']][5] + $data['data'][$d['inisial']][10])) * 100) . ' %';
                else
                    $persen = '0 %';
                $data['data'][$d['inisial']][9] = $persen;
                if (!isset($data['data'][$d['inisial']]['jm'])) {
                    $data['data']['tot'][3] += $data['data'][$d['inisial']][3];
                    $data['data']['tot'][4] += $data['data'][$d['inisial']][4];
                    $data['data']['tot'][5] += $data['data'][$d['inisial']][5];
                    $data['data']['tot'][6] += $data['data'][$d['inisial']][6];
                    $data['data']['tot'][7] += $data['data'][$d['inisial']][7];
                    $data['data']['tot'][8] += $data['data'][$d['inisial']][8];
                    $data['data']['tot'][10] += $data['data'][$d['inisial']][10];
                    $data['data']['tot'][11] += $data['data'][$d['inisial']][11];

                    $data['data'][$d['inisial']]['jm'] = 1;
                }
                $i++;
            }
            if ($data['data']['tot'][5] > 0)
                $persentotal = round(($data['data']['tot'][8] / $data['data']['tot'][5]) * 100) . ' %';
            else
                $persentotal = '0 %';
            $data['data']['tot'][9] = $persentotal;
        } else {
            if ($fak != '') {
                $fak = explode(',', $fak);
            } else {
                $fak = [];
            }
            $fakultas = Fakultas::getFakultas($fak);
            foreach ($fakultas as $f) {
                $_POST['sql'] = ['substring(ps.kode,1,1)'=>$f['kode']];
                

                $jumS2pleno1 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                $jumS3pleno1 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();


                $jumS2pleno2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where('pps.hasilKeputusan_id IS NOT NULL');},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $jumS3pleno2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where('pps.hasilKeputusan_id IS NOT NULL');},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $data['data'][$i][1] = $i;
                $data['data'][$i][2] = $f['nama'] . ' (' . $f['inisial'] . ')';
                $data['data'][$i][3] = sizeof($jumS2pleno1);
                $data['data'][$i][4] = sizeof($jumS3pleno1);
                $data['data'][$i][5] = $data['data'][$i][3] + $data['data'][$i][4];
                $data['data'][$i][6] = sizeof($jumS2pleno2);
                $data['data'][$i][7] = sizeof($jumS3pleno2);
                $data['data'][$i][8] = $data['data'][$i][6] + $data['data'][$i][7];

                $limpahan = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql']);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $data['data'][$i][10] = sizeof($limpahan);

                $limpahan = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql']);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where('pps.hasilKeputusan_id IS NOT NULL');},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $data['data'][$i][11] = sizeof($limpahan);

                if ($data['data'][$i][5] != 0)
                    $data['data'][$i][9] = round((($data['data'][$i][8] + $data['data'][$i][11]) /
                            ($data['data'][$i][5] + $data['data'][$i][10])) * 100) . ' %';
                else
                    $data['data'][$i][9] = "0 %";

                $data['data']['tot'][3] += $data['data'][$i][3];
                $data['data']['tot'][4] += $data['data'][$i][4];
                $data['data']['tot'][5] += $data['data'][$i][5];
                $data['data']['tot'][6] += $data['data'][$i][6];
                $data['data']['tot'][7] += $data['data'][$i][7];
                $data['data']['tot'][8] += $data['data'][$i][8];
                $data['data']['tot'][10] += $data['data'][$i][10];
                $data['data']['tot'][11] += $data['data'][$i][11];
                //$data['data']['tot'][9] += $data['data'][$i][9];
                $i++;

            }if ($data['data']['tot'][5] != 0)
                $data['data']['tot'][9] = round(($data['data']['tot'][8] / $data['data']['tot'][5]) * 100) . ' %';
            else
                $data['data']['tot'][9] = "0 %";
        }
        return $data;
    }

    public static function getDataTable2($fak = '', $level=0 , $url, $strata)
    {
        $_POST['url'] = $url;
        $_POST['strata'] = $strata;

        $i = 1;
        $data['data']['tot'][3] = 0;
        $data['data']['tot'][4] = 0;
        $data['data']['tot'][5] = 0;
        $data['data']['tot'][6] = 0;
        $data['data']['tot'][7] = 0;
        $data['data']['tot'][8] = 0;
        $data['data']['tot'][9] = 0;
        $data['data']['tot'][10] = 0;
        $data['data']['tot'][11] = 0;
        $data['data']['tot'][12] = 0;
        $data['data']['tot'][13] = 0;
        $data['data']['tot'][14] = 0;

        $paketPendaftaran = PaketPendaftaran::find()->where(['uniqueUrl'=>$url])->one();
        $jalurMasuk = isset($paketPendaftaran)? $paketPendaftaran->getJalurMasuk() : '';
        if ($level == 4 || $level == 5 || $level == 6) {
            $prodi = \app\models\ProgramStudi::getTblMayor($fak,$jalurMasuk);
            foreach ($prodi as $d) {
                $_POST['sql'] = ['ps.inisial'=>$d['inisial']];
                $jumS2pleno1 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>$_POST['strata']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $jumS2biasa = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>$_POST['strata']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where(['pps.hasilKeputusan_id'=>4]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                $jumS2percobaan = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>$_POST['strata']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where(['pps.hasilKeputusan_id'=>5]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                $jumS2tolak = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>$_POST['strata']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where(['pps.hasilKeputusan_id'=>6]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $limpahan = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>$_POST['strata']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                foreach ($limpahan as $item) {
                    $program = $item->pendaftaranHasProgramStudis[0];
                    $proses_sidang = $program->prosesSidangs;
                    foreach($proses_sidang as $p => $proses){
                      $sidang = Sidang::findOne($proses->sidang_id);
                      if($sidang->jenisSidang_id == 2){
                         $idhasil = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : null;
                      }
                      
                    }
                    if (!empty($idhasil)) {
                        if ($idhasil == 4){
                            if (isset($data['data'][$d['inisial']][12]))
                                $data['data'][$d['inisial']][12] += 1;
                            else
                                $data['data'][$d['inisial']][12] = 1;
                        }
                        if ($idhasil == 5){
                            if (isset($data['data'][$d['inisial']][13]))
                                $data['data'][$d['inisial']][13] += 1;
                            else
                                $data['data'][$d['inisial']][13] = 1;
                        }
                        if ($idhasil == 6){
                            if (isset($data['data'][$d['inisial']][14]))
                                $data['data'][$d['inisial']][14] += 1;
                            else
                                $data['data'][$d['inisial']][14] = 1;
                        }

                    }

                }
                if (!isset($data['data'][$d['inisial']][12]))
                    $data['data'][$d['inisial']][12] = 0;
                if (!isset($data['data'][$d['inisial']][13]))
                    $data['data'][$d['inisial']][13] = 0;
                if (!isset($data['data'][$d['inisial']][14]))
                    $data['data'][$d['inisial']][14] = 0;
                $data['data'][$d['inisial']][1] = $i;
                $data['data'][$d['inisial']][2] = $d['nama'] . ' (' . $d['inisial'] . ')';
                $data['data'][$d['inisial']][3] = sizeof($jumS2pleno1);
                $data['data'][$d['inisial']][4] = sizeof($jumS2biasa);
                $data['data'][$d['inisial']][6] = sizeof($jumS2percobaan);
                $data['data'][$d['inisial']][8] = sizeof($jumS2tolak);
                $data['data'][$d['inisial']][10] = $data['data'][$d['inisial']][4]
                                                  + $data['data'][$d['inisial']][6]
                                                  + $data['data'][$d['inisial']][8];

                if ($data['data'][$d['inisial']][10] > 0) {
                    $data['data'][$d['inisial']][5] = round(($data['data'][$d['inisial']][4] / $data['data'][$d['inisial']][10]) * 100) . ' %';
                    $data['data'][$d['inisial']][7] = round(($data['data'][$d['inisial']][6] / $data['data'][$d['inisial']][10]) * 100) . ' %';
                    $data['data'][$d['inisial']][9] = round(($data['data'][$d['inisial']][8] / $data['data'][$d['inisial']][10]) * 100) . ' %';
                } else {
                    $data['data'][$d['inisial']][5] = '0 %';
                    $data['data'][$d['inisial']][7] = '0 %';
                    $data['data'][$d['inisial']][9] = '0 %';
                }

                if ($data['data'][$d['inisial']][3] > 0)
                    $persen = round(($data['data'][$d['inisial']][10] / $data['data'][$d['inisial']][3]) * 100) . ' %';
                else
                    $persen = '0 %';
                $data['data'][$d['inisial']][11] = $persen;
                if (!isset($data['data'][$d['inisial']]['jm'])) {
                    $data['data']['tot'][3] += $data['data'][$d['inisial']][3];
                    $data['data']['tot'][4] += $data['data'][$d['inisial']][4];
                    $data['data']['tot'][6] += $data['data'][$d['inisial']][6];
                    $data['data']['tot'][8] += $data['data'][$d['inisial']][8];
                    $data['data']['tot'][10] += $data['data'][$d['inisial']][10];
                    $data['data']['tot'][12] += $data['data'][$d['inisial']][12];
                    $data['data']['tot'][13] += $data['data'][$d['inisial']][13];
                    $data['data']['tot'][14] += $data['data'][$d['inisial']][14];

                    $data['data'][$d['inisial']]['jm'] = 1;
                }
                $i++;
            }
            $data['data']['tot'][5] = '';
            $data['data']['tot'][7] = '';
            $data['data']['tot'][9] = '';
            if ($data['data']['tot'][3] > 0)
                $persentotal = round(($data['data']['tot'][10] / $data['data']['tot'][3]) * 100) . ' %';
            else
                $persentotal = '0 %';
            $data['data']['tot'][11] = $persentotal;
        } else {
            if ($fak != '') {
                $fak = explode(',', $fak);
            } else {
                $fak = [];
            }
            $fakultas = Fakultas::getFakultas($fak);
            foreach ($fakultas as $f) {
                $_POST['sql'] = ['substring(ps.kode,1,1)'=>$f['kode']];
                $jumS2pleno1 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>$_POST['strata']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $jumS2biasa = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>$_POST['strata']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where(['pps.hasilKeputusan_id'=>4]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                $jumS2percobaan = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>$_POST['strata']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where(['pps.hasilKeputusan_id'=>5]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                $jumS2tolak = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>$_POST['strata']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where(['pps.hasilKeputusan_id'=>6]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                $limpahan = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>$_POST['strata']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                foreach ($limpahan as $item) {
                    $program = $item->pendaftaranHasProgramStudis[0];
                    $proses_sidang = $program->prosesSidangs;
                    foreach($proses_sidang as $p => $proses){
                      $sidang = Sidang::findOne($proses->sidang_id);
                      if($sidang->jenisSidang_id == 2){
                         $idhasil = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : null;
                      }
                      
                    }
                    if (!empty($idhasil)) {
                        if ($idhasil == 4){
                            if (isset($data['data'][$i][12]))
                                $data['data'][$i][12] += 1;
                            else
                                $data['data'][$i][12] = 1;
                        }
                        if ($idhasil == 5){
                            if (isset($data['data'][$i][13]))
                                $data['data'][$i][13] += 1;
                            else
                                $data['data'][$i][13] = 1;
                        }
                        if ($idhasil == 6){
                            if (isset($data['data'][$i][14]))
                                $data['data'][$i][14] += 1;
                            else
                                $data['data'][$i][14] = 1;
                        }

                    }

                }

                if (!isset($data['data'][$i][12]))
                    $data['data'][$i][12] = 0;
                if (!isset($data['data'][$i][13]))
                    $data['data'][$i][13] = 0;
                if (!isset($data['data'][$i][14]))
                    $data['data'][$i][14] = 0;
                $data['data'][$i][1] = $i;
                $data['data'][$i][2] = $f['nama'] . ' (' . $f['inisial'] . ')';
                $data['data'][$i][3] = sizeof($jumS2pleno1);
                $data['data'][$i][4] = sizeof($jumS2biasa);
                $data['data'][$i][6] = sizeof($jumS2percobaan);
                $data['data'][$i][8] = sizeof($jumS2tolak);
                $data['data'][$i][10] = $data['data'][$i][4]
                    + $data['data'][$i][6]
                    + $data['data'][$i][8];
                if ($data['data'][$i][10] > 0) {
                    $data['data'][$i][5] = round(($data['data'][$i][4] / $data['data'][$i][10]) * 100) . ' %';
                    $data['data'][$i][7] = round(($data['data'][$i][6] / $data['data'][$i][10]) * 100) . ' %';
                    $data['data'][$i][9] = round(($data['data'][$i][8] / $data['data'][$i][10]) * 100) . ' %';
                } else {
                    $data['data'][$i][5] = '0 %';
                    $data['data'][$i][7] = '0 %';
                    $data['data'][$i][9] = '0 %';
                }

                if ($data['data'][$i][3] > 0)
                    $persen = round(($data['data'][$i][10] / $data['data'][$i][3]) * 100) . ' %';
                else
                    $persen = '0 %';
                $data['data'][$i][11] = $persen;
                if (!isset($data['data'][$i]['jm'])) {
                    $data['data']['tot'][3] += $data['data'][$i][3];
                    $data['data']['tot'][4] += $data['data'][$i][4];
                    $data['data']['tot'][6] += $data['data'][$i][6];
                    $data['data']['tot'][8] += $data['data'][$i][8];
                    $data['data']['tot'][10] += $data['data'][$i][10];
                    $data['data']['tot'][12] += $data['data'][$i][12];
                    $data['data']['tot'][13] += $data['data'][$i][13];
                    $data['data']['tot'][14] += $data['data'][$i][14];

                    $data['data'][$i]['jm'] = 1;
                }
                $i++;
            }
            $data['data']['tot'][5] = '';
            $data['data']['tot'][7] = '';
            $data['data']['tot'][9] = '';
            if ($data['data']['tot'][3] > 0)
                $persentotal = round(($data['data']['tot'][10] / $data['data']['tot'][3]) * 100) . ' %';
            else
                $persentotal = '0 %';
            $data['data']['tot'][11] = $persentotal;
        }
        return $data;
    }
}