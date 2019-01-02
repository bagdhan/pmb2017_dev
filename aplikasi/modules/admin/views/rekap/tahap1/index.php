<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\bootstrap\Tabs;
// use yii\googlechart\GoogleChart;
// use yii\fontawesome\FA;
// use yii\bootstrap\BootstrapAsset; 
use app\adminlte\assets\plugin\DataTableAsset;
use app\models\Pendaftaran;
use yii\helpers\Url;

$this->params['sidebar'] = 0;

DataTableAsset::register($this);

$this->title = 'Rekap Periode 1';
$this->params['breadcrumbs'][] = $this->title;
$tahunakademik = $tahun.'/'.($tahun+1);

$this->registerCss("
        tfoot input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
            color: black;
        }
        tbody button 
        {
            margin: 5px;
            box-sizing: border-box;
        }
        #idtable .bottom {
            margin: 30px;
        }
        ");
function upfistarray($input){
    $pca = explode(' ', $input);
    foreach ($pca as $p)
        $g[]= ucfirst(strtolower($p));
    return join(' ',$g);
}

?>
<div class="box box-default color-palette-box">
               <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> Pendaftar T.A. <?=$tahunakademik?> Yang Sudah Melakukan Verifikasi PIN Periode Pendaftaran Tahap 1 </h3>
            </div>
        <div class="box-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1>

    <p>
        
    </p> -->
    <?php
               $fak = array();
               $faks2 = array(); 
               $faks3 = array();  
               $pria = array();
               $wanita = array();
               $allpria = array();
               $allwanita = array();
               $jumalls2 = 0;
               $jumalls3 = 0;

            //all data no mayor
               $allpria['nomayor']['2']['jumlah'] = 0;
               $allwanita['nomayor']['2']['jumlah'] = 0;
               $allpria['nomayor']['3']['jumlah'] = 0;
               $allwanita['nomayor']['3']['jumlah'] = 0;

            // all data beasiswa
               $allpria['sendiri']['2']['jumlah'] = 0;
               $allwanita['sendiri']['2']['jumlah'] = 0;
               $allpria['sendiri']['3']['jumlah'] = 0;
               $allwanita['sendiri']['3']['jumlah'] = 0;

               $allpria['kerjasama']['2']['jumlah'] = 0;
               $allwanita['kerjasama']['2']['jumlah'] = 0;
               $allpria['kerjasama']['3']['jumlah'] = 0;
               $allwanita['kerjasama']['3']['jumlah'] = 0;

               $allpria['lain']['2']['jumlah'] = 0;
               $allwanita['lain']['2']['jumlah'] = 0;
               $allpria['lain']['3']['jumlah'] = 0;
               $allwanita['lain']['3']['jumlah'] = 0;

        $filter="tahap1_".$tahun;
        foreach ($fakultas as $f){
            $jum = Pendaftaran::getVerifikasi($f['kode'],'',$filter);
            $jums2 = Pendaftaran::getVerifikasi($f['kode'],'2',$filter);
            $jums3 = Pendaftaran::getVerifikasi($f['kode'],'3',$filter);
            $jums2lost = Pendaftaran::getVerifikasi('','2',$filter);
            $jums3lost = Pendaftaran::getVerifikasi('','3',$filter);
           array_push($fak,array($f['inisial'],count($jum)));
           array_push($faks2,array($f['inisial'],count($jums2)));
           array_push($faks3,array($f['inisial'],count($jums3)));
           $pria[$f['inisial']]['2']['jumlah'] = 0;
           $wanita[$f['inisial']]['2']['jumlah'] = 0;
           $pria[$f['inisial']]['3']['jumlah'] = 0;
           $wanita[$f['inisial']]['3']['jumlah'] = 0;

           //pleno
           $pria[$f['inisial']]['2']['pleno'] = 0;
           $wanita[$f['inisial']]['2']['pleno'] = 0;
           $pria[$f['inisial']]['3']['pleno'] = 0;
           $wanita[$f['inisial']]['3']['pleno'] = 0;

           
           foreach($jums2 as $s2){
                $tahap_sidang = isset($s2->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title)? $s2->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title : '';
                if($s2['orang']['jenisKelamin']==1 && $s2['verifikasiPMB'] ==1 && ($s2['paketPendaftaran']['title'] =='Tahap 1' &&  $tahap_sidang =='Tahap 1' )){
                    $pria[$f['inisial']]['2']['jumlah'] += 1;
                    $pria[$f['inisial']]['2']['pleno'] += 1;
                }elseif($s2['orang']['jenisKelamin']==1 && (($s2['verifikasiPMB'] ==1 && $s2['paketPendaftaran']['title']=='Kelas Khusus') || ($s2['paketPendaftaran']['title'] =='Tahap 1' &&  $tahap_sidang =='Tahap 2') || $s2['verifikasiPMB'] ==0 || $s2['verifikasiPMB'] ==2 || empty($s2['verifikasiPMB']))){
                    $pria[$f['inisial']]['2']['jumlah'] += 1;
                }elseif($s2['orang']['jenisKelamin']==0 && $s2['verifikasiPMB'] ==1 && ($s2['paketPendaftaran']['title'] =='Tahap 1' &&  $tahap_sidang =='Tahap 1' )){
                    $wanita[$f['inisial']]['2']['jumlah'] += 1;
                    $wanita[$f['inisial']]['2']['pleno'] += 1;
                }elseif($s2['orang']['jenisKelamin']==0 && (($s2['verifikasiPMB'] ==1 && $s2['paketPendaftaran']['title']=='Kelas Khusus') || ($s2['paketPendaftaran']['title'] =='Tahap 1' &&  $tahap_sidang =='Tahap 2') || $s2['verifikasiPMB'] ==0  || $s2['verifikasiPMB'] ==2  || empty($s2['verifikasiPMB']))){
                    $wanita[$f['inisial']]['2']['jumlah'] += 1;
                }
           }

           foreach($jums3 as $s3){
                $tahap_sidang = isset($s3->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title)? $s3->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title : '';
                if($s3['orang']['jenisKelamin']==1 && $s3['verifikasiPMB'] ==1 && ($s3['paketPendaftaran']['title'] =='Tahap 1' && $tahap_sidang =='Tahap 1' )){
                    $pria[$f['inisial']]['3']['jumlah'] += 1;
                    $pria[$f['inisial']]['3']['pleno'] += 1;
                }elseif($s3['orang']['jenisKelamin']==1 && (($s3['verifikasiPMB'] ==1 && $s3['paketPendaftaran']['title']=='Kelas Khusus') || ($s3['paketPendaftaran']['title'] =='Tahap 1' && $tahap_sidang =='Tahap 2' ) || $s3['verifikasiPMB'] ==0 || $s3['verifikasiPMB'] ==2 || empty($s3['verifikasiPMB']))){
                    $pria[$f['inisial']]['3']['jumlah'] += 1;
                }elseif($s3['orang']['jenisKelamin']==0 && $s3['verifikasiPMB'] ==1 && ($s3['paketPendaftaran']['title'] =='Tahap 1' && $tahap_sidang =='Tahap 1' )){
                    $wanita[$f['inisial']]['3']['jumlah'] += 1;
                    $wanita[$f['inisial']]['3']['pleno'] += 1;
                }elseif($s3['orang']['jenisKelamin']==0 && (($s3['verifikasiPMB'] ==1 && $s3['paketPendaftaran']['title']=='Kelas Khusus') || ($s3['paketPendaftaran']['title'] =='Tahap 1' && $tahap_sidang =='Tahap 2' ) || $s3['verifikasiPMB'] ==0 || $s3['verifikasiPMB'] ==2  || empty($s3['verifikasiPMB']))){
                    $wanita[$f['inisial']]['3']['jumlah'] += 1;
                }
           }
           $jumalls2+= count($jums2);
           $jumalls3+= count($jums3);

           foreach($jums2 as $s2lost){ 
                if($s2lost['orang']['jenisKelamin']==1){
                    if($s2lost['rencanaPembiayaan']['jenisPembiayan_id']==1){
                        $allpria['sendiri']['2']['jumlah'] += 1;
                    }elseif($s2lost['rencanaPembiayaan']['jenisPembiayan_id']==8){
                        $allpria['lain']['2']['jumlah'] += 1;
                    }else{
                        $allpria['kerjasama']['2']['jumlah'] += 1;
                    }
                }elseif($s2lost['orang']['jenisKelamin']==0){
                    if($s2lost['rencanaPembiayaan']['jenisPembiayan_id']==1){
                        $allwanita['sendiri']['2']['jumlah'] += 1;
                    }elseif($s2lost['rencanaPembiayaan']['jenisPembiayan_id']==8){
                        $allwanita['lain']['2']['jumlah'] += 1;
                    }else{
                        $allwanita['kerjasama']['2']['jumlah'] += 1;
                    }
                }
           }

           foreach($jums3 as $s3lost){
                if($s3lost['orang']['jenisKelamin']==1){
                    if($s3lost['rencanaPembiayaan']['jenisPembiayan_id']==1){
                        $allpria['sendiri']['3']['jumlah'] += 1;
                    }elseif($s3lost['rencanaPembiayaan']['jenisPembiayan_id']==8){
                        $allpria['lain']['3']['jumlah'] += 1;
                    }else{
                        $allpria['kerjasama']['3']['jumlah'] +=1;
                    }
                }elseif($s3lost['orang']['jenisKelamin']==0){
                    if($s3lost['rencanaPembiayaan']['jenisPembiayan_id']==1){
                        $allwanita['sendiri']['3']['jumlah'] += 1;
                    }elseif($s3lost['rencanaPembiayaan']['jenisPembiayan_id']==8){
                        $allwanita['lain']['3']['jumlah'] += 1;
                    }else{
                        $allwanita['kerjasama']['3']['jumlah'] += 1;
                    }
                }
           }
        }

        foreach($jums2lost as $s2lost){ 
                if($s2lost['orang']['jenisKelamin']==1){
                    $allpria['nomayor']['2']['jumlah'] += 1;
                    if($s2lost['rencanaPembiayaan']['jenisPembiayan_id']==1){
                        $allpria['sendiri']['2']['jumlah'] += 1;
                    }elseif($s2lost['rencanaPembiayaan']['jenisPembiayan_id']==8){
                        $allpria['lain']['2']['jumlah'] += 1;
                    }else{
                        $allpria['kerjasama']['2']['jumlah'] += 1;
                    }
                }elseif($s2lost['orang']['jenisKelamin']==0){
                    $allwanita['nomayor']['2']['jumlah'] += 1;
                    if($s2lost['rencanaPembiayaan']['jenisPembiayan_id']==1){
                        $allwanita['sendiri']['2']['jumlah'] += 1;
                    }elseif($s2lost['rencanaPembiayaan']['jenisPembiayan_id']==8){
                        $allwanita['lain']['2']['jumlah'] += 1;
                    }else{
                        $allwanita['kerjasama']['2']['jumlah'] += 1;
                    }
                }
           }

           $s2bp = $allpria['nomayor']['2']['jumlah'] + $allwanita['nomayor']['2']['jumlah'] ;
           $s3bp = 0 ;
           array_push($faks2,array('belum pilih',$s2bp));
           array_push($faks3,array('belum pilih',$s3bp));
           array_push($fak,array('belum pilih',$s2bp + $s3bp));

    echo Tabs::widget([
    'items' => [
        [
            'label' => 'Tabel',
            'content' => Yii::$app->controller->renderPartial('tahap1/rekap', ['pria'=>$pria, 'wanita'=>$wanita, 'allpria'=>$allpria, 'allwanita'=>$allwanita, 'fakultas'=>$fakultas]),
            'active' => true
        ],
        [
            'label' => 'Grafik',
            'content' => Yii::$app->controller->renderPartial('tahap1/chart2', ['faks2' => $faks2, 'faks3' => $faks3, 'fak' => $fak]),
            'headerOptions' => ['style'=>''],
            'options' => ['id' => 'myveryownID'],
        ],
    ],
]);
?>
</div>
</div>

