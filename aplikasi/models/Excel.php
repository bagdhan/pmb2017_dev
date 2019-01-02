<?php

namespace app\models;

use Yii;
use \yii\base\Model;
use app\models\Pendaftaran;
use app\models\Fakultas;
use app\models\NrpGenerator;
use app\modelsDB\UserHasDepartemen;
use app\modelsDB\UserHasFakultas;
use app\modelsDB\Sidang;
use app\modelsDB\UserHasProgramStudi;

use PHPExcel;

/**
 * @author doni47
 * @copyright 2017
 * 
 * This model use for every data
 */

class Excel extends Model
{  
    public function create()
    {
        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
        // Create new PHPExcel object
        $mssg =  date('H:i:s') . " Create new PHPExcel object" . EOL;
        $objPHPExcel = new PHPExcel;
        
        // Set document properties
        $mssg .=  date('H:i:s') . " Set document properties" . EOL;
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        							 ->setLastModifiedBy("Maarten Balliauw")
        							 ->setTitle("PHPExcel Test Document")
        							 ->setSubject("PHPExcel Test Document")
        							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
        							 ->setKeywords("office PHPExcel php")
        							 ->setCategory("Test result file");
        
        
        // Add some data
        $mssg .=  date('H:i:s') . " Add some data" . EOL;
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Hello')
                    ->setCellValue('B2', 'world!')
                    ->setCellValue('C1', 'Hello')
                    ->setCellValue('D2', 'world!');
        
        // Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'Miscellaneous glyphs')
                    ->setCellValue('A5', '�����������������');
        
        
        $objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
        $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);
        
        
        $value = "-ValueA\n-Value B\n-Value C";
        $objPHPExcel->getActiveSheet()->setCellValue('A10', $value);
        $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
        $objPHPExcel->getActiveSheet()->getStyle('A10')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('A10')->setQuotePrefix(true);
        
        
        
        // Rename worksheet
        $mssg .=  date('H:i:s') . " Rename worksheet" . EOL;
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        
        // Save Excel 2007 file
        $mssg .=  date('H:i:s') . " Write to Excel2007 format" . EOL;
        $callStartTime = microtime(true);
        
        $IOF = $objPHPExcel->getIOFactory();
        $objWriter = $IOF->createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
        $callEndTime = microtime(true);
        $callTime = $callEndTime - $callStartTime;
        
        $mssg .=  date('H:i:s') . " File written to " ;
        $mssg .=  'Call time to write Workbook was ' . sprintf('%.4f',$callTime) . " seconds" . EOL;
        // $mssg .=  memory usage
        $mssg .=  date('H:i:s') . ' Current memory usage: ' . (memory_get_usage(true) / 1024 / 1024) . " MB" . EOL;
        
        
        // Save Excel 95 file
        $mssg .=  date('H:i:s') . " Write to Excel5 format" . EOL;
        $callStartTime = microtime(true);
        
        $objWriter = $IOF->createWriter($objPHPExcel, 'Excel5');
        $objWriter->save(str_replace('.php', '.xls', __FILE__));
                
    }
    
    public function render($dir,$filename)
    {
        /**
         * PHPExcel
         *
         * Copyright (c) 2006 - 2015 PHPExcel
         *
         * This library is free software; you can redistribute it and/or
         * modify it under the terms of the GNU Lesser General Public
         * License as published by the Free Software Foundation; either
         * version 2.1 of the License, or (at your option) any later version.
         *
         * This library is distributed in the hope that it will be useful,
         * but WITHOUT ANY WARRANTY; without even the implied warranty of
         * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
         * Lesser General Public License for more details.
         *
         * You should have received a copy of the GNU Lesser General Public
         * License along with this library; if not, write to the Free Software
         * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
         *
         * @category   PHPExcel
         * @package    PHPExcel
         * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
         * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
         * @version    ##VERSION##, ##DATE##
         */
        
        
        
        if (!file_exists($dir.$filename)) {
        	exit("template tidak ditemukan" );
        }
        
        
        $callStartTime = microtime(true);
        
        $objPHPExcel = new PHPExcel;
        
        $IOF = $objPHPExcel->getIOFactory();
        
        $objPHPExcel = $IOF->load($dir.$filename);
        
        $callEndTime = microtime(true);
        $callTime = $callEndTime - $callStartTime;
        
        $callStartTime = microtime(true);
        
        //$objWriter = $IOF->createWriter($objPHPExcel, 'Excel2007');
//        $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
        
        $callEndTime = microtime(true);
        $callTime = $callEndTime - $callStartTime;
        
        
        return $objPHPExcel;
    }

    public function upfistarray($input){
        $pca = explode(' ', $input);
        $g='';
        foreach ($pca as $p)
            $g[]= ucfirst(strtolower($p));
        return join(' ',$g);
    } 
    
    
    public function getExcelBackup($fak='',$periode='',$tahun='')
    {
        $_POST['periode'] = $periode;
        $_POST['tahun'] = $tahun;
        $dirTemp = Yii::getAlias('@app').'/arsip/template/';
        $filename = 'tempbackup.xlsx';
        $objPHPExcel = $this->render($dirTemp,$filename);
        if($fak != 'Belum'){
          $fakultas = Fakultas::getFakultas($fak);
          
          foreach ($fakultas as $f){
              $_POST['fak'] = $f['kode'];
              if($periode != 'Menunda'){
                $data = Pendaftaran::find()
                                  ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                              'paketPendaftaran a'=> function($q){$q->where(['a.title'=>$_POST['periode'],'a.tahun'=>$_POST['tahun']]);},
                                              'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                              'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where(['substring(ps.kode,1,1)'=>$_POST['fak']]);}]
                                              )
                                  ->orderBy('p.noPendaftaran ASC')->all();
              // $data = ModelData::getBackup( " and substring(p1.kode,1,1) = '$f[kode]' and pin.dateverifikasi >= '$mulai' and pin.dateverifikasi <= '$selesai'");
              $objWorkSheet1 = $this->inputArrayToBackup($objPHPExcel,$data,7);
              }else{
                $_POST['tahun'] = $tahun-1;
                $data = Pendaftaran::find()
                                  ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                              'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=>$_POST['tahun']]);},
                                              'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);}, // menunda di pilihan pertama
                                              'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where(['substring(ps.kode,1,1)'=>$_POST['fak']]);},
                                              'pendaftaranHasProgramStudis.prosesSidangs pps',
                                              'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                              'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.statusMasuk_id'=>5]);}]
                                              )
                                  ->orderBy('p.noPendaftaran ASC')->all();

                $data1 = Pendaftaran::find()
                                  ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                              'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=>$_POST['tahun']]);},
                                              'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);}, // menunda di pilihan kedua
                                              'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where(['substring(ps.kode,1,1)'=>$_POST['fak']]);},
                                              'pendaftaranHasProgramStudis.prosesSidangs pps',
                                              'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                              'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.statusMasuk_id'=>5]);}]
                                              )
                                  ->orderBy('p.noPendaftaran ASC')->all();
              $objWorkSheet1 = $this->inputArrayToBackup($objPHPExcel,$data,7,$data1);
              }
              
              $objWorkSheet1  ->setCellValue('A2', strtoupper($f['nama']));
              $objWorkSheet1->setTitle('backup data '.$f['inisial']);
              $objPHPExcel->addSheet($objWorkSheet1);
          }
        }else{
          $data = Pendaftaran::find()
                                  ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                              'paketPendaftaran a'=> function($q){$q->where(['a.title'=>$_POST['periode'],'a.tahun'=>$_POST['tahun']]);},
                                              'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.id'=>null]);}]
                                              )
                                  ->orderBy('p.noPendaftaran ASC')->all();

              $objWorkSheet1 = $this->inputArrayToBackup($objPHPExcel,$data,7);
              $objWorkSheet1  ->setCellValue('A2', strtoupper('Belum Pilih Program Studi'));
              $objWorkSheet1->setTitle('backup data pelamar no prodi');
              $objPHPExcel->addSheet($objWorkSheet1);
        }
        
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->removeSheetByIndex(0);
        return $objPHPExcel;
    }
  
    public function inputArrayToBackup($objPHPExcel,$arr,$starRow,$arr2='')
    {   
        $row = $starRow;
        $count = sizeof($arr);
        ini_set('max_execution_time', 300);
        $obj = $objPHPExcel->getSheet(0);
        $objWorkSheet1 = clone $obj;
        $objWorkSheet1->setCellValue('DE'.($row-1), 'Tahapan');
        $objWorkSheet1 = $this->objBackup($objWorkSheet1,$arr,$row);
        if($arr2 != ''){
          $row += $count;
          $objWorkSheet1 = $this->objBackup($objWorkSheet1,$arr2,$row,$count);
        }

        
                   
        $objWorkSheet1->removeRow($starRow-2,2);
        return $objWorkSheet1;
    }

    public function objBackup($objWorkSheet1,$arr,$row,$no='')
    {
      $no = ($no!='')? $no : 0;
      foreach($arr as $r => $dataRow) {

        if(isset($dataRow->orang->pekerjaans[0])){
          $pekerjaan = $dataRow->orang->pekerjaans[0];
          $jalan = $desa = $kecamatan = $kabkota = $propinsi = $kodepos = '';
          if(isset($pekerjaan->instansis[0])){
          $instansi = $pekerjaan->instansis[0];
          
          foreach($instansi->alamats as $alamat):
          
          if(empty($alamat)){

            $jalan = $alamat->jalan;
            $desa = $alamat->desaKelurahanKode->namaID;
            $kecamatan = $alamat->desaKelurahanKode->kecamatanKode->namaID;
            $kabkota = $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID;
            $propinsi = $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID;
            $kodepos = $alamat->kodePos;
          } endforeach;

        }
      }


          $pendidikan = []; $i = 1; 
          foreach($dataRow['orang']['pendidikans'] as $value){

            $pendidikan[$i] = $value;
            $i++;
          }

          $kontak = []; $kontak[1] = $kontak[2] = $kontak[3]= ''; 
          foreach($dataRow['orang']['kontaks'] as $value){
            $kontak[$value->jenisKontak_id] = $value->kontak;
          }

          $gelar = []; $gelar[0] = $gelar[1] = ''; 
          foreach($dataRow['orang']['gelars'] as $value){
            $gelar[$value->depanBelakang] = $value->nama;
          }

          $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
            foreach($dataRow['pendaftaranHasProgramStudis'] as $value){
                $pilihan[$i] = $value->programStudi->kode;
                $mayor[$i] = $value->programStudi->nama;
                $inisial[$i] = $value->programStudi->inisial;
                $strata = $value->programStudi->strata;
                $i++;
            }

          $syarat = []; 
            foreach($dataRow['syaratTambahans'] as $value){
              $syarat[$value->jenisSyaratTambahan->title]=$value;
            }

          $rekomendasi=[]; $rekomendasi[0]=$rekomendasi[1]=$rekomendasi[2]=$rekomendasi[3]=''; 
          $i = 1; foreach($dataRow['pemberiRekomendasis'] as $value){
                    $rekomendasi[$i] = $value->nama;
                      $rekomendasi[0] = 'Ya';
                      $i++;
                  }

          if($dataRow['verifikasiPMB']==0) $pleno = 'Belum Pilih'; 
          elseif($dataRow['verifikasiPMB'] ==1 && $dataRow->paketPendaftaran->title == 'Tahap 1') $pleno = 'Tahap 1'; 
          elseif($dataRow['verifikasiPMB'] ==1 && $dataRow->paketPendaftaran->title == 'Tahap 2') $pleno = 'Tahap 2'; 
          elseif($dataRow['verifikasiPMB'] ==1 && $dataRow->paketPendaftaran->title == 'Kelas Khusus') $pleno = 'Kelas Khusus';
		  elseif($dataRow['verifikasiPMB'] ==1 && $dataRow->paketPendaftaran->title == 'Kelas by Research') $pleno = 'Kelas by Research';
          $sidang = ($dataRow->verifikasiPMB == 1 && isset($dataRow->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title))? $dataRow->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title : '';

          $cek_berkas = $dataRow->verifberkas();
          if($cek_berkas) $berkas = '1'; else $berkas = '0';

        
        $objWorkSheet1->insertNewRowBefore($row,1);

    
            $objWorkSheet1->setCellValue('A'.$row, $no+=1)
                                          ->setCellValue('B'.$row, @$dataRow['noPendaftaran'])
                                          ->setCellValue('C'.$row, @$dataRow['orang']['nama'])
                                          ->setCellValue('D'.$row, @$gelar[0])
                                          ->setCellValue('E'.$row, @$gelar[1])
                                          ->setCellValue('F'.$row, @$dataRow['orang']['tempatLahir'])
                                          ->setCellValue('G'.$row, @$dataRow['orang']['tanggalLahir'])
										  ->setCellValue('H'.$row, @$dataRow['orang']['usia'])
                                          ->setCellValue('I'.$row, @$dataRow['orang']['negara']['nama'])
                                          ->setCellValue('J'.$row, (@$dataRow['orang']['jenisKelamin']==1)? 'Laki-laki':'Perempuan')
                                          ->setCellValue('K'.$row, @$dataRow['orang']['statusPerkawinan']['status'])
                                          ->setCellValue('L'.$row, @$dataRow['orang']['keamanaans'][0]['namaGadisIbu'])
                                          ->setCellValue('M'.$row, @$dataRow['orang']['alamats'][0]['jalan'])
                                          ->setCellValue('N'.$row, @$dataRow['orang']['alamats'][0]['rw'])
                                          ->setCellValue('O'.$row, @$dataRow['orang']['alamats'][0]['rt'])
                                          ->setCellValue('P'.$row, @$dataRow['orang']['alamats'][0]['desaKelurahanKode']['namaID'])
                                          ->setCellValue('Q'.$row, @$dataRow['orang']['alamats'][0]['desaKelurahanKode']['kecamatanKode']['namaID'])
                                          ->setCellValue('R'.$row, @$dataRow['orang']['alamats'][0]['desaKelurahanKode']['kecamatanKode']['kabupatenKotaKode']['namaID'])
                                          ->setCellValue('S'.$row, @$dataRow['orang']['alamats'][0]['desaKelurahanKode']['kecamatanKode']['kabupatenKotaKode']['propinsiKode']['namaID'])
                                          ->setCellValue('T'.$row, @$dataRow['orang']['negara']['nama'])
                                          ->setCellValue('U'.$row, @$dataRow['orang']['identitas'][0]['identitas'])
                                          ->setCellValue('V'.$row, @$kontak[3]) // phone
                                          ->setCellValue('W'.$row, '')
                                          ->setCellValue('X'.$row, @$kontak[2]) // mobilephone
                                          ->setCellValue('Y'.$row, @$dataRow['orang']['alamats'][0]['kodePos'])
                                          ->setCellValue('Z'.$row, @$kontak[1]) // email
                                          // ->setCellValue('Z'.$row, 'emailalternatif'])
                                          ->setCellValue('AB'.$row, @$pekerjaan->jenisInstansi->nama)
                                          // ->setCellValue('AB'.$row, $dataRow['pekerjaanlainnya'])
                                          ->setCellValue('AD'.$row, @$pekerjaan->noIdentitas)
                                          ->setCellValue('AE'.$row, @$instansi->nama)
                                          ->setCellValue('AF'.$row, '')
                                          ->setCellValue('AG'.$row, @$jalan)
                                          ->setCellValue('AH'.$row, @$desa)
                                          ->setCellValue('AI'.$row, @$kecamatan)
                                          ->setCellValue('AJ'.$row, @$kabkota)
                                          ->setCellValue('AK'.$row, @$propinsi)
                                          ->setCellValue('AL'.$row, @$instansi->tlp)
                                          ->setCellValue('AM'.$row, @$instansi->fax)
                                          ->setCellValue('AN'.$row, @$kodepos)
                                          ->setCellValue('AO'.$row, isset($pendidikan[1]->tanggalLulus)? date('d-m-Y', strtotime($pendidikan[1]->tanggalLulus)) : '')
                                          ->setCellValue('AP'.$row, @$pendidikan[1]->institusi->nama)
                                          ->setCellValue('AQ'.$row, @$pendidikan[1]->fakultas)
                                          ->setCellValue('AR'.$row, @$pendidikan[1]->programStudi)
                                          ->setCellValue('AS'.$row, '')
                                          ->setCellValue('AT'.$row, '')
                                          ->setCellValue('AU'.$row, '')
                                          ->setCellValue('AV'.$row, '')
                                          ->setCellValue('AW'.$row, '')
                                          ->setCellValue('AX'.$row, @$pendidikan[1]->akreditasi)
                                          ->setCellValue('AY'.$row, @$pendidikan[1]->ipk)
                                          ->setCellValue('AZ'.$row, @$pendidikan[1]->ipkAsal)
                                          ->setCellValue('BA'.$row, @$pendidikan[1]->jumlahSKS)
                                          ->setCellValue('BB'.$row, @$pendidikan[1]->gelar)
                                          ->setCellValue('BC'.$row, '')
                                          ->setCellValue('BD'.$row, isset($pendidikan[2]->tanggalLulus)? date('d-m-Y', strtotime($pendidikan[2]->tanggalLulus)) : '')
                                          ->setCellValue('BE'.$row, @$pendidikan[2]->institusi->nama)
										  ->setCellValue('BF'.$row, @$pendidikan[2]->fakultas)
                                          // ->setCellValue('BE'.$row, $dataRow['s2fakultas'])
                                          ->setCellValue('BG'.$row, @$pendidikan[2]->programStudi)
                                          // ->setCellValue('BG'.$row, $dataRow['s2alamat'])
                                          // ->setCellValue('BH'.$row, $dataRow['s2jalan'])
                                          // ->setCellValue('BI'.$row, $dataRow['s2kota'])
                                          // ->setCellValue('BJ'.$row, $dataRow['s2propinsi'])
                                          // ->setCellValue('BK'.$row, $dataRow['s2negara'])
                                          ->setCellValue('BM'.$row, @$pendidikan[2]->akreditasi)
                                          ->setCellValue('BN'.$row, @$pendidikan[2]->ipk)
                                          ->setCellValue('BO'.$row, @$pendidikan[2]->ipkAsal)
                                          ->setCellValue('BP'.$row, @$pendidikan[2]->jumlahSKS)
                                          ->setCellValue('BQ'.$row, @$pendidikan[2]->gelar)
                                          ->setCellValue('BR'.$row, @$strata)
                                          // ->setCellValue('BR'.$row, $dataRow['strata_pend'])
                                          ->setCellValue('BS'.$row, @$pilihan[1])
                                          ->setCellValue('BT'.$row, @$pilihan[2])
                                          // ->setCellValue('BU'.$row, $dataRow['prog_prof'])
                                          // ->setCellValue('BV'.$row, $dataRow['daftar_sps'])
                                          // ->setCellValue('BW'.$row, $dataRow['tahun_daftar_sps'])
                                          // ->setCellValue('BX'.$row, $dataRow['beasiswa'])
                                          ->setCellValue('BZ'.$row, @$dataRow['rencanaPembiayaan']['jenisPembiayan']['title'])
                                          ->setCellValue('CA'.$row, @$dataRow['rencanaPembiayaan']['deskripsi'])
                                          // ->setCellValue('CA'.$row, $dataRow['kirimbpps'])
                                          // ->setCellValue('CB'.$row, $dataRow['pernyataan_biaya'])
                                          ->setCellValue('CD'.$row, @$syarat['TOEFL']->score)
                                          ->setCellValue('CE'.$row, isset($syarat['TOEFL']->dateExercise)? date('d-m-Y', strtotime($syarat['TOEFL']->dateExercise)) : '')
                                          ->setCellValue('CF'.$row, @$syarat['TOEFL']->organizer)
                                          ->setCellValue('CG'.$row, @$syarat['TPA']->score)
                                          ->setCellValue('CH'.$row, isset($syarat['TPA']->dateExercise)? date('d-m-Y', strtotime($syarat['TPA']->dateExercise)) : '')
                                          ->setCellValue('CI'.$row, @$syarat['TPA']->organizer)
                                          ->setCellValue('CJ'.$row, @$dataRow['karyaIlmiahs'][0]['jurnalInternasional'])
                                          ->setCellValue('CK'.$row, @$dataRow['karyaIlmiahs'][0]['jurnalNasionalAkreditasi'])
                                          ->setCellValue('CL'.$row, @$dataRow['karyaIlmiahs'][0]['jurnalNasionalTakAkreditasi'])
                                          ->setCellValue('CM'.$row, @$dataRow['karyaIlmiahs'][0]['belum'])
                                          ->setCellValue('CN'.$row, @$rekomendasi[0])
                                          ->setCellValue('CO'.$row, @$rekomendasi[1])
                                          ->setCellValue('CP'.$row, @$rekomendasi[2])
                                          ->setCellValue('CQ'.$row, @$rekomendasi[3])
                                          // ->setCellValue('CQ'.$row, $dataRow['sinopsis'])
                                          ->setCellValue('CS'.$row, @$pendidikan[1]->judulTA)
                                          ->setCellValue('CT'.$row, isset($pendidikan[2])? $pendidikan[2]->judulTA : '')
                                          ->setCellValue('CU'.$row, @$dataRow['setujuSyarat'])
                                          ->setCellValue('CV'.$row, @$dataRow['pinVerifikasis'][0]['status'])
                                          ->setCellValue('CW'.$row, @$pekerjaan->jenisInstansi->nama)
                                          ->setCellValue('CX'.$row, @$mayor[1]) //nama
                                          ->setCellValue('CY'.$row, @$inisial[1]) 
                                          ->setCellValue('CZ'.$row, @$mayor[2])
                                          ->setCellValue('DA'.$row, @$inisial[2])
                                          // ->setCellValue('DA'.$row, $dataRow['setuju'])
                                          ->setCellValue('DC'.$row, @$pleno)
                                          ->setCellValue('DD'.$row, @$berkas)
                                          ->setCellValue('DE'.$row, '') // aktifdo
                                          ->setCellValue('DF'.$row, @$sidang); // sidang pleno 1
            $row++;
        }

        return $objWorkSheet1;
    }

    public function getExcelBahanPleno($fak='', $tahun, $tahap,$url)
    {
        $_POST['url'] = $url;
        $jab=1;
        $dirTemp = Yii::getAlias('@app').'/arsip/template/';
        $filename = 'temp1.xlsx';
        $objPHPExcel = $this->render($dirTemp,$filename);
        $fakultas = Fakultas::getFakultas($fak);
        if($tahap == '1'){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO TAHAP I';
        }elseif($tahap == '2'){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO TAHAP II';
        }elseif($tahap == "s"){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO Kelas Khusus';
        }else{
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO Kelas by Research';
        }
        $ttd = Fakultas::getTandatangan($fak,$jab);
        foreach ($fakultas as $f){

              $accessRule = Yii::$app->user->identity->accessRole_id;
              if($accessRule < 4 || $accessRule == 8){
                  $_POST['filter'] = 'substring(ps.kode,1,1)';
                  $_POST['kode'] = $f['kode'];
              }elseif($accessRule == 4){
                  $ch = UserHasFakultas::findOne(['user_id' => Yii::$app->user->id]);
                  $_POST['filter'] = 'substring(ps.kode,1,1)';
                  $_POST['kode'] = $ch->fakultas->kode;
              }elseif($accessRule == 5){
                  $ch = UserHasDepartemen::findOne(['user_id' => Yii::$app->user->id]);
                  $_POST['filter'] = 'substring(ps.kode,1,2)';
                  $_POST['kode'] = $ch->departemen->kode;
              }elseif($accessRule == 6){
                  $ch = UserHasProgramStudi::findOne(['user_id' => Yii::$app->user->id]);
                  $_POST['filter'] = 'ps.kode';
                  $_POST['kode'] = $ch->programStudi->kode;  
              }

              $data = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>1]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

              $data_m2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=>(date('Y')-1)]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.statusMasuk_id'=>5]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
              $data_m2 = ($tahap== '1')? $data_m2 : '';


            $objWorkSheet1 = $this->inputArrayToBahanPleno($objPHPExcel,$data,9,2,$ttd,$f['inisial'],$data_m2,0);
            $objWorkSheet1  ->setCellValue('A1', strtoupper($title));
            $objWorkSheet1  ->setCellValue('A2', strtoupper('SELEKSI CALON MAHASISWA BARU PROGRAM MAGISTER SAINS (S2) SEKOLAH PASCASARJANA IPB SEMESTER GANJIL TAHUN AKADEMIK '.date('Y').'/'.(date('Y')+1)));
            $objWorkSheet1  ->setCellValue('A3', strtoupper($f['nama']));
