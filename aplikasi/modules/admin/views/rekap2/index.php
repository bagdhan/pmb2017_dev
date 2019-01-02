<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\bootstrap\Tabs;
use yii\googlechart\GoogleChart;
use yii\fontawesome\FA;
// use yii\bootstrap\BootstrapAsset; 
use app\assets\DataTableAsset;
use app\models\ModelData;
use yii\helpers\Url;

DataTableAsset::register($this);

$this->title = 'Rekap Periode 2';
$this->params['breadcrumbs'][] = $this->title;

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
                <h3 class="box-title"><i class="fa fa-tag"></i> Pendaftar T.A. 2016/2017 Yang Sudah Melakukan Verifikasi PIN Periode Pendaftaran Tahap 2</h3>
            </div>
        <div class="box-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1>

    <p>
        
    </p> -->
    <?php
               $fak = array();  array_push($fak,array('Fakultas','Jumlah')); 
               $faks2 = array();  array_push($faks2,array('Fakultas','Jumlah')); 
               $faks3 = array();  array_push($faks3,array('Fakultas','Jumlah')); 
               $pria = array();
               $wanita = array();
               $allpria = array();
               $allwanita = array();
               $jumalls2 = 0;
               $jumalls3 = 0;
               $filter="and pin.dateverifikasi > '2016-05-16 15:00:00'";
        foreach ($fakultas as $f){
            $jum = ModelData::getVerifikasiJum($f['kode'],'',$filter);
            $jums2 = ModelData::getVerifikasiJum($f['kode'],'S2',$filter);
            $jums3 = ModelData::getVerifikasiJum($f['kode'],'S3',$filter);
            $jums2lost = ModelData::getVerifikasiJum('','S2',$filter);
            $jums3lost = ModelData::getVerifikasiJum('','S3',$filter);
           array_push($fak,array($f['inisial'],count($jum)));
           array_push($faks2,array($f['inisial'],count($jums2)));
           array_push($faks3,array($f['inisial'],count($jums3)));
           $pria[$f['inisial']]['2']['jumlah'] = 0;
           $wanita[$f['inisial']]['2']['jumlah'] = 0;
           $pria[$f['inisial']]['3']['jumlah'] = 0;
           $wanita[$f['inisial']]['3']['jumlah'] = 0;

           //pleno
           $pria[$f['inisial']]['2']['pleno2'] = 0;
           $wanita[$f['inisial']]['2']['pleno2'] = 0;
           $pria[$f['inisial']]['3']['pleno2'] = 0;
           $wanita[$f['inisial']]['3']['pleno2'] = 0;

           
           foreach($jums2 as $s2){
                if($s2['jeniskelamin']==1 && ($s2['kirimberkas']==1 || $s2['kirimberkas']==2 )){
                    $pria[$f['inisial']]['2']['jumlah'] += 1;
                    $pria[$f['inisial']]['2']['pleno2'] += 1;
                }elseif($s2['jeniskelamin']==1 && ($s2['kirimberkas']==0 || $s2['kirimberkas']>2 )){
                    $pria[$f['inisial']]['2']['jumlah'] += 1;
                }elseif($s2['jeniskelamin']==0 && ($s2['kirimberkas']==1 || $s2['kirimberkas']==2 )){
                    $wanita[$f['inisial']]['2']['jumlah'] += 1;
                    $wanita[$f['inisial']]['2']['pleno2'] += 1;
                }elseif($s2['jeniskelamin']==0 && ($s2['kirimberkas']==0 || $s2['kirimberkas']>2 )){
                    $wanita[$f['inisial']]['2']['jumlah'] += 1;
                }
           }

           foreach($jums3 as $s3){
                if($s3['jeniskelamin']==1 && ($s3['kirimberkas']==1 || $s3['kirimberkas']==2 )){
                    $pria[$f['inisial']]['3']['jumlah'] += 1;
                    $pria[$f['inisial']]['3']['pleno2'] += 1;
                }elseif($s3['jeniskelamin']==1 && ($s3['kirimberkas']==0 || $s3['kirimberkas']>2)){
                    $pria[$f['inisial']]['3']['jumlah'] += 1;
                }elseif($s3['jeniskelamin']==0 && ($s3['kirimberkas']==1 || $s3['kirimberkas']==2 )){
                    $wanita[$f['inisial']]['3']['jumlah'] += 1;
                    $wanita[$f['inisial']]['3']['pleno2'] += 1;
                }elseif($s3['jeniskelamin']==0 && ($s3['kirimberkas']==0 || $s3['kirimberkas']>2)){
                    $wanita[$f['inisial']]['3']['jumlah'] += 1;
                }
           }
           $jumalls2+= count($jums2);
           $jumalls3+= count($jums3);
        }

        // masuk pleno1 tahap 2 dari periode 1
        $filter="and pin.dateverifikasi <= '2016-05-16 15:00:00'";
        foreach ($fakultas as $f){
            $jums2 = ModelData::getVerifikasiJum($f['kode'],'S2',$filter);
            $jums3 = ModelData::getVerifikasiJum($f['kode'],'S3',$filter);

           
           $pria[$f['inisial']]['2']['pleno1'] = 0;
           $wanita[$f['inisial']]['2']['pleno1'] = 0;
           $pria[$f['inisial']]['3']['pleno1'] = 0;
           $wanita[$f['inisial']]['3']['pleno1'] = 0;

           
           foreach($jums2 as $s2){
                if($s2['jeniskelamin']==1 && $s2['kirimberkas']==2 ){
                    $pria[$f['inisial']]['2']['pleno1'] += 1;
                }elseif($s2['jeniskelamin']==0 && $s2['kirimberkas']==2 ){
                    $wanita[$f['inisial']]['2']['pleno1'] += 1;
                }
           }

           foreach($jums3 as $s3){
                if($s3['jeniskelamin']==1 && $s3['kirimberkas']==2 ){
                    $pria[$f['inisial']]['3']['pleno1'] += 1;
                }elseif($s3['jeniskelamin']==0 && $s3['kirimberkas']==2 ){
                    $wanita[$f['inisial']]['3']['pleno1'] += 1;
                }
           }
        }

           

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

           foreach($jums2lost as $s2lost){
                if($s2lost['jeniskelamin']==1){
                    $allpria['nomayor']['2']['jumlah'] += 1;
                    if($s2lost['sumber_beasiswa']=='biayasendiri'){
                        $allpria['sendiri']['2']['jumlah'] += 1;
                    }elseif($s2lost['sumber_beasiswa']!='yayasanptsswasta'){
                        $allpria['lain']['2']['jumlah'] += 1;
                    }elseif($s2lost['sumber_beasiswa']!='departement'){
                        $allpria['kerjasama']['2']['jumlah'] += 1;
                    }
                }elseif($s2lost['jeniskelamin']==0){
                    $allwanita['nomayor']['2']['jumlah'] += 1;
                    if($s2lost['sumber_beasiswa']=='biayasendiri'){
                        $allwanita['sendiri']['2']['jumlah'] += 1;
                    }elseif($s2lost['sumber_beasiswa']!='yayasanptsswasta'){
                        $allwanita['lain']['2']['jumlah'] += 1;
                    }elseif($s2lost['sumber_beasiswa']!='departement'){
                        $allwanita['kerjasama']['2']['jumlah'] += 1;
                    }
                }
           }

           foreach($jums3lost as $s3lost){
                if($s3lost['jeniskelamin']==1){
                    $allpria['nomayor']['3']['jumlah'] += 1;
                    if($s3lost['sumber_beasiswa']=='biayasendiri'){
                        $allpria['sendiri']['3']['jumlah'] += 1;
                    }elseif($s3lost['sumber_beasiswa']!='yayasanptsswasta'){
                        $allpria['lain']['3']['jumlah'] += 1;
                    }elseif($s3lost['sumber_beasiswa']!='departement'){
                        $allpria['kerjasama']['3']['jumlah'] += 1;
                    }
                }elseif($s3lost['jeniskelamin']==0){
                    $allwanita['nomayor']['3']['jumlah'] += 1;
                    if($s3lost['sumber_beasiswa']=='biayasendiri'){
                        $allwanita['sendiri']['3']['jumlah'] += 1;
                    }elseif($s3lost['sumber_beasiswa']!='yayasanptsswasta'){
                        $allwanita['lain']['3']['jumlah'] += 1;
                    }elseif($s3lost['sumber_beasiswa']!='departement'){
                        $allwanita['kerjasama']['3']['jumlah'] += 1;
                    }
                }
           }

           $s2bp = $allpria['nomayor']['2']['jumlah'] + $allwanita['nomayor']['2']['jumlah'] - $jumalls2 ;
           $s3bp = $allpria['nomayor']['3']['jumlah'] + $allwanita['nomayor']['3']['jumlah'] - $jumalls3 ;
           array_push($faks2,array('belum pilih',$s2bp));
           array_push($faks3,array('belum pilih',$s3bp));
           array_push($fak,array('belum pilih',$s2bp + $s3bp));

    echo Tabs::widget([
    'items' => [
        [
            'label' => 'Tabel',
            'content' => Yii::$app->controller->renderPartial('rekap', ['pria'=>$pria, 'wanita'=>$wanita, 'allpria'=>$allpria, 'allwanita'=>$allwanita, 'fakultas'=>$fakultas]),
            'active' => true
        ],
        [
            'label' => 'Grafik',
            'content' => Yii::$app->controller->renderPartial('chart', ['faks2' => $faks2, 'faks3' => $faks3, 'fak' => $fak]),
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



  