<?php 
    $this->registerJs("
        $(document).ready(function() {
                tabelajk();
        } );
        ", View::POS_END, 'runtable');
    
?>
<script>
function tabelajk()
{
    var table1 = $('#rekapfak').DataTable(
        {
            "columns": [
                { "width": "5%" , "className":'details-control' },
                { "width": "10%", "className":'details-control' }
                
              ],
              "paging": false,
              "info": false,
            "dom": '<"top"l>rt<"bottom"ip><"clear">',
  //           "createdRow": function( row, data, dataIndex ) {
  //   if ( data[3] == "S2" ) {
  //     $(row).addClass( 'important' );
  //   }
  // }
        }   
    );

    var table2 = $('#rekapbi').DataTable(
        {
            "columns": [
                { "width": "5%" , "className":'details-control' },
                { "width": "20%", "className":'details-control' },
                { "className":'details-control' },
                { "width": "8%", "className":'details-control' },
                { "width": "13%", "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "width": "10%" }
              ],
              "paging": false,
              "info": false,
            "dom": '<"top"l>rt<"bottom"ip><"clear">',
  //           "createdRow": function( row, data, dataIndex ) {
  //   if ( data[3] == "S2" ) {
  //     $(row).addClass( 'important' );
  //   }
  // }
        }   
    );
};


</script>



  