//                            ->setCellValue('B'.$row, $dataRow['nopendaftaran'])
//                            ->setCellValue('C'.$row, 'a');
            $objWorkSheet1->setTitle($f['inisial'].'_S2');
            $objPHPExcel->addSheet($objWorkSheet1);
            $data2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>1]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

            $data2_m3 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=>(date('Y')-1)]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.statusMasuk_id'=>5]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
            $data2_m3 = ($tahap == '1')? $data2_m3 : '';
       
            $objWorkSheet1 = $this->inputArrayToBahanPleno($objPHPExcel,$data2,9,3,$ttd,$f['inisial'],$data2_m3,0);
            $objWorkSheet1  ->setCellValue('A1', strtoupper($title));
            $objWorkSheet1  ->setCellValue('A2', strtoupper('SELEKSI CALON MAHASISWA BARU PROGRAM DOKTOR (S3) SEKOLAH PASCASARJANA IPB SEMESTER GANJIL TAHUN AKADEMIK '.date('Y').'/'.(date('Y')+1)));
            $objWorkSheet1  ->setCellValue('A3', strtoupper($f['nama']));
            $objWorkSheet1->setTitle($f['inisial'].'_S3');
            $objPHPExcel->addSheet($objWorkSheet1);        
        }
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->removeSheetByIndex(0);
        return $objPHPExcel;
    }

    public function getExcelBAPleno($fak='', $jab, $tahun, $tahap, $url)
    {
        $_POST['url'] = $url;
        $dirTemp = Yii::getAlias('@app').'/arsip/template/';
        $filename = 'temp1.xlsx';
        $objPHPExcel = $this->render($dirTemp,$filename);
        $fakultas = Fakultas::getFakultas($fak);
        if($tahap == '1'){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO TAHAP I';
        }elseif($tahap == '2'){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO TAHAP II';
        }elseif($tahap == "s"){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO Kelas Khusus';
        }else{
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO Kelas by Research';
        }
        $ttd = Fakultas::getTandatangan($fak,$jab);
        foreach ($fakultas as $f){

              $accessRule = Yii::$app->user->identity->accessRole_id;
              if($accessRule < 4 || $accessRule == 8){
                  $_POST['filter'] = 'substring(ps.kode,1,1)';
                  $_POST['kode'] = $f['kode'];
              }elseif($accessRule == 4){
                  $ch = UserHasFakultas::findOne(['user_id' => Yii::$app->user->id]);
                  $_POST['filter'] = 'substring(ps.kode,1,1)';
                  $_POST['kode'] = $ch->fakultas->kode;
              }elseif($accessRule == 5){
                  $ch = UserHasDepartemen::findOne(['user_id' => Yii::$app->user->id]);
                  $_POST['filter'] = 'substring(ps.kode,1,2)';
                  $_POST['kode'] = $ch->departemen->kode;
              }elseif($accessRule == 6){
                  $ch = UserHasProgramStudi::findOne(['user_id' => Yii::$app->user->id]);
                  $_POST['filter'] = 'ps.kode';
                  $_POST['kode'] = $ch->programStudi->kode;  
              }

              $data = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>1]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

              $data_m2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=>(date('Y')-1)]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.statusMasuk_id'=>5]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
              $data_m2 = ($tahap == '1')? $data_m2 : '';


            $objWorkSheet1 = $this->inputArrayToBahanPleno($objPHPExcel,$data,9,2,$ttd,$f['inisial'],$data_m2,1);
            $objWorkSheet1  ->setCellValue('A1', strtoupper($title));
            $objWorkSheet1  ->setCellValue('A2', strtoupper('SELEKSI CALON MAHASISWA BARU PROGRAM MAGISTER SAINS (S2) SEKOLAH PASCASARJANA IPB SEMESTER GANJIL TAHUN AKADEMIK '.date('Y').'/'.(date('Y')+1)));
            $objWorkSheet1  ->setCellValue('A3', strtoupper($f['nama']));
//                            ->setCellValue('B'.$row, $dataRow['nopendaftaran'])
//                            ->setCellValue('C'.$row, 'a');
            $objWorkSheet1->setTitle($f['inisial'].'_S2');
            $objPHPExcel->addSheet($objWorkSheet1);
            $data2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>1]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

            $data2_m3 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=>(date('Y')-1)]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.statusMasuk_id'=>5]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
            $data2_m3 = ($tahap == '1')? $data2_m3 : '';
       
            $objWorkSheet1 = $this->inputArrayToBahanPleno($objPHPExcel,$data2,9,3,$ttd,$f['inisial'],$data2_m3,1);
            $objWorkSheet1  ->setCellValue('A1', strtoupper($title));
            $objWorkSheet1  ->setCellValue('A2', strtoupper('SELEKSI CALON MAHASISWA BARU PROGRAM DOKTOR (S3) SEKOLAH PASCASARJANA IPB SEMESTER GANJIL TAHUN AKADEMIK '.date('Y').'/'.(date('Y')+1)));
            $objWorkSheet1  ->setCellValue('A3', strtoupper($f['nama']));
            $objWorkSheet1->setTitle($f['inisial'].'_S3');
            $objPHPExcel->addSheet($objWorkSheet1);        
        }
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->removeSheetByIndex(0);
        return $objPHPExcel;
    }

    public function getExcelBahanPleno2($fak='', $tahun, $tahap,$url)
    {
        $_POST['url'] = $url;
        $jab=1;
        $dirTemp = Yii::getAlias('@app').'/arsip/template/';
        $filename = 'temp3.xlsx';
        $objPHPExcel = $this->render($dirTemp,$filename);
        $fakultas = Fakultas::getFakultas($fak);
        if($tahap == '1'){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO 2 TAHAP I';
        }elseif($tahap == '2'){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO 2 TAHAP II';
        }elseif($tahap == "s"){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO Kelas Khusus';
        }else{
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO Kelas by Research';
        }
        $ttd = Fakultas::getTandatangan($fak,$jab);
        foreach ($fakultas as $f){

              $accessRule = Yii::$app->user->identity->accessRole_id;
              if($accessRule < 4 || $accessRule == 8){
                  $_POST['filter'] = 'substring(ps.kode,1,1)';
                  $_POST['kode'] = $f['kode'];
              }elseif($accessRule == 4){
                  $ch = UserHasFakultas::findOne(['user_id' => Yii::$app->user->id]);
                  $_POST['filter'] = 'substring(ps.kode,1,1)';
                  $_POST['kode'] = $ch->fakultas->kode;
              }elseif($accessRule == 5){
                  $ch = UserHasDepartemen::findOne(['user_id' => Yii::$app->user->id]);
                  $_POST['filter'] = 'substring(ps.kode,1,2)';
                  $_POST['kode'] = $ch->departemen->kode;
              }elseif($accessRule == 6){
                  $ch = UserHasProgramStudi::findOne(['user_id' => Yii::$app->user->id]);
                  $_POST['filter'] = 'ps.kode';
                  $_POST['kode'] = $ch->programStudi->kode;  
              }

              $data = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

              $data_p2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

              $data_m2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=>(date('Y')-1)]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.statusMasuk_id'=>5]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
              $data_m2 = ($tahap == '1')? $data_m2 : '';


            $objWorkSheet1 = $this->inputArrayToBahanPleno2($objPHPExcel,$data,$data_p2,9,2,$ttd,$f['inisial'],$data_m2,0);
            $objWorkSheet1  ->setCellValue('A1', strtoupper($title));
            $objWorkSheet1  ->setCellValue('A2', strtoupper('SELEKSI CALON MAHASISWA BARU PROGRAM MAGISTER SAINS (S2) SEKOLAH PASCASARJANA IPB SEMESTER GANJIL TAHUN AKADEMIK '.date('Y').'/'.(date('Y')+1)));
            $objWorkSheet1  ->setCellValue('A3', strtoupper($f['nama']));
            $objWorkSheet1->setTitle($f['inisial'].'_S2');
            $objPHPExcel->addSheet($objWorkSheet1);
            $data2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

            $data2_p2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

            $data2_m3 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=>(date('Y')-1)]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.statusMasuk_id'=>5]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
            $data2_m3 = ($tahap == '1')? $data2_m3 : '';
       
            $objWorkSheet1 = $this->inputArrayToBahanPleno2($objPHPExcel,$data2,$data2_p2,9,3,$ttd,$f['inisial'],$data2_m3,0);
            $objWorkSheet1  ->setCellValue('A1', strtoupper($title));
            $objWorkSheet1  ->setCellValue('A2', strtoupper('SELEKSI CALON MAHASISWA BARU PROGRAM DOKTOR (S3) SEKOLAH PASCASARJANA IPB SEMESTER GANJIL TAHUN AKADEMIK '.date('Y').'/'.(date('Y')+1)));
            $objWorkSheet1  ->setCellValue('A3', strtoupper($f['nama']));
            $objWorkSheet1->setTitle($f['inisial'].'_S3');
            $objPHPExcel->addSheet($objWorkSheet1);        
        }
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->removeSheetByIndex(0);
        return $objPHPExcel;
    }

    public function getExcelBAPleno2($fak='', $jab, $tahun, $tahap, $url)
    {
        $_POST['url'] = $url;
        $dirTemp = Yii::getAlias('@app').'/arsip/template/';
        $filename = 'temp3.xlsx';
        $objPHPExcel = $this->render($dirTemp,$filename);
        $fakultas = Fakultas::getFakultas($fak);
        if($tahap == '1'){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO 2 TAHAP I';
        }elseif($tahap == '2'){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO 2 TAHAP II';
        }elseif($tahap == "s"){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO Kelas Khusus';
        }else{
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO Kelas by Research';
        }
        $ttd = Fakultas::getTandatangan($fak,$jab);
        foreach ($fakultas as $f){

              $accessRule = Yii::$app->user->identity->accessRole_id;
              if($accessRule < 4 || $accessRule == 8){
                  $_POST['filter'] = 'substring(ps.kode,1,1)';
                  $_POST['kode'] = $f['kode'];
              }elseif($accessRule == 4){
                  $ch = UserHasFakultas::findOne(['user_id' => Yii::$app->user->id]);
                  $_POST['filter'] = 'substring(ps.kode,1,1)';
                  $_POST['kode'] = $ch->fakultas->kode;
              }elseif($accessRule == 5){
                  $ch = UserHasDepartemen::findOne(['user_id' => Yii::$app->user->id]);
                  $_POST['filter'] = 'substring(ps.kode,1,2)';
                  $_POST['kode'] = $ch->departemen->kode;
              }elseif($accessRule == 6){
                  $ch = UserHasProgramStudi::findOne(['user_id' => Yii::$app->user->id]);
                  $_POST['filter'] = 'ps.kode';
                  $_POST['kode'] = $ch->programStudi->kode;  
              }

              $data = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

              $data_p2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

              $data_m2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=>(date('Y')-1)]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.statusMasuk_id'=>5]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
              $data_m2 = ($tahap == '1')? $data_m2 : '';


            $objWorkSheet1 = $this->inputArrayToBahanPleno2($objPHPExcel,$data,$data_p2,9,2,$ttd,$f['inisial'],$data_m2,1);
            $objWorkSheet1  ->setCellValue('A1', strtoupper($title));
            $objWorkSheet1  ->setCellValue('A2', strtoupper('SELEKSI CALON MAHASISWA BARU PROGRAM MAGISTER SAINS (S2) SEKOLAH PASCASARJANA IPB SEMESTER GANJIL TAHUN AKADEMIK '.date('Y').'/'.(date('Y')+1)));
            $objWorkSheet1  ->setCellValue('A3', strtoupper($f['nama']));
            $objWorkSheet1->setTitle($f['inisial'].'_S2');
            $objPHPExcel->addSheet($objWorkSheet1);
            $data2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

            $data2_p2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

            $data2_m3 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=>(date('Y')-1)]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.statusMasuk_id'=>5]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
            $data2_m3 = ($tahap == '1')? $data2_m3 : '';
       
            $objWorkSheet1 = $this->inputArrayToBahanPleno2($objPHPExcel,$data2,$data2_p2,9,3,$ttd,$f['inisial'],$data2_m3,1);
            $objWorkSheet1  ->setCellValue('A1', strtoupper($title));
            $objWorkSheet1  ->setCellValue('A2', strtoupper('SELEKSI CALON MAHASISWA BARU PROGRAM DOKTOR (S3) SEKOLAH PASCASARJANA IPB SEMESTER GANJIL TAHUN AKADEMIK '.date('Y').'/'.(date('Y')+1)));
            $objWorkSheet1  ->setCellValue('A3', strtoupper($f['nama']));
            $objWorkSheet1->setTitle($f['inisial'].'_S3');
            $objPHPExcel->addSheet($objWorkSheet1);        
        }
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->removeSheetByIndex(0);
        return $objPHPExcel;
    }

    public function getExcelBahanSeleksi($fak='', $tahun, $tahap,$url)
    {
        $_POST['url'] = $url;
        $jab=1;
        $dirTemp = Yii::getAlias('@app').'/arsip/template/';
        $filename = 'temp2.xlsx';
        $objPHPExcel = $this->render($dirTemp,$filename);
        $fakultas = Fakultas::getFakultas($fak);
        if($tahap == '1'){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO 2 TAHAP I';
        }elseif($tahap == '2'){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO 2 TAHAP II';
        }elseif($tahap == "s"){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO Kelas Khusus';
        }else{
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO Kelas by Research';
        }
        $ttd = Fakultas::getTandatangan($fak,$jab);
        foreach ($fakultas as $f){

              $accessRule = Yii::$app->user->identity->accessRole_id;
              if($accessRule < 4 || $accessRule == 8){
                  $filter = 'substring(ps.kode,1,1)';
                  $kode = $f['kode'];
                  $_POST['sql'] = [$filter=>$kode];
              }elseif($accessRule == 4){
                  $ch = UserHasFakultas::findOne(['user_id' => Yii::$app->user->id]);
                  $filter = 'substring(ps.kode,1,1)';
                  $kode = $ch->fakultas->kode;
                  $_POST['sql'] = [$filter=>$kode];
              }elseif($accessRule == 5){
                  $ch = UserHasDepartemen::findOne(['user_id' => Yii::$app->user->id]);
                  $filter = 'substring(ps.kode,1,2)';
                  $kode = $ch->departemen->kode;
                  $_POST['sql'] = [$filter=>$kode];
              }elseif($accessRule == 6){
                  $user = UserHasProgramStudi::find()->where(['user_id' => Yii::$app->user->id])->all();

                  $ch = UserHasProgramStudi::findOne(['user_id' => Yii::$app->user->id]);
                  $filter = 'substring(ps.kode,1,2)';
                  $filter2= 'substring(ps.kode,4,1)'; 
                  $kode = substr($ch->programStudi->kode,0,2);
                  $kode2 = substr($ch->programStudi->kode,3,1);
                  if(count($user)>1){
                      $_POST['sql'] = [$filter=>$kode,$filter2=>$kode2];
                  }else{
                      $filter = 'ps.kode';
                      $kode = $ch->programStudi->kode; 
                      $_POST['sql'] = [$filter=>$kode];
                  }
              }

              

              $data = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

            $objWorkSheet1 = $this->inputArrayToBahanSeleksi($objPHPExcel,$data,10,2,$ttd,$f['inisial'],0);
            $objWorkSheet1  ->setCellValue('A2', strtoupper($title));
            $objWorkSheet1  ->setCellValue('A4', strtoupper('TAHUN AKADEMIK '.date('Y').'/'.(date('Y')+1)));
            $objWorkSheet1  ->setCellValue('A5', strtoupper($f['nama']));
            $objWorkSheet1->setTitle($f['inisial'].'_S2');
            $objPHPExcel->addSheet($objWorkSheet1);
            $data2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
       
            $objWorkSheet1 = $this->inputArrayToBahanSeleksi($objPHPExcel,$data2,10,3,$ttd,$f['inisial'],0);
            $objWorkSheet1  ->setCellValue('A2', strtoupper($title));
            $objWorkSheet1  ->setCellValue('A4', strtoupper('TAHUN AKADEMIK '.date('Y').'/'.(date('Y')+1)));
            $objWorkSheet1  ->setCellValue('A5', strtoupper($f['nama']));
            $objWorkSheet1->setTitle($f['inisial'].'_S3');
            $objPHPExcel->addSheet($objWorkSheet1);        
        }
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->removeSheetByIndex(0);
        return $objPHPExcel;
    }

    public function getExcelBASeleksi($fak='', $jab, $tahun, $tahap, $url)
    {
        $_POST['url'] = $url;
        // $jab=1;
        $dirTemp = Yii::getAlias('@app').'/arsip/template/';
        $filename = 'temp2.xlsx';
        $objPHPExcel = $this->render($dirTemp,$filename);
        $fakultas = Fakultas::getFakultas($fak);
        if($tahap == '1'){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO 2 TAHAP I';
        }elseif($tahap == '2'){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO 2 TAHAP II';
        }elseif($tahap == "s"){
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO Kelas Khusus';
        }else{
            $title = 'DAFTAR CALON MAHASISWA - SIDANG PLENO Kelas by Research';
        }
        $ttd = Fakultas::getTandatangan($fak,$jab);
        foreach ($fakultas as $f){

              $accessRule = Yii::$app->user->identity->accessRole_id;
              if($accessRule < 4 || $accessRule == 8){
                  $filter = 'substring(ps.kode,1,1)';
                  $kode = $f['kode'];
                  $_POST['sql'] = [$filter=>$kode];
              }elseif($accessRule == 4){
                  $ch = UserHasFakultas::findOne(['user_id' => Yii::$app->user->id]);
                  $filter = 'substring(ps.kode,1,1)';
                  $kode = $ch->fakultas->kode;
                  $_POST['sql'] = [$filter=>$kode];
              }elseif($accessRule == 5){
                  $ch = UserHasDepartemen::findOne(['user_id' => Yii::$app->user->id]);
                  $filter = 'substring(ps.kode,1,2)';
                  $kode = $ch->departemen->kode;
                  $_POST['sql'] = [$filter=>$kode];
              }elseif($accessRule == 6){
                  $user = UserHasProgramStudi::find()->where(['user_id' => Yii::$app->user->id])->all();

                  $ch = UserHasProgramStudi::findOne(['user_id' => Yii::$app->user->id]);
                  $filter = 'substring(ps.kode,1,2)';
                  $filter2= 'substring(ps.kode,4,1)'; 
                  $kode = substr($ch->programStudi->kode,0,2);
                  $kode2 = substr($ch->programStudi->kode,3,1);
                  if(count($user)>1){
                      $_POST['sql'] = [$filter=>$kode,$filter2=>$kode2];
                  }else{
                      $filter = 'ps.kode';
                      $kode = $ch->programStudi->kode; 
                      $_POST['sql'] = [$filter=>$kode];
                  }
              }

              $data = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

            $objWorkSheet1 = $this->inputArrayToBahanSeleksi($objPHPExcel,$data,10,2,$ttd,$f['inisial'],1);
            $objWorkSheet1  ->setCellValue('A2', strtoupper($title));
            $objWorkSheet1  ->setCellValue('A4', strtoupper('TAHUN AKADEMIK '.date('Y').'/'.(date('Y')+1)));
            $objWorkSheet1  ->setCellValue('A5', strtoupper($f['nama']));
            $objWorkSheet1->setTitle($f['inisial'].'_S2');
            $objPHPExcel->addSheet($objWorkSheet1);
            $data2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where($_POST['sql'])->andWhere(['strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
       
            $objWorkSheet1 = $this->inputArrayToBahanSeleksi($objPHPExcel,$data2,10,3,$ttd,$f['inisial'],1);
            $objWorkSheet1  ->setCellValue('A2', strtoupper($title));
            $objWorkSheet1  ->setCellValue('A4', strtoupper('TAHUN AKADEMIK '.date('Y').'/'.(date('Y')+1)));
            $objWorkSheet1  ->setCellValue('A5', strtoupper($f['nama']));
            $objWorkSheet1->setTitle($f['inisial'].'_S3');
            $objPHPExcel->addSheet($objWorkSheet1);        
        }
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->removeSheetByIndex(0);
        return $objPHPExcel;
    }


    public function inputArrayToBahanPleno($objPHPExcel,$arr,$starRow,$strata,$ttd,$inisialfak,$arr2,$hasil)
    {   
        $row = $starRow;
        $ps = '';
        ini_set('max_execution_time', 300);
        $objS2 = $objPHPExcel->getSheet(0);
        $objS3 = $objPHPExcel->getSheet(1);
        $objWorkSheet1 = clone $objS2;
        if ($strata == 3)
            $objWorkSheet1 = clone $objS3;
        foreach($arr as $r => $dataRow) {
        $beasiswa = '';
        $beasiswa = @$dataRow['rencanaPembiayaan']['jenisPembiayan']['title'].' '.@$dataRow['rencanaPembiayaan']['deskripsi'];
        $pkerja = '';
        if(isset($dataRow->orang->pekerjaans[0])){
          $pekerjaan = $dataRow->orang->pekerjaans[0];
          if(isset($pekerjaan->instansis[0])){
            $instansi = $pekerjaan->instansis[0];
          }
          
          if($pekerjaan->jenisInstansi->id == 1){
            $pkerja = $pekerjaan->jenisInstansi->nama;
          }else{
            $pkerja = $instansi->nama;
          }
        }

        $syarat = []; 
            foreach($dataRow['syaratTambahans'] as $value){
              $syarat[$value->jenisSyaratTambahan->title]=$value;
            }

        $pendidikan = []; $i = 1; 
          foreach($dataRow['orang']['pendidikans'] as $value){

            $pendidikan[$i] = $value;
            $i++;
          }

        $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
            $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
            foreach($model['pendaftaranHasProgramStudis'] as $value){
                $pilihan[$i] = $value->programStudi->kode;
                $mayor[$i] = $value->programStudi->nama;
                $inisial[$i] = $value->programStudi->inisial;
                $strata = $value->programStudi->strata;
                $i++;
            }

            $program = $dataRow->pendaftaranHasProgramStudis[0];
            $proses_sidang = $program->prosesSidangs;
            foreach($proses_sidang as $p => $proses){
              $sidang = Sidang::findOne($proses->sidang_id);
              if($sidang->jenisSidang_id == 1){
                $idhasil = (isset($proses->hasilKeputusan->id) )? $proses->hasilKeputusan->id : null;
                $keterangan = (isset($proses->keterangan))? $proses->keterangan : null;
              }
            }

        $objWorkSheet1->insertNewRowBefore($row,1);
        if($strata == 2){
          if($hasil == 1){
          $ptsn=[0=>'',1=>'R',2=>'S',3=>'T'];
            if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                $objWorkSheet1->setCellValue($ptsn[$idhasil].$row,'W')
                            ->setCellValue('U'.$row , @$keterangan); 
            }
          }

          $objWorkSheet1->setCellValue('A'.$row, $r+1)
                                        ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                        ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
										->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['usia']))
                                        ->setCellValue('E'.$row, @$inisial[1])
                                          ->setCellValue('F'.$row, @$inisial[2])
                                          ->setCellValue('G'.$row, $beasiswa)
                                          ->setCellValue('H'.$row, @$pendidikan[1]->institusi->nama)
                                          ->setCellValue('I'.$row, @$pendidikan[1]->fakultas)
                                          ->setCellValue('J'.$row, @$pendidikan[1]->programStudi)
                                          ->setCellValue('K'.$row, @$pendidikan[1]->akreditasi)
                                          ->setCellValue('L'.$row, isset($pendidikan[1]->ipkAsal)? $pendidikan[1]->ipkAsal : @$pendidikan[1]->ipk)
                                          ->setCellValue('M'.$row, @$pkerja)
                                          ->setCellValue('N'.$row, @$syarat['TOEFL']->score)
                                        ->setCellValue('O'.$row, @$syarat['TPA']->score);
        }
        else{
          if($hasil == 1){
          $ptsn=[0=>'',1=>'U',2=>'V',3=>'W'];
            if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                $objWorkSheet1->setCellValue($ptsn[$idhasil].$row,'W')
                            ->setCellValue('X'.$row , @$program->prosesSidangs[0]->keterangan);
            }
          }

          $objWorkSheet1->setCellValue('A'.$row, $r+1)
                                        ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                        ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
										->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['usia']))
                                        ->setCellValue('E'.$row, @$inisial[1])
                                          ->setCellValue('F'.$row, @$inisial[2])
                                          ->setCellValue('G'.$row, $beasiswa)
                                          ->setCellValue('H'.$row, @$pendidikan[1]->institusi->nama)
                                          ->setCellValue('I'.$row, @$pendidikan[1]->fakultas)
                                          ->setCellValue('J'.$row, @$pendidikan[1]->programStudi)
                                          ->setCellValue('K'.$row, @$pendidikan[1]->akreditasi)
                                          ->setCellValue('L'.$row, isset($pendidikan[1]->ipkAsal)? $pendidikan[1]->ipkAsal : @$pendidikan[1]->ipk)
                                          ->setCellValue('M'.$row, @$pendidikan[2]->institusi->nama)
                                          ->setCellValue('N'.$row, @$pendidikan[2]->fakultas)
                                        ->setCellValue('O'.$row, @$pendidikan[2]->programStudi)
                                          ->setCellValue('P'.$row, @$pendidikan[2]->akreditasi)
                                          ->setCellValue('Q'.$row, isset($pendidikan[2]->ipkAsal)? $pendidikan[2]->ipkAsal : @$pendidikan[2]->ipk)
                                          ->setCellValue('R'.$row, @$pkerja)
                                          ->setCellValue('S'.$row, @$syarat['TOEFL']->score)
                                        ->setCellValue('T'.$row, @$syarat['TPA']->score);
        }
            if ($ps != $pilihan[1]){   
                $objWorkSheet1->insertNewRowBefore($row,1);
                $objWorkSheet1->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A7'), 'A'.$row);
                $objWorkSheet1->setCellValue('A'.$row, $inisial[1]
                                    .' - '.$mayor[1]
                                    .' ('.$pilihan[1].')');
                if($strata == 2){ $column =':U';} else {$column = ':X';}
                $objWorkSheet1->mergeCells('A'.$row.$column.$row);
                $row++;
            }
            $row++;
            $ps = $pilihan[1];
        }

        // menunda 
        if($arr2 != ''){
        $menunda ='';
        foreach($arr2 as $r => $dataRow) {
        $beasiswa = '';
        $beasiswa = @$dataRow['rencanaPembiayaan']['jenisPembiayan']['title'].' '.@$dataRow['rencanaPembiayaan']['deskripsi'];
        $pkerja = '';
        if(isset($dataRow->orang->pekerjaans[0])){
          $pekerjaan = $dataRow->orang->pekerjaans[0];
          if(isset($pekerjaan->instansis[0])){
            $instansi = $pekerjaan->instansis[0];
          }
          
          if($pekerjaan->jenisInstansi->id == 1){
            $pkerja = $pekerjaan->jenisInstansi->nama;
          }else{
            $pkerja = $instansi->nama;
          }
        }

        $syarat = []; 
            foreach($dataRow['syaratTambahans'] as $value){
              $syarat[$value->jenisSyaratTambahan->title]=$value;
            }

        $pendidikan = []; $i = 1; 
          foreach($dataRow['orang']['pendidikans'] as $value){

            $pendidikan[$i] = $value;
            $i++;
          }

        $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
            $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
            foreach($model['pendaftaranHasProgramStudis'] as $value){
                $pilihan[$i] = $value->programStudi->kode;
                $mayor[$i] = $value->programStudi->nama;
                $inisial[$i] = $value->programStudi->inisial;
                $strata = $value->programStudi->strata;
                $i++;
            }

          $program = $dataRow->pendaftaranHasProgramStudis[0];
          $statusmenunda = $program->prosesSidangs[0]->hasilKeputusan->keputusan;

        $objWorkSheet1->insertNewRowBefore($row,1);
                
        if($strata == 2){
            $objWorkSheet1->setCellValue('A'.$row, $r+1)
                                          ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                        ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
										->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['usia']))
                                        ->setCellValue('E'.$row, @$inisial[1])
                                          ->setCellValue('F'.$row, @$inisial[2])
                                          ->setCellValue('G'.$row, $beasiswa)
                                          ->setCellValue('H'.$row, @$pendidikan[1]->institusi->nama)
                                          ->setCellValue('I'.$row, @$pendidikan[1]->fakultas)
                                          ->setCellValue('J'.$row, @$pendidikan[1]->programStudi)
                                          ->setCellValue('K'.$row, @$pendidikan[1]->akreditasi)
                                          ->setCellValue('L'.$row, isset($pendidikan[1]->ipkAsal)? $pendidikan[1]->ipkAsal : @$pendidikan[1]->ipk)
                                          ->setCellValue('M'.$row, @$pkerja)
                                          ->setCellValue('N'.$row, @$syarat['TOEFL']->score)
                                          ->setCellValue('O'.$row, @$syarat['TPA']->score)
                                          ->setCellValue('R'.$row, $statusmenunda);
            $objWorkSheet1->mergeCells('R'.$row.':T'.$row);
        }
        else{
            $objWorkSheet1->setCellValue('A'.$row, $r+1)
                                          ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                        ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
										->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['usia']))
                                        ->setCellValue('E'.$row, @$inisial[1])
                                          ->setCellValue('F'.$row, @$inisial[2])
                                          ->setCellValue('G'.$row, $beasiswa)
                                          ->setCellValue('H'.$row, @$pendidikan[1]->institusi->nama)
                                          ->setCellValue('I'.$row, @$pendidikan[1]->fakultas)
                                          ->setCellValue('J'.$row, @$pendidikan[1]->programStudi)
                                          ->setCellValue('K'.$row, @$pendidikan[1]->akreditasi)
                                          ->setCellValue('L'.$row, isset($pendidikan[1]->ipkAsal)? $pendidikan[1]->ipkAsal : @$pendidikan[1]->ipk)
                                          ->setCellValue('M'.$row, @$pendidikan[2]->institusi->nama)
                                          ->setCellValue('N'.$row, @$pendidikan[2]->fakultas)
                                        ->setCellValue('O'.$row, @$pendidikan[2]->programStudi)
                                          ->setCellValue('P'.$row, @$pendidikan[2]->akreditasi)
                                          ->setCellValue('Q'.$row, isset($pendidikan[2]->ipkAsal)? $pendidikan[2]->ipkAsal : @$pendidikan[2]->ipk)
                                          ->setCellValue('R'.$row, @$pkerja)
                                          ->setCellValue('S'.$row, @$syarat['TOEFL']->score)
                                        ->setCellValue('T'.$row, @$syarat['TPA']->score)
                                          ->setCellValue('U'.$row, $statusmenunda);
            $objWorkSheet1->mergeCells('U'.$row.':W'.$row);
        }
            if ($menunda == ''){   
                $objWorkSheet1->insertNewRowBefore($row,1);
                $objWorkSheet1->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A7'), 'A'.$row);
                $objWorkSheet1->setCellValue('A'.$row, 'Informasi Calon Mahasiswa Menunda Tahun Lalu');
                if($strata == 2){ $column =':U';} else {$column = ':X';}
                $objWorkSheet1->mergeCells('A'.$row.$column.$row);
                $row++;
            }
            $row++;
            $menunda = $pilihan[1];
        }
      }

        if($strata == 2)
            $column = 'O'; else $column = 'S';
        
            if($ttd['jabatan'] == 'dekan'){
                $jabatan = 'Dekan '.$inisialfak;
            }else{
                $jabatan = 'an. Dekan '.$inisialfak;
                $objWorkSheet1->setCellValue($column.($row+4),'Wakil Dekan');
            }
            $nip = 'NIP. '.$ttd['nip'];
            $date='Bogor, '.date('d-m-Y');
            $objWorkSheet1->setCellValue($column.($row+2),$date);
            $objWorkSheet1->setCellValue($column.($row+3),$jabatan);
            $objWorkSheet1->setCellValue($column.($row+8),$ttd['nama']);
            $objWorkSheet1->setCellValue($column.($row+9),$nip);
                   
        $objWorkSheet1->removeRow($starRow-2,2);
        return $objWorkSheet1;
    }

    public function inputArrayToBahanPleno2($objPHPExcel,$arr,$arr_p2,$starRow,$strata,$ttd,$inisialfak,$arr2,$hasil)
    {   
        $row = $starRow;
        $ps = '';
        ini_set('max_execution_time', 300);
        $objS2 = $objPHPExcel->getSheet(0);
        $objS3 = $objPHPExcel->getSheet(1);
        $objWorkSheet1 = clone $objS2;
        if ($strata == 3)
            $objWorkSheet1 = clone $objS3;
        foreach($arr as $r => $dataRow) {
        $beasiswa = '';
        $beasiswa = @$dataRow['rencanaPembiayaan']['jenisPembiayan']['title'].' '.@$dataRow['rencanaPembiayaan']['deskripsi'];
        $pkerja = '';
        if(isset($dataRow->orang->pekerjaans[0])){
          $pekerjaan = $dataRow->orang->pekerjaans[0];
          if(isset($pekerjaan->instansis[0])){
            $instansi = $pekerjaan->instansis[0];
          }
          
          if($pekerjaan->jenisInstansi->id == 1){
            $pkerja = $pekerjaan->jenisInstansi->nama;
          }else{
            $pkerja = $instansi->nama;
          }
        }

        $syarat = []; 
            foreach($dataRow['syaratTambahans'] as $value){
              $syarat[$value->jenisSyaratTambahan->title]=$value;
            }

        $pendidikan = []; $i = 1; 
          foreach($dataRow['orang']['pendidikans'] as $value){

            $pendidikan[$i] = $value;
            $i++;
          }

        $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
            $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
            foreach($model['pendaftaranHasProgramStudis'] as $value){
                $pilihan[$i] = $value->programStudi->kode;
                $mayor[$i] = $value->programStudi->nama;
                $inisial[$i] = $value->programStudi->inisial;
                $strata = $value->programStudi->strata;
                $i++;
            }

            $program = $dataRow->pendaftaranHasProgramStudis[0];
            $proses_sidang = $program->prosesSidangs;
            foreach($proses_sidang as $p => $proses){
              $sidang = Sidang::findOne($proses->sidang_id);
              if($sidang->jenisSidang_id == 2){
                 $idhasil = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : null;
                 if($hasil == 1){
                  $keterangan = (isset($proses->keterangan))? $proses->keterangan : null;
                 }
              }
              if($sidang->jenisSidang_id == 3){
                $idhasilseleksi = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->keputusan : null;
                if($hasil==0){
                  $keterangan = (isset($proses->keterangan))? $proses->keterangan : null;
                }
              }
              
            }
    
        $objWorkSheet1->insertNewRowBefore($row,1);
        if($strata == 2){
          if($hasil == 1){
          $ptsn=[0=>'',4=>'S',5=>'T',6=>'U'];
            if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                $objWorkSheet1->setCellValue($ptsn[$idhasil].$row,'W')
                              ->setCellValue('V'.$row , @$keterangan); 
            }
          }

            if(isset($idhasilseleksi) && $idhasilseleksi!=null){
                $objWorkSheet1->setCellValue('P'.$row, @$idhasilseleksi)
                              ->setCellValue('V'.$row, @$keterangan);
            }

          $objWorkSheet1->setCellValue('A'.$row, $r+1)
                                        ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                        ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
										->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['usia']))
                                        ->setCellValue('E'.$row, @$inisial[1])
                                          ->setCellValue('F'.$row, @$inisial[2])
                                          ->setCellValue('G'.$row, $beasiswa)
                                          ->setCellValue('H'.$row, @$pendidikan[1]->institusi->nama)
                                          ->setCellValue('I'.$row, @$pendidikan[1]->fakultas)
                                          ->setCellValue('J'.$row, @$pendidikan[1]->programStudi)
                                          ->setCellValue('K'.$row, @$pendidikan[1]->akreditasi)
                                          ->setCellValue('L'.$row, isset($pendidikan[1]->ipkAsal)? $pendidikan[1]->ipkAsal : @$pendidikan[1]->ipk)
                                          ->setCellValue('M'.$row, @$pkerja)
                                          ->setCellValue('N'.$row, @$syarat['TOEFL']->score)
                                          ->setCellValue('O'.$row, @$syarat['TPA']->score);
          $objWorkSheet1->mergeCells('P'.$row.':R'.$row);
        }
        else{
          if($hasil == 1){
          $ptsn=[0=>'',4=>'X',5=>'Y',6=>'Z'];
            if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                $objWorkSheet1->setCellValue($ptsn[$idhasil].$row,'U')
                            ->setCellValue('AA'.$row , @$keterangan); 
            }
          }

          if(isset($idhasilseleksi) && $idhasilseleksi!=null){
                $objWorkSheet1->setCellValue('U'.$row , @$idhasilseleksi)
                              ->setCellValue('AA'.$row , @$keterangan);
            }

          $objWorkSheet1->setCellValue('A'.$row, $r+1)
                                        ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                        ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
										->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['usia']))
                                        ->setCellValue('E'.$row, @$inisial[1])
                                          ->setCellValue('F'.$row, @$inisial[2])
                                          ->setCellValue('G'.$row, $beasiswa)
                                          ->setCellValue('H'.$row, @$pendidikan[1]->institusi->nama)
                                          ->setCellValue('I'.$row, @$pendidikan[1]->fakultas)
                                          ->setCellValue('J'.$row, @$pendidikan[1]->programStudi)
                                          ->setCellValue('K'.$row, @$pendidikan[1]->akreditasi)
                                          ->setCellValue('L'.$row, isset($pendidikan[1]->ipkAsal)? $pendidikan[1]->ipkAsal : @$pendidikan[1]->ipk)
                                          ->setCellValue('M'.$row, @$pendidikan[2]->institusi->nama)
                                          ->setCellValue('N'.$row, @$pendidikan[2]->fakultas)
                                        ->setCellValue('O'.$row, @$pendidikan[2]->programStudi)
                                          ->setCellValue('P'.$row, @$pendidikan[2]->akreditasi)
                                          ->setCellValue('Q'.$row, isset($pendidikan[2]->ipkAsal)? $pendidikan[2]->ipkAsal : @$pendidikan[2]->ipk)
                                          ->setCellValue('R'.$row, @$pkerja)
                                          ->setCellValue('S'.$row, @$syarat['TOEFL']->score)
                                        ->setCellValue('T'.$row, @$syarat['TPA']->score);
          $objWorkSheet1->mergeCells('U'.$row.':W'.$row);
        }
            if ($ps != $pilihan[1]){   
                $objWorkSheet1->insertNewRowBefore($row,1);
                $objWorkSheet1->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A7'), 'A'.$row);
                $objWorkSheet1->setCellValue('A'.$row, $inisial[1]
                                    .' - '.$mayor[1]
                                    .' ('.$pilihan[1].')');
                if($strata == 2){ $column =':U';} else {$column = ':Z';}
                $objWorkSheet1->mergeCells('A'.$row.$column.$row);
                $row++;
            }
            $row++;
            $ps = $pilihan[1];
        }

        // pilihan2
        $ps='';
        foreach($arr_p2 as $r => $dataRow) {
        $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
            $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
            foreach($model['pendaftaranHasProgramStudis'] as $value){
                $pilihan[$i] = $value->programStudi->kode;
                $mayor[$i] = $value->programStudi->nama;
                $inisial[$i] = $value->programStudi->inisial;
                $strata = $value->programStudi->strata;
                $i++;
            }

        if($pilihan[1]!=$pilihan[2]){

        $beasiswa = '';
        $beasiswa = @$dataRow['rencanaPembiayaan']['jenisPembiayan']['title'].' '.@$dataRow['rencanaPembiayaan']['deskripsi'];
        $pkerja = '';
        if(isset($dataRow->orang->pekerjaans[0])){
          $pekerjaan = $dataRow->orang->pekerjaans[0];
          if(isset($pekerjaan->instansis[0])){
            $instansi = $pekerjaan->instansis[0];
          }
          
          if($pekerjaan->jenisInstansi->id == 1){
            $pkerja = $pekerjaan->jenisInstansi->nama;
          }else{
            $pkerja = $instansi->nama;
          }
        }

        $syarat = []; 
            foreach($dataRow['syaratTambahans'] as $value){
              $syarat[$value->jenisSyaratTambahan->title]=$value;
            }

        $pendidikan = []; $i = 1; 
          foreach($dataRow['orang']['pendidikans'] as $value){

            $pendidikan[$i] = $value;
            $i++;
          }


            $program = $dataRow->pendaftaranHasProgramStudis[0];
            $proses_sidang = $program->prosesSidangs;
            foreach($proses_sidang as $p => $proses){
              $sidang = Sidang::findOne($proses->sidang_id);
              if($sidang->jenisSidang_id == 2){
                 $idhasil = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : null;
                 if($hasil == 1){
                  $keterangan = (isset($proses->keterangan))? $proses->keterangan : null;
                 }
              }
              if($sidang->jenisSidang_id == 3){
                $idhasilseleksi = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->keputusan : null;
                if($hasil==0){
                  $keterangan = (isset($proses->keterangan))? $proses->keterangan : null;
                }
              }
              
            }
    
        $objWorkSheet1->insertNewRowBefore($row,1);
        if($strata == 2){
          if($hasil == 1){
          $ptsn=[0=>'',4=>'S',5=>'T',6=>'U'];
            if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                $objWorkSheet1->setCellValue($ptsn[$idhasil].$row,'W')
                              ->setCellValue('V'.$row , @$keterangan); 
            }
          }

            if(isset($idhasilseleksi) && $idhasilseleksi!=null){
                $objWorkSheet1->setCellValue('P'.$row, @$idhasilseleksi)
                              ->setCellValue('V'.$row, @$keterangan);
            }

          $objWorkSheet1->setCellValue('A'.$row, $r+1)
                                        ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                        ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
										->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['usia']))
                                        ->setCellValue('E'.$row, @$inisial[1])
                                          ->setCellValue('F'.$row, @$inisial[2])
                                          ->setCellValue('G'.$row, $beasiswa)
                                          ->setCellValue('H'.$row, @$pendidikan[1]->institusi->nama)
                                          ->setCellValue('I'.$row, @$pendidikan[1]->fakultas)
                                          ->setCellValue('J'.$row, @$pendidikan[1]->programStudi)
                                          ->setCellValue('K'.$row, @$pendidikan[1]->akreditasi)
                                          ->setCellValue('L'.$row, isset($pendidikan[1]->ipkAsal)? $pendidikan[1]->ipkAsal : @$pendidikan[1]->ipk)
                                          ->setCellValue('M'.$row, @$pkerja)
                                          ->setCellValue('N'.$row, @$syarat['TOEFL']->score)
                                          ->setCellValue('O'.$row, @$syarat['TPA']->score);
          $objWorkSheet1->mergeCells('P'.$row.':R'.$row);
        }
        else{
          if($hasil == 1){
          $ptsn=[0=>'',4=>'X',5=>'Y',6=>'Z'];
            if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                $objWorkSheet1->setCellValue($ptsn[$idhasil].$row,'W')
                            ->setCellValue('AA'.$row , @$keterangan); 
            }
          }

          if(isset($idhasilseleksi) && $idhasilseleksi!=null){
                $objWorkSheet1->setCellValue('U'.$row , @$idhasilseleksi)
                              ->setCellValue('AA'.$row , @$keterangan);
            }

          $objWorkSheet1->setCellValue('A'.$row, $r+1)
                                        ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                        ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
										->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['usia']))
                                        ->setCellValue('E'.$row, @$inisial[1])
                                          ->setCellValue('F'.$row, @$inisial[2])
                                          ->setCellValue('G'.$row, $beasiswa)
                                          ->setCellValue('H'.$row, @$pendidikan[1]->institusi->nama)
                                          ->setCellValue('I'.$row, @$pendidikan[1]->fakultas)
                                          ->setCellValue('J'.$row, @$pendidikan[1]->programStudi)
                                          ->setCellValue('K'.$row, @$pendidikan[1]->akreditasi)
                                          ->setCellValue('L'.$row, isset($pendidikan[1]->ipkAsal)? $pendidikan[1]->ipkAsal : @$pendidikan[1]->ipk)
                                          ->setCellValue('M'.$row, @$pendidikan[2]->institusi->nama)
                                          ->setCellValue('N'.$row, @$pendidikan[2]->fakultas)
                                        ->setCellValue('O'.$row, @$pendidikan[2]->programStudi)
                                          ->setCellValue('P'.$row, @$pendidikan[2]->akreditasi)
                                          ->setCellValue('Q'.$row, isset($pendidikan[2]->ipkAsal)? $pendidikan[2]->ipkAsal : @$pendidikan[2]->ipk)
                                          ->setCellValue('R'.$row, @$pkerja)
                                          ->setCellValue('S'.$row, @$syarat['TOEFL']->score)
                                        ->setCellValue('T'.$row, @$syarat['TPA']->score);
          $objWorkSheet1->mergeCells('U'.$row.':W'.$row);
        }
            if ($ps == ''){   
                $objWorkSheet1->insertNewRowBefore($row,1);
                $objWorkSheet1->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A7'), 'A'.$row);
                $objWorkSheet1->setCellValue('A'.$row, 'Calon Mahasiswa Pilihan ke-2');
                if($strata == 2){ $column =':V';} else {$column = ':AA';}
                $objWorkSheet1->mergeCells('A'.$row.$column.$row);
                $row++;
            }

            if ($ps != $pilihan[2]){   
                $objWorkSheet1->insertNewRowBefore($row,1);
                $objWorkSheet1->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A7'), 'A'.$row);
                $objWorkSheet1->setCellValue('A'.$row, $inisial[2]
                                    .' - '.$mayor[2]
                                    .' ('.$pilihan[2].')');
                if($strata == 2){ $column =':V';} else {$column = ':AA';}
                $objWorkSheet1->mergeCells('A'.$row.$column.$row);
                $row++;
            }
            $row++;
            $ps = $pilihan[2];
        }
        }
        
        // menunda 
        if($arr2 != ""){
        $menunda ='';
        foreach($arr2 as $r => $dataRow) {
        $beasiswa = '';
        $beasiswa = @$dataRow['rencanaPembiayaan']['jenisPembiayan']['title'].' '.@$dataRow['rencanaPembiayaan']['deskripsi'];
        $pkerja = '';
        if(isset($dataRow->orang->pekerjaans[0])){
          $pekerjaan = $dataRow->orang->pekerjaans[0];
          if(isset($pekerjaan->instansis[0])){
            $instansi = $pekerjaan->instansis[0];
          }
          
          if($pekerjaan->jenisInstansi->id == 1){
            $pkerja = $pekerjaan->jenisInstansi->nama;
          }else{
            $pkerja = $instansi->nama;
          }
        }

        $syarat = []; 
            foreach($dataRow['syaratTambahans'] as $value){
              $syarat[$value->jenisSyaratTambahan->title]=$value;
            }

        $pendidikan = []; $i = 1; 
          foreach($dataRow['orang']['pendidikans'] as $value){

            $pendidikan[$i] = $value;
            $i++;
          }

        $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
            $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
            foreach($model['pendaftaranHasProgramStudis'] as $value){
                $pilihan[$i] = $value->programStudi->kode;
                $mayor[$i] = $value->programStudi->nama;
                $inisial[$i] = $value->programStudi->inisial;
                $strata = $value->programStudi->strata;
                $i++;
            }

          $program = $dataRow->pendaftaranHasProgramStudis[0];
          $statusmenunda = $program->prosesSidangs[0]->hasilKeputusan->keputusan;

        $objWorkSheet1->insertNewRowBefore($row,1);
                
        if($strata == 2){
            $objWorkSheet1->setCellValue('A'.$row, $r+1)
                                          ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                        ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
										->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['usia']))
                                        ->setCellValue('E'.$row, @$inisial[1])
                                          ->setCellValue('F'.$row, @$inisial[2])
                                          ->setCellValue('G'.$row, $beasiswa)
                                          ->setCellValue('H'.$row, @$pendidikan[1]->institusi->nama)
                                          ->setCellValue('I'.$row, @$pendidikan[1]->fakultas)
                                          ->setCellValue('J'.$row, @$pendidikan[1]->programStudi)
                                          ->setCellValue('K'.$row, @$pendidikan[1]->akreditasi)
                                          ->setCellValue('L'.$row, isset($pendidikan[1]->ipkAsal)? $pendidikan[1]->ipkAsal : @$pendidikan[1]->ipk)
                                          ->setCellValue('M'.$row, @$pkerja)
                                          ->setCellValue('N'.$row, @$syarat['TOEFL']->score)
                                          ->setCellValue('N'.$row, @$syarat['TPA']->score)
                                          ->setCellValue('S'.$row, $statusmenunda);
            $objWorkSheet1->mergeCells('S'.$row.':U'.$row);
        }
        else{
            $objWorkSheet1->setCellValue('A'.$row, $r+1)
                                          ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                        ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
										->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['usia']))
                                        ->setCellValue('E'.$row, @$inisial[1])
                                          ->setCellValue('F'.$row, @$inisial[2])
                                          ->setCellValue('G'.$row, $beasiswa)
                                          ->setCellValue('H'.$row, @$pendidikan[1]->institusi->nama)
                                          ->setCellValue('I'.$row, @$pendidikan[1]->fakultas)
                                          ->setCellValue('J'.$row, @$pendidikan[1]->programStudi)
                                          ->setCellValue('K'.$row, @$pendidikan[1]->akreditasi)
                                          ->setCellValue('L'.$row, isset($pendidikan[1]->ipkAsal)? $pendidikan[1]->ipkAsal : @$pendidikan[1]->ipk)
                                          ->setCellValue('M'.$row, @$pendidikan[2]->institusi->nama)
                                          ->setCellValue('N'.$row, @$pendidikan[2]->fakultas)
                                        ->setCellValue('O'.$row, @$pendidikan[2]->programStudi)
                                          ->setCellValue('P'.$row, @$pendidikan[2]->akreditasi)
                                          ->setCellValue('Q'.$row, isset($pendidikan[2]->ipkAsal)? $pendidikan[2]->ipkAsal : @$pendidikan[2]->ipk)
                                          ->setCellValue('R'.$row, @$pkerja)
                                          ->setCellValue('S'.$row, @$syarat['TOEFL']->score)
                                        ->setCellValue('S'.$row, @$syarat['TPA']->score)
                                          ->setCellValue('T'.$row, $statusmenunda);
            $objWorkSheet1->mergeCells('X'.$row.':Z'.$row);
        }
            if ($menunda == ''){   
                $objWorkSheet1->insertNewRowBefore($row,1);
                $objWorkSheet1->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A7'), 'A'.$row);
                $objWorkSheet1->setCellValue('A'.$row, 'Informasi Calon Mahasiswa Menunda Tahun Lalu');
                if($strata == 2){ $column =':V';} else {$column = ':AA';}
                $objWorkSheet1->mergeCells('A'.$row.$column.$row);
                $row++;
            }
            $row++;
            $menunda = $pilihan[1];
        }
      }

      
            $column = 'Q';
        
            if($ttd['jabatan'] == 'dekan'){
                $jabatan = 'Dekan '.$inisialfak;
            }else{
                $jabatan = 'an. Dekan '.$inisialfak;
                $objWorkSheet1->setCellValue($column.($row+4),'Wakil Dekan');
            }
            $nip = 'NIP. '.$ttd['nip'];
            $date='Bogor, '.date('d-m-Y');
            $objWorkSheet1->setCellValue($column.($row+3),$date);
            $objWorkSheet1->setCellValue($column.($row+4),$jabatan);
            $objWorkSheet1->setCellValue($column.($row+9),$ttd['nama']);
            $objWorkSheet1->setCellValue($column.($row+10),$nip);
                   
        $objWorkSheet1->removeRow($starRow-2,2);
        return $objWorkSheet1;
    }

    public function inputArrayToBahanSeleksi($objPHPExcel,$arr,$starRow,$strata,$ttd,$inisialfak,$hasil)
    {   
        $row = $starRow;
        $ps = '';
        ini_set('max_execution_time', 300);
        $objS2 = $objPHPExcel->getSheet(0);
        $objS3 = $objPHPExcel->getSheet(1);
        $objWorkSheet1 = clone $objS2;
        if ($strata == 3)
            $objWorkSheet1 = clone $objS3;
        foreach($arr as $r => $dataRow) {

        $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
            $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
            foreach($model['pendaftaranHasProgramStudis'] as $value){
                $pilihan[$i] = $value->programStudi->kode;
                $mayor[$i] = $value->programStudi->nama;
                $inisial[$i] = $value->programStudi->inisial;
                $strata = $value->programStudi->strata;
                $i++;
            }

            $program = $dataRow->pendaftaranHasProgramStudis[0];
            $proses_sidang = $program->prosesSidangs;
            foreach($proses_sidang as $p => $proses){
              $sidang = Sidang::findOne($proses->sidang_id);
              if($sidang->jenisSidang_id == 3){
                $idhasil = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : null;
                $keterangan = (isset($proses->keterangan))? $proses->keterangan : null;
              }
            }
            

        $objWorkSheet1->insertNewRowBefore($row,1);

        if($hasil == 1){
          $ptsn=[0=>'',9=>'F',10=>'G',11=>'H'];
            if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                $objWorkSheet1->setCellValue($ptsn[$idhasil].$row,'V')
                            ->setCellValue('I'.$row , @$keterangan); 
            }
          }

        if($strata == 2){
          $objWorkSheet1->setCellValue('A'.$row, $r+1)
                                        ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                        ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
                                        ->setCellValue('D'.$row, @$inisial[1])
                                          ->setCellValue('E'.$row, @$inisial[2]);
        }
        else{
          $objWorkSheet1->setCellValue('A'.$row, $r+1)
                                        ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                        ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
                                        ->setCellValue('D'.$row, @$inisial[1])
                                          ->setCellValue('E'.$row, @$inisial[2]);
        }
            if ($ps != $pilihan[1]){   
                $objWorkSheet1->insertNewRowBefore($row,1);
                $objWorkSheet1->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A8'), 'A'.$row);
                $objWorkSheet1->setCellValue('A'.$row, $inisial[1]
                                    .' - '.$mayor[1]
                                    .' ('.$pilihan[1].')');
                $objWorkSheet1->mergeCells('A'.$row.':I'.$row);
                $row++;
            }
            $row++;
            $ps = $pilihan[1];
        }

        $column = 'G';
        
            if($ttd['jabatan'] == 'dekan'){
                $jabatan = 'Dekan '.$inisialfak;
            }else{
                $jabatan = 'an. Dekan '.$inisialfak;
                $objWorkSheet1->setCellValue($column.($row+4),'Wakil Dekan');
            }
            $nip = 'NIP. '.$ttd['nip'];
            $date='Bogor, '.date('d-m-Y');
            $objWorkSheet1->setCellValue($column.($row+2),$date);
            $objWorkSheet1->setCellValue($column.($row+3),$jabatan);
            $objWorkSheet1->setCellValue($column.($row+8),$ttd['nama']);
            $objWorkSheet1->setCellValue($column.($row+9),$nip);
                   
        $objWorkSheet1->removeRow($starRow-2,2);
        return $objWorkSheet1;
    }

    public function getExcelObjBahanSuratT1($fak='', $jenis='', $tahap,$periode)
    {
        $_POST['url'] = $tahap;

        $dirTemp = Yii::getAlias('@app').'/arsip/template/';
        if($jenis == 1){
            $title = 'DAFTAR CALON MAHASISWA - BAHAN SURAT PENERIMAAN';
            $filename = 'temp5.xlsx';
        }else{
            $title = 'DAFTAR CALON MAHASISWA - BAHAN SURAT PENOLAKAN';
            $filename = 'temp6.xlsx';
        }
        
        $objPHPExcel = $this->render($dirTemp,$filename);
        $fakultas = Fakultas::getFakultas($fak);
        
        
        foreach ($fakultas as $f){

            $accessRule = Yii::$app->user->identity->accessRole_id;
              if($accessRule < 4 || $accessRule == 8){
                  $_POST['filter'] = 'substring(ps.kode,1,1)';
                  $_POST['kode'] = $f['kode'];
              }

            // if($periode == '1')
            // {
                //data pilihan pertama
                $data = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                //data pilihan kedua
                $data1 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                //data menunda
                $data12 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=>(date('Y')-1)]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.statusMasuk_id'=>5]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                $data12 = ($periode == '1')? $data12 : '';

                //data tolak di verif
                $data13 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);}]
                                            )
                                ->where(['pendaftaran.verifikasiPMB'=>2])->orderBy(['ps.kode'=>SORT_ASC])->all();

                //data tolak pleno1
                $data14 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where(['pps.hasilKeputusan_id'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>1]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

         
            $objWorkSheet1 = $this->inputArrayToTemplateBahanSurat($objPHPExcel,$data,$data1,8,2,$jenis,$f['inisial'],$data12,$data13,$data14,$f['nama']);
            $objWorkSheet1  ->setCellValue('A1', strtoupper($title));
            $objWorkSheet1  ->setCellValue('A2', strtoupper('SELEKSI CALON MAHASISWA BARU PROGRAM DOKTOR (S2) SEKOLAH PASCASARJANA IPB SEMESTER GANJIL TAHUN AKADEMIK '.date('Y').'/'.(date('Y')+1)));
            $objWorkSheet1  ->setCellValue('A3', strtoupper($f['nama']));
            $objWorkSheet1->setTitle($f['inisial'].'_S2');
            $objPHPExcel->addSheet($objWorkSheet1);
            // if($periode == '1')
            // {
                // $data2 = ModelData::getPleno1Data(" and substring(p1.kode,1,1) = '$f[kode]' and or_account.strata ='S3'");
           $data2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                //data pilihan kedua
                $data21 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

                //data menunda
                $data22 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=>(date('Y')-1)]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.statusMasuk_id'=>5]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                $data22 = ($periode == '1')? $data22 : '';

                //data tolak di verif
                $data23 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);}]
                                            )
                                ->where(['pendaftaran.verifikasiPMB'=>2])->orderBy(['ps.kode'=>SORT_ASC])->all();

                //data tolak pleno1
                $data24 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode'],'strata'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where(['pps.hasilKeputusan_id'=>3]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>1]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();

            $objWorkSheet1 = $this->inputArrayToTemplateBahanSurat($objPHPExcel,$data2,$data21,8,3,$jenis,$f['inisial'],$data22,$data23,$data24,$f['nama']);
            $objWorkSheet1  ->setCellValue('A1', strtoupper($title));
            $objWorkSheet1  ->setCellValue('A2', strtoupper('SELEKSI CALON MAHASISWA BARU PROGRAM DOKTOR (S3) SEKOLAH PASCASARJANA IPB SEMESTER GANJIL TAHUN AKADEMIK '.date('Y').'/'.(date('Y')+1)));
            $objWorkSheet1  ->setCellValue('A3', strtoupper($f['nama']));
            $objWorkSheet1->setTitle($f['inisial'].'_S3');
            $objPHPExcel->addSheet($objWorkSheet1);        
        }
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->removeSheetByIndex(0);
        return $objPHPExcel;
    }

    public function inputArrayToTemplateBahanSurat($objPHPExcel,$arr,$arr2,$starRow,$strata,$jenis,$inisialfak,$arr3,$arr4,$arr5,$fakultas)
    {   
        $row = $starRow;
        $ps = '';
        ini_set('max_execution_time', 300);
        $objS2 = $objPHPExcel->getSheet(0);
        $objS3 = $objPHPExcel->getSheet(1);
        $objWorkSheet1 = clone $objS2;
        if ($strata == 3)
            $objWorkSheet1 = clone $objS3;
        $p = [4 => "BIASA",
                5 => "PERCOBAAN",
                6 => "DITOLAK",
                0 => '',
            ];
        $tingkat = ["2"=> "Magister Sains",
                  "3"=> "Doktor"];

        $ii = 1;
        foreach($arr as $r => $dataRow) {

        $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
            $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
            foreach($model['pendaftaranHasProgramStudis'] as $value){
                $pilihan[$i] = $value->programStudi->kode;
                $mayor[$i] = $value->programStudi->nama;
                $inisial[$i] = $value->programStudi->inisial;
                $strata = $value->programStudi->strata;
                $i++;
            }

            foreach($dataRow->orang->alamats as $alamat){
              $jalan = isset($alamat->jalan)? $alamat->jalan : '';
              $rt = isset($alamat->rt)? $alamat->rt : '-';
              $rw = isset($alamat->rw)? $alamat->rw : '-';
              $keldes = isset($alamat->desaKelurahanKode->namaID)? $alamat->desaKelurahanKode->namaID : '';
              $kec = isset($alamat->desaKelurahanKode->kecamatanKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->namaID : '';
              $kab = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID : '';
              $prov = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID : '';
              $pos = isset($alamat->kodePos)? $alamat->kodePos : '';
            }

            $list_kontak = $dataRow->orang->kontaks;
            foreach($list_kontak as $value){
                $jeniskontak = $value->jenisKontak_id;
                if($jeniskontak == 2){ // hp
                    $hp = isset($value->kontak)? $value->kontak : '';
                }
            }

            $idhasil='';
            $program = $dataRow->pendaftaranHasProgramStudis[0];
            $proses_sidang = $program->prosesSidangs;
            foreach($proses_sidang as $pr => $proses){
              $sidang = Sidang::findOne($proses->sidang_id);
              if($sidang->jenisSidang_id == 2){
                 $idhasil = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : null;
                 
              }
            }

        
        $nrp = new NrpGenerator();
        
        if(($jenis == 1 && ($idhasil == 4 || $idhasil == 5)) || ($jenis == 2 && $idhasil == 6 && ($pilihan[1] == $pilihan[2] || $pilihan[2] == '') )){

            $objWorkSheet1->insertNewRowBefore($row,1);

            if($jenis == 1){
                
                if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                    $objWorkSheet1->setCellValue('H'.$row , $p[$idhasil]);
                }
            $objWorkSheet1->setCellValue('A'.$row, $ii)
                                          ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                          ->setCellValue('C'.$row, $nrp->getNrp($dataRow['noPendaftaran']))
                                          ->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['nama']))
                                          ->setCellValue('E'.$row, $this->upfistarray($jalan.' Rt. '.$rt.'/'.$rw.' '.$keldes.', '.$kec.', '.$kab.', '.$prov.', '.$pos).'. HP '.$hp)
                                          ->setCellValue('F'.$row, $tingkat[$strata])
                                          ->setCellValue('G'.$row, $mayor[1].' ('.$inisial[1].')')
                                          ->setCellValue('I'.$row, $this->upfistarray($fakultas))
                                          ->setCellValue('J'.$row, substr($pilihan[1], 0,2))
                                          ->setCellValue('K'.$row, 'Terima pleno 2 pilihan 1');
            }else{
                if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                    $objWorkSheet1->setCellValue('G'.$row , $p[$idhasil]);
                }
            $objWorkSheet1->setCellValue('A'.$row, $ii)
                                          ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                          ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
                                          ->setCellValue('D'.$row, $this->upfistarray($jalan.' Rt. '.$rt.'/'.$rw.' '.$keldes.', '.$kec.', '.$kab.', '.$prov.', '.$pos).'. HP '.$hp)
                                          ->setCellValue('E'.$row, $tingkat[$strata])
                                          ->setCellValue('F'.$row, $mayor[1].' ('.$inisial[1].')')
                                          ->setCellValue('H'.$row, $this->upfistarray($fakultas))
                                          ->setCellValue('I'.$row, 'Tolak pleno 2 pilihan 1');

            }

          $row++;  $ii++;
        }
            
            
        }

        // pilihan kedua
        $ii = 1;
        foreach($arr2 as $r => $dataRow) {

          $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
            $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
            foreach($model['pendaftaranHasProgramStudis'] as $value){
                $pilihan[$i] = $value->programStudi->kode;
                $mayor[$i] = $value->programStudi->nama;
                $inisial[$i] = $value->programStudi->inisial;
                $strata = $value->programStudi->strata;
                $i++;
            }

            foreach($dataRow->orang->alamats as $alamat){
              $jalan = isset($alamat->jalan)? $alamat->jalan : '';
              $rt = isset($alamat->rt)? $alamat->rt : '-';
              $rw = isset($alamat->rw)? $alamat->rw : '-';
              $keldes = isset($alamat->desaKelurahanKode->namaID)? $alamat->desaKelurahanKode->namaID : '';
              $kec = isset($alamat->desaKelurahanKode->kecamatanKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->namaID : '';
              $kab = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID : '';
              $prov = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID : '';
              $pos = isset($alamat->kodePos)? $alamat->kodePos : '';
            }

            $list_kontak = $dataRow->orang->kontaks;
            foreach($list_kontak as $value){
                $jeniskontak = $value->jenisKontak_id;
                if($jeniskontak == 2){ // hp
                    $hp = isset($value->kontak)? $value->kontak : '';
                }
            }

            $idhasil='';
            $program = $dataRow->pendaftaranHasProgramStudis[0];
            $proses_sidang = $program->prosesSidangs;
            foreach($proses_sidang as $pr => $proses){
              $sidang = Sidang::findOne($proses->sidang_id);
              if($sidang->jenisSidang_id == 2){
                 $idhasil = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : null;
                 
              }
            }

        if ($pilihan[1] != $pilihan[2]) {
        
        if(($jenis == 1 && ($idhasil == 4 || $idhasil == 5)) || ($jenis == 2 && $idhasil == 6)){
            $objWorkSheet1->insertNewRowBefore($row,1);
            
            if($jenis == 1){
                
                if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                    $objWorkSheet1->setCellValue('H'.$row , $p[$idhasil]);
                }
            $objWorkSheet1->setCellValue('A'.$row, $ii)
                                          ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                          ->setCellValue('C'.$row, $nrp->getNrp($dataRow['noPendaftaran']))
                                          ->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['nama']))
                                          ->setCellValue('E'.$row, $this->upfistarray($jalan.' Rt. '.$rt.'/'.$rw.' '.$keldes.', '.$kec.', '.$kab.', '.$prov.', '.$pos).'. HP '.$hp)
                                          ->setCellValue('F'.$row, $tingkat[$strata])
                                          ->setCellValue('G'.$row, $mayor[2].' ('.$inisial[2].')')
                                          ->setCellValue('I'.$row, $this->upfistarray($fakultas))
                                          ->setCellValue('J'.$row, substr($pilihan[2], 0,2))
                                          ->setCellValue('K'.$row, 'Terima pleno 2 pilihan 2');
            }else{
                if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                    $objWorkSheet1->setCellValue('G'.$row , $p[$idhasil]);
                }
            $objWorkSheet1->setCellValue('A'.$row, $ii)
                                          ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                          ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
                                          ->setCellValue('D'.$row, $this->upfistarray($jalan.' Rt. '.$rt.'/'.$rw.' '.$keldes.', '.$kec.', '.$kab.', '.$prov.', '.$pos).'. HP '.$hp)
                                          ->setCellValue('E'.$row, $tingkat[$strata])
                                          ->setCellValue('F'.$row, $mayor[2].' ('.$inisial[2].')')
                                          ->setCellValue('H'.$row, $this->upfistarray($fakultas))
                                          ->setCellValue('I'.$row, 'Tolak pleno 2 pilihan 2');

            }

        $row++;  $ii++;                                
        }

        }
        }

        // menunda 
        if($arr3 != ''){
        $ii = 1;
        foreach($arr3 as $r => $dataRow) {
        

        $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
            $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
            foreach($model['pendaftaranHasProgramStudis'] as $value){
                $pilihan[$i] = $value->programStudi->kode;
                $mayor[$i] = $value->programStudi->nama;
                $inisial[$i] = $value->programStudi->inisial;
                $strata = $value->programStudi->strata;
                $i++;
            }

            foreach($dataRow->orang->alamats as $alamat){
              $jalan = isset($alamat->jalan)? $alamat->jalan : '';
              $rt = isset($alamat->rt)? $alamat->rt : '-';
              $rw = isset($alamat->rw)? $alamat->rw : '-';
              $keldes = isset($alamat->desaKelurahanKode->namaID)? $alamat->desaKelurahanKode->namaID : '';
              $kec = isset($alamat->desaKelurahanKode->kecamatanKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->namaID : '';
              $kab = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID : '';
              $prov = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID : '';
              $pos = isset($alamat->kodePos)? $alamat->kodePos : '';
            }

            $list_kontak = $dataRow->orang->kontaks;
            foreach($list_kontak as $value){
                $jeniskontak = $value->jenisKontak_id;
                if($jeniskontak == 2){ // hp
                    $hp = isset($value->kontak)? $value->kontak : '';
                }
            }

            $idhasil='';
            $program = $dataRow->pendaftaranHasProgramStudis[0];
            $proses_sidang = $program->prosesSidangs;
            foreach($proses_sidang as $pr => $proses){
              $sidang = Sidang::findOne($proses->sidang_id);
              if($sidang->jenisSidang_id == 2){
                 $idhasil = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : null;
                 
              }
            }
        
        if($jenis == 1){
            $objWorkSheet1->insertNewRowBefore($row,1);
            if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                    $objWorkSheet1->setCellValue('H'.$row , $p[$idhasil]);
                }  
            $objWorkSheet1->setCellValue('A'.$row, $ii)
                                          ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                          ->setCellValue('C'.$row, $nrp->getNrp($dataRow['noPendaftaran']))
                                          ->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['nama']))
                                          ->setCellValue('E'.$row, $this->upfistarray($jalan.' Rt. '.$rt.'/'.$rw.' '.$keldes.', '.$kec.', '.$kab.', '.$prov.', '.$pos).'. HP '.$hp)
                                          ->setCellValue('F'.$row, $tingkat[$strata])
                                          ->setCellValue('G'.$row, $mayor[1].' ('.$inisial[1].')')
                                          ->setCellValue('I'.$row, $this->upfistarray($fakultas))
                                          ->setCellValue('J'.$row, substr($pilihan[1], 0,2))
                                          ->setCellValue('K'.$row, 'Menunda tahun lalu');
        $row++;  $ii++; 
        }
        }
      }

      
        // tolak di verif 
        $ii = 1;
        foreach($arr4 as $r => $dataRow) {
          $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
            $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
            foreach($model['pendaftaranHasProgramStudis'] as $value){
                $pilihan[$i] = $value->programStudi->kode;
                $mayor[$i] = $value->programStudi->nama;
                $inisial[$i] = $value->programStudi->inisial;
                $strata = $value->programStudi->strata;
                $i++;
            }

            foreach($dataRow->orang->alamats as $alamat){
              $jalan = isset($alamat->jalan)? $alamat->jalan : '';
              $rt = isset($alamat->rt)? $alamat->rt : '-';
              $rw = isset($alamat->rw)? $alamat->rw : '-';
              $keldes = isset($alamat->desaKelurahanKode->namaID)? $alamat->desaKelurahanKode->namaID : '';
              $kec = isset($alamat->desaKelurahanKode->kecamatanKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->namaID : '';
              $kab = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID : '';
              $prov = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID : '';
              $pos = isset($alamat->kodePos)? $alamat->kodePos : '';
            }

            $list_kontak = $dataRow->orang->kontaks;
            foreach($list_kontak as $value){
                $jeniskontak = $value->jenisKontak_id;
                if($jeniskontak == 2){ // hp
                    $hp = isset($value->kontak)? $value->kontak : '';
                }
            }

            $idhasil='';
            $program = $dataRow->pendaftaranHasProgramStudis[0];
            $proses_sidang = $program->prosesSidangs;
            foreach($proses_sidang as $pr => $proses){
              $sidang = Sidang::findOne($proses->sidang_id);
              if($sidang->jenisSidang_id == 2){
                 $idhasil = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : null;
                 
              }
            }
        
        if($jenis == 2){
            // if(isset($mayor[1])) {
            //     $namaprodi = $mayor[1];
            //     $inisialprodi = $pilihan[1];
            // }else{
            //     $namaprodi = '';
            //     $inisialprodi = '';
            // }
            $objWorkSheet1->insertNewRowBefore($row,1);
            $objWorkSheet1->setCellValue('G'.$row, 'DITOLAK');  
            $objWorkSheet1->setCellValue('A'.$row, $ii)
                                          ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                          ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
                                          ->setCellValue('D'.$row, $this->upfistarray($jalan.' Rt. '.$rt.'/'.$rw.' '.$keldes.', '.$kec.', '.$kab.', '.$prov.', '.$pos).'. HP '.$hp)
                                          ->setCellValue('E'.$row, $tingkat[$strata])
                                          ->setCellValue('F'.$row, $mayor[1].' ('.$inisial[1].')')
                                          ->setCellValue('H'.$row, $this->upfistarray($fakultas))
                                          ->setCellValue('I'.$row, 'Tolak verifikasi');
        $row++;  $ii++; 
        }
        }

        // tolak di peno1 
        $ii = 1;
        foreach($arr5 as $r => $dataRow) {

          $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
            $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
            foreach($model['pendaftaranHasProgramStudis'] as $value){
                $pilihan[$i] = $value->programStudi->kode;
                $mayor[$i] = $value->programStudi->nama;
                $inisial[$i] = $value->programStudi->inisial;
                $strata = $value->programStudi->strata;
                $i++;
            }

            foreach($dataRow->orang->alamats as $alamat){
              $jalan = isset($alamat->jalan)? $alamat->jalan : '';
              $rt = isset($alamat->rt)? $alamat->rt : '-';
              $rw = isset($alamat->rw)? $alamat->rw : '-';
              $keldes = isset($alamat->desaKelurahanKode->namaID)? $alamat->desaKelurahanKode->namaID : '';
              $kec = isset($alamat->desaKelurahanKode->kecamatanKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->namaID : '';
              $kab = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID : '';
              $prov = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID : '';
              $pos = isset($alamat->kodePos)? $alamat->kodePos : '';
            }

            $list_kontak = $dataRow->orang->kontaks;
            foreach($list_kontak as $value){
                $jeniskontak = $value->jenisKontak_id;
                if($jeniskontak == 2){ // hp
                    $hp = isset($value->kontak)? $value->kontak : '';
                }
            }

            $idhasil='';
            $program = $dataRow->pendaftaranHasProgramStudis[0];
            $proses_sidang = $program->prosesSidangs;
            foreach($proses_sidang as $pr => $proses){
              $sidang = Sidang::findOne($proses->sidang_id);
              if($sidang->jenisSidang_id == 2){
                 $idhasil = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : null;
                 
              }
            }
        
        if($jenis == 2){
            // if(isset($dataRow['prodi1'])) {
            //     $namaprodi = $dataRow['prodi1'];
            //     $inisialprodi = $dataRow['kprodi1'];
            // }else{
            //     $namaprodi = '';
            //     $inisialprodi = '';
            // }
            $objWorkSheet1->insertNewRowBefore($row,1);
            $objWorkSheet1->setCellValue('G'.$row, 'DITOLAK');  
            $objWorkSheet1->setCellValue('A'.$row, $ii)
                                          ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                                          ->setCellValue('C'.$row, $this->upfistarray($dataRow['orang']['nama']))
                                          ->setCellValue('D'.$row, $this->upfistarray($jalan.' Rt. '.$rt.'/'.$rw.' '.$keldes.', '.$kec.', '.$kab.', '.$prov.', '.$pos).'. HP '.$hp)
                                          ->setCellValue('E'.$row, $tingkat[$strata])
                                          ->setCellValue('F'.$row, $mayor[1].' ('.$inisial[1].')')
                                          ->setCellValue('H'.$row, $this->upfistarray($fakultas))
                                          ->setCellValue('I'.$row, 'Tolak Pleno 1');
        $row++;  $ii++; 
        }
        }
        
        

                   
        $objWorkSheet1->removeRow($starRow-2,2);
        return $objWorkSheet1;
    }

    // print data semua bahan penerimaan
    public function getExcelObjBahanSuratAll($tahap,$tahun,$periode)
    {
      $_POST['url'] = $tahap;
      $_POST['tahun'] = $tahun;
      $jenis='1';
        $dirTemp = Yii::getAlias('@app').'/arsip/template/';
     
            $filename = 'temp7.xlsx';
        
        
        $objPHPExcel = $this->render($dirTemp,$filename);
        $fakultas = Fakultas::getFakultas();
        
        $row = 8;
         $ps = '';
            ini_set('max_execution_time', 700);
            $obj = $objPHPExcel->getSheet(0);
            $objWorkSheet1 = clone $obj;
            $p = [4 => "BIASA",
                  5 => "PERCOBAAN",
                  6 => "DITOLAK",
                  0 => '',
                 ];
            $tingkat = ["2"=> "Magister Sains",
                        "3"=> "Doktor"
                       ];

        foreach ($fakultas as $f){
           $fakultas = $f['nama'];
          $accessRule = Yii::$app->user->identity->accessRole_id;
              if($accessRule < 4 || $accessRule == 8){
                  $_POST['filter'] = 'substring(ps.kode,1,1)';
                  $_POST['kode'] = $f['kode'];
              }

                // $data1 = ModelData::getPleno1Data(" and substring(p1.kode,1,1) = '$f[kode]' and or_account.strata ='S2'");
            // $arr = Pleno2::getPleno2DataSandard(" and substring(p1.kode,1,1) = '$f[kode]' and pin.dateverifikasi >= '2016-01-18 00:00:00' order by p1.kode", '');
            //data pilihan pertama
            $arr = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                //data pilihan kedua
            // $arr2 = Pleno2::getPleno2DataHasilPs2(" and substring(p2.kode,1,1) = '$f[kode]' and pin.dateverifikasi >= '2016-01-18 00:00:00' order by p2.kode", '');
            //data pilihan kedua
            $arr2 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang.paketPendaftaran b'=>function($q){$q->where(['b.uniqueUrl'=>$_POST['url']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.id'=>null]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                //data menunda
            // $arr3 = ModelData::getPleno1Data(" and substring(p1.kode,1,1) = '$f[kode]' and pin.dateverifikasi < '2016-01-18 00:00:00'", '');
            //data menunda
                $arr3 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=>($_POST['tahun']-1)]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.statusMasuk_id'=>5]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                $arr3 = ($periode == 1)? $arr3 : '';

            //data menunda
                $arr4 = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=>($_POST['tahun']-1)]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>2]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where([$_POST['filter']=>$_POST['kode']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs.finalStatuses fs'=>function($q){$q->where(['fs.statusMasuk_id'=>5]);}]
                                            )
                                ->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
                $arr4 = ($periode == 1)? $arr4 : '';
            // $objWorkSheet1 = $this->inputArrayToTemplateBahanSurat($objPHPExcel,$data,$data1,8,$jenis,$f['inisial'],$data12,$data13,$data14,$f['fakultas']);

            // coba coba dulul

            
           
            $ii = 1;
            foreach($arr as $r => $dataRow) {

            $beasiswa = '';
            $beasiswa = @$dataRow['rencanaPembiayaan']['jenisPembiayan']['title'].' '.@$dataRow['rencanaPembiayaan']['deskripsi'];

            $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
            $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
            foreach($model['pendaftaranHasProgramStudis'] as $value){
                $pilihan[$i] = $value->programStudi->kode;
                $mayor[$i] = $value->programStudi->nama;
                $inisial[$i] = $value->programStudi->inisial;
                $strata = $value->programStudi->strata;
                $i++;
            }

            foreach($dataRow->orang->alamats as $alamat){
              $jalan = isset($alamat->jalan)? $alamat->jalan : '';
              $rt = isset($alamat->rt)? $alamat->rt : '-';
              $rw = isset($alamat->rw)? $alamat->rw : '-';
              $keldes = isset($alamat->desaKelurahanKode->namaID)? $alamat->desaKelurahanKode->namaID : '';
              $kec = isset($alamat->desaKelurahanKode->kecamatanKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->namaID : '';
              $kab = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID : '';
              $prov = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID : '';
              $pos = isset($alamat->kodePos)? $alamat->kodePos : '';
            }

            $list_kontak = $dataRow->orang->kontaks;
            foreach($list_kontak as $value){
                $jeniskontak = $value->jenisKontak_id;
                if($jeniskontak == 2){ // hp
                    $hp = isset($value->kontak)? $value->kontak : '';
                }
            }
			
			$kontak = []; $kontak[1] = $kontak[2] = $kontak[3]= ''; 
          foreach($dataRow['orang']['kontaks'] as $value){
            $kontak[$value->jenisKontak_id] = $value->kontak;
          }

            $idhasil='';
            $program = $dataRow->pendaftaranHasProgramStudis[0];
            $proses_sidang = $program->prosesSidangs;
            foreach($proses_sidang as $pr => $proses){
              $sidang = Sidang::findOne($proses->sidang_id);
              if($sidang->jenisSidang_id == 2){
                 $idhasil = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : null;
                 
              }
            }

        
        $nrp = new NrpGenerator();

                if(($jenis == 1 && ($idhasil == 4 || $idhasil == 5))){
                    $objWorkSheet1->insertNewRowBefore($row,1);

                    if($jenis == 1){
                        if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                            $objWorkSheet1->setCellValue('J'.$row , $p[$idhasil]);
                        }
                        $objWorkSheet1->setCellValue('A'.$row, $ii)
                        ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                        ->setCellValue('C'.$row, $nrp->getNrp($dataRow['noPendaftaran']))
                        ->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['nama']))
                        ->setCellValue('E'.$row, $this->upfistarray($jalan.' Rt. '.$rt.'/'.$rw.' '.$keldes.', '.$kec.', '.$kab.', '.$prov.', '.$pos).'. HP '.$hp)
						->setCellValue('F'.$row, @$kontak[1])
						->setCellValue('G'.$row, (@$dataRow['orang']['jenisKelamin']==1)? 'Laki-laki':'Perempuan')
                        ->setCellValue('H'.$row, $tingkat[$strata])
                        ->setCellValue('I'.$row, $mayor[1].' ('.$inisial[1].')')
                        ->setCellValue('K'.$row, $this->upfistarray($fakultas))
                        ->setCellValue('L'.$row, substr($pilihan[1], 0,2))
                        ->setCellValue('M'.$row, $beasiswa)
                        ->setCellValue('N'.$row, 'Terima pleno 2 pilihan 1');
                    }

                    $row++;  $ii++;
                }


            }

        // pilihan kedua
            $ii = 1;
            foreach($arr2 as $r => $dataRow) {
            
              $beasiswa = '';
              $beasiswa = @$dataRow['rencanaPembiayaan']['jenisPembiayan']['title'].' '.@$dataRow['rencanaPembiayaan']['deskripsi'];

              $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
              $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
              foreach($model['pendaftaranHasProgramStudis'] as $value){
                  $pilihan[$i] = $value->programStudi->kode;
                  $mayor[$i] = $value->programStudi->nama;
                  $inisial[$i] = $value->programStudi->inisial;
                  $strata = $value->programStudi->strata;
                  $i++;
              }

              foreach($dataRow->orang->alamats as $alamat){
                $jalan = isset($alamat->jalan)? $alamat->jalan : '';
                $rt = isset($alamat->rt)? $alamat->rt : '-';
                $rw = isset($alamat->rw)? $alamat->rw : '-';
                $keldes = isset($alamat->desaKelurahanKode->namaID)? $alamat->desaKelurahanKode->namaID : '';
                $kec = isset($alamat->desaKelurahanKode->kecamatanKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->namaID : '';
                $kab = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID : '';
                $prov = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID : '';
                $pos = isset($alamat->kodePos)? $alamat->kodePos : '';
              }

              $list_kontak = $dataRow->orang->kontaks;
              foreach($list_kontak as $value){
                  $jeniskontak = $value->jenisKontak_id;
                  if($jeniskontak == 2){ // hp
                      $hp = isset($value->kontak)? $value->kontak : '';
                  }
              }
			  
			  $kontak = []; $kontak[1] = $kontak[2] = $kontak[3]= ''; 
          foreach($dataRow['orang']['kontaks'] as $value){
            $kontak[$value->jenisKontak_id] = $value->kontak;
          }

              $idhasil='';
              $program = $dataRow->pendaftaranHasProgramStudis[0];
              $proses_sidang = $program->prosesSidangs;
              foreach($proses_sidang as $pr => $proses){
                $sidang = Sidang::findOne($proses->sidang_id);
                if($sidang->jenisSidang_id == 2){
                   $idhasil = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : null;
                   
                }
              }

                if ($pilihan[1] != $pilihan[2]) {
      
                    if(($jenis == 1 && ($idhasil == 4 || $idhasil == 5))){
                        $objWorkSheet1->insertNewRowBefore($row,1);

                        if($jenis == 1){
                            if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                                $objWorkSheet1->setCellValue('J'.$row, $p[$idhasil]);
                            }
                            $objWorkSheet1->setCellValue('A'.$row, $ii)
                            ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                            ->setCellValue('C'.$row, $nrp->getNrp($dataRow['noPendaftaran']))
                            ->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['nama']))
                            ->setCellValue('E'.$row, $this->upfistarray($jalan.' Rt. '.$rt.'/'.$rw.' '.$keldes.', '.$kec.', '.$kab.', '.$prov.', '.$pos).'. HP '.$hp)
							->setCellValue('F'.$row, @$kontak[1])
						    ->setCellValue('G'.$row, (@$dataRow['orang']['jenisKelamin']==1)? 'Laki-laki':'Perempuan')
                            ->setCellValue('H'.$row, $tingkat[$strata])
                            ->setCellValue('I'.$row, $mayor[2].' ('.$inisial[2].')')
                            ->setCellValue('K'.$row, $this->upfistarray($fakultas))
                            ->setCellValue('L'.$row, substr($pilihan[2], 0,2))
                            ->setCellValue('M'.$row, $beasiswa)
                            ->setCellValue('N'.$row, 'Terima pleno 2 pilihan 2');
                        }

                        $row++;  $ii++;                                
                    }

                }
            }

        // menunda 
        if($arr3 != ''){
            $ii = 1;
            foreach($arr3 as $r => $dataRow) {
              $beasiswa = '';
              $beasiswa = @$dataRow['rencanaPembiayaan']['jenisPembiayan']['title'].' '.@$dataRow['rencanaPembiayaan']['deskripsi'];

              $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
              $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
              foreach($model['pendaftaranHasProgramStudis'] as $value){
                  $pilihan[$i] = $value->programStudi->kode;
                  $mayor[$i] = $value->programStudi->nama;
                  $inisial[$i] = $value->programStudi->inisial;
                  $strata = $value->programStudi->strata;
                  $i++;
              }

              foreach($dataRow->orang->alamats as $alamat){
                $jalan = isset($alamat->jalan)? $alamat->jalan : '';
                $rt = isset($alamat->rt)? $alamat->rt : '-';
                $rw = isset($alamat->rw)? $alamat->rw : '-';
                $keldes = isset($alamat->desaKelurahanKode->namaID)? $alamat->desaKelurahanKode->namaID : '';
                $kec = isset($alamat->desaKelurahanKode->kecamatanKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->namaID : '';
                $kab = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID : '';
                $prov = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID : '';
                $pos = isset($alamat->kodePos)? $alamat->kodePos : '';
              }

              $list_kontak = $dataRow->orang->kontaks;
              foreach($list_kontak as $value){
                  $jeniskontak = $value->jenisKontak_id;
                  if($jeniskontak == 2){ // hp
                      $hp = isset($value->kontak)? $value->kontak : '';
                  }
              }
			  
			  $kontak = []; $kontak[1] = $kontak[2] = $kontak[3]= ''; 
          foreach($dataRow['orang']['kontaks'] as $value){
            $kontak[$value->jenisKontak_id] = $value->kontak;
          }

              $idhasil='';
              $program = $dataRow->pendaftaranHasProgramStudis[0];
              $proses_sidang = $program->prosesSidangs;
              foreach($proses_sidang as $pr => $proses){
                $sidang = Sidang::findOne($proses->sidang_id);
                if($sidang->jenisSidang_id == 2){
                   $idhasil = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : null;
                   
                }
              }

                if($jenis == 1){
                    $objWorkSheet1->insertNewRowBefore($row,1);

                    if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                      $objWorkSheet1->setCellValue('J'.$row, $p[$idhasil]);
                    }

                    $objWorkSheet1->setCellValue('A'.$row, $ii)
                    ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                    ->setCellValue('C'.$row, $nrp->getNrp($dataRow['noPendaftaran']))
                    ->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['nama']))
                    ->setCellValue('E'.$row, $this->upfistarray($jalan.' Rt. '.$rt.'/'.$rw.' '.$keldes.', '.$kec.', '.$kab.', '.$prov.', '.$pos).'. HP '.$hp)
					->setCellValue('F'.$row, @$kontak[1])
					->setCellValue('G'.$row, (@$dataRow['orang']['jenisKelamin']==1)? 'Laki-laki':'Perempuan')
                    ->setCellValue('H'.$row, $tingkat[$strata])
                    ->setCellValue('I'.$row, $mayor[1].' ('.$inisial[1].')')
                    ->setCellValue('K'.$row, $this->upfistarray($fakultas))
                    ->setCellValue('L'.$row, substr($pilihan[1], 0,2))
                    ->setCellValue('M'.$row, $beasiswa)
                    ->setCellValue('N'.$row, 'Menunda tahun lalu');
                    $row++;  $ii++; 
                }
            }
          }

          // menunda 
        if($arr4 != ''){
            // $ii = 1;
            foreach($arr4 as $r => $dataRow) {
              $beasiswa = '';
              $beasiswa = @$dataRow['rencanaPembiayaan']['jenisPembiayan']['title'].' '.@$dataRow['rencanaPembiayaan']['deskripsi'];

              $pilihan=$inisial=$mayor=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=''; $i = 1; 
              $model = Pendaftaran::find()->where(['noPendaftaran'=>$dataRow['noPendaftaran']])->one();
              foreach($model['pendaftaranHasProgramStudis'] as $value){
                  $pilihan[$i] = $value->programStudi->kode;
                  $mayor[$i] = $value->programStudi->nama;
                  $inisial[$i] = $value->programStudi->inisial;
                  $strata = $value->programStudi->strata;
                  $i++;
              }

              foreach($dataRow->orang->alamats as $alamat){
                $jalan = isset($alamat->jalan)? $alamat->jalan : '';
                $rt = isset($alamat->rt)? $alamat->rt : '-';
                $rw = isset($alamat->rw)? $alamat->rw : '-';
                $keldes = isset($alamat->desaKelurahanKode->namaID)? $alamat->desaKelurahanKode->namaID : '';
                $kec = isset($alamat->desaKelurahanKode->kecamatanKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->namaID : '';
                $kab = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID : '';
                $prov = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID : '';
                $pos = isset($alamat->kodePos)? $alamat->kodePos : '';
              }

              $list_kontak = $dataRow->orang->kontaks;
              foreach($list_kontak as $value){
                  $jeniskontak = $value->jenisKontak_id;
                  if($jeniskontak == 2){ // hp
                      $hp = isset($value->kontak)? $value->kontak : '';
                  }
              }
			  
			  $kontak = []; $kontak[1] = $kontak[2] = $kontak[3]= ''; 
          foreach($dataRow['orang']['kontaks'] as $value){
            $kontak[$value->jenisKontak_id] = $value->kontak;
          }

              $idhasil='';
              $program = $dataRow->pendaftaranHasProgramStudis[0];
              $proses_sidang = $program->prosesSidangs;
              foreach($proses_sidang as $pr => $proses){
                $sidang = Sidang::findOne($proses->sidang_id);
                if($sidang->jenisSidang_id == 2){
                   $idhasil = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : null;
                   
                }
              }

                if($jenis == 1){
                    $objWorkSheet1->insertNewRowBefore($row,1);

                    if(isset($idhasil) && $idhasil!=0 && $idhasil!=null){
                      $objWorkSheet1->setCellValue('J'.$row, $p[$idhasil]);
                    }

                    $objWorkSheet1->setCellValue('A'.$row, $ii)
                    ->setCellValue('B'.$row, $dataRow['noPendaftaran'])
                    ->setCellValue('C'.$row, $nrp->getNrp($dataRow['noPendaftaran']))
                    ->setCellValue('D'.$row, $this->upfistarray($dataRow['orang']['nama']))
                    ->setCellValue('E'.$row, $this->upfistarray($jalan.' Rt. '.$rt.'/'.$rw.' '.$keldes.', '.$kec.', '.$kab.', '.$prov.', '.$pos).'. HP '.$hp)
					->setCellValue('F'.$row, @$kontak[1])
					->setCellValue('G'.$row, (@$dataRow['orang']['jenisKelamin']==1)? 'Laki-laki':'Perempuan')
                    ->setCellValue('H'.$row, $tingkat[$strata])
                    ->setCellValue('I'.$row, $mayor[2].' ('.$inisial[2].')')
                    ->setCellValue('K'.$row, $this->upfistarray($fakultas))
                    ->setCellValue('L'.$row, substr($pilihan[2], 0,2))
                    ->setCellValue('M'.$row, $beasiswa)
                    ->setCellValue('N'.$row, 'Menunda tahun lalu pilihan 2');
                    $row++;  $ii++; 
                }
            }
          }


            // end coba coba dulu

        }
            $objWorkSheet1->removeRow(8-2,2);

            $objWorkSheet1->setTitle('Penerimaan S2 dan S3');
            $objWorkSheet1  ->setCellValue('A2', strtoupper('SELEKSI CALON MAHASISWA BARU SEKOLAH PASCASARJANA IPB SEMESTER GANJIL TAHUN AKADEMIK '.$tahun.'/'.($tahun+1)));
            $objPHPExcel->addSheet($objWorkSheet1);
                 
        
        $objPHPExcel->removeSheetByIndex(0);
        // $objPHPExcel->removeSheetByIndex(0);
        return $objPHPExcel;
    }

    public function save($objPHPExcel,$path)
    {
        $PHPExcel = new PHPExcel;
        
        //$IOF = $PHPExcel->getIOFactory();
        
        $IOF = $PHPExcel->getClassName('PHPExcel_IOFactory');
        
        $objWriter = $IOF->createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($path);
    }
    
    public static function downloadUrl($filedownload)
    {
        
        Yii::$app->assetManager->publish($filedownload);
        $path = Yii::$app->assetManager->getPublishedUrl($filedownload);
        
        return Yii::$app->request->hostInfo.$path;
        
    }
    public static function createObject()
    {        
        return new PHPExcel;
    }
}


?>