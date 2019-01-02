<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use \app\modules\pleno\models\Table;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\pleno\models\Sidang */

$this->title = $model->jenisSidang->title;
$this->params['sidebar'] = 0;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sidang'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$urlto = \yii\helpers\Url::to();
$sidang_id = Yii::$app->getRequest()->getQueryParam('id');

$jenisSidang_id = $model->jenisSidang_id;

$tahun = !empty($_GET['tahun'])? $_GET['tahun'] : date('Y');
$tahap  =  !empty($_GET['tahap'])? $_GET['tahap'] : 'tahap1';
if($sidang_id>=100){
    $urlto = empty($_GET['tahun'])? str_replace(substr($urlto,-16),'',$urlto) : str_replace(substr($urlto,-27),'',$urlto);
}elseif($sidang_id>=10){
    $urlto = empty($_GET['tahun'])? str_replace(substr($urlto,-15),'',$urlto) : str_replace(substr($urlto,-26),'',$urlto);
}else{
    $urlto = empty($_GET['tahun'])? str_replace(substr($urlto,-14),'',$urlto) : str_replace(substr($urlto,-25),'',$urlto);
}
?>
<form action="#" method="get" class="sidebar-form">
<select class="form-control" id="pilihantahap">
        
        <option value='tahap1'> Tahap 1</option>
        <option value='tahap2'> Tahap 2</option>
        <option value='khusus'> Khusus</option>
		<option value='byResearch'> by Research</option>
        
        
    </select>
    </form>
<div class="sidang-view">

    <h1>Penerimaan Mahasiswa Baru <br/>
        <?= Html::encode($this->title . ' ' . $model->paketPendaftaran->title) ?>
    </h1>

    <p><?= Html::encode('') ?></p>

    <hr>

    <?php
    switch ($model->jenisSidang_id){
                case 1 : //pleno 1
                    $link = 'createberitaacarapleno';
                    break;
                case 2 : // pleno 2
                    $link = 'createberitaacarapleno2';
                    break;
                case 3 : // seleksi fakultas
                    $link = 'createberitaacaraseleksi';
                    break;
            }
    $button = $model->jenisSidang->title;
    $data = '<div class="row"><br/><div class="col-lg-3">
    <div class="form-group">
          <label>Pilih Fakultas</label>
          <select id="selectmulti" multiple class="form-control">
            <option value="A">FAPERTA</option>
            <option value="B">FKH</option>
            <option value="C">FPIK</option>
            <option value="D">FAPET</option>
            <option value="E">FAHUTAN</option>
            <option value="F">FATETA</option>
            <option value="G">FMIPA</option>
            <option value="H">FEM</option>
            <option value="I">FEMA</option>
            <option value="P">SPS</option>
        </select>
    </div>
    <div class="form-group">
      <label>Pilih Penandatangan</label>
      <select id="selectpejabat" class="form-control">
        <option value="1">Dekan</option>
        <option value="2">Wakil Dekan 1</option>
        <option value="3">Wakil Dekan 2</option>
    </select>
    </div><br/>'. Html::a('Cetak Berita Acara '.$button,['#'],['class'=>'btn btn-primary',
        'data-fak'=>'', 
        'data-tahap'=>$model->paketPendaftaran->uniqueUrl, 
        'data-action'=>'excelba',
        'data-url'=>\yii\helpers\Url::to([
            "/pleno/sidang/".$link]),
        ]).'
    <div id="hasilBAexl"></div>
    </div></div>';

    $data2 = '<div class="row"><br/><div class="col-lg-3">
    <div class="form-group">
      <label>Pilih Penandatangan</label>
      <select id="selectpejabat" class="form-control">
        <option value="1">Dekan</option>
        <option value="2">Wakil Dekan 1</option>
        <option value="3">Wakil Dekan 2</option>
    </select>
    </div><br/>'. Html::a('Cetak Berita Acara Seleksi',['#'],['class'=>'btn btn-primary',
        'data-fak'=>'', 
        'data-tahap'=>$model->paketPendaftaran->uniqueUrl, 
        'data-action'=>'excelba',
        'data-url'=>\yii\helpers\Url::to([
            "/pleno/sidang/createberitaacaraseleksi?fak=A"]),
        ]).'
    <div id="hasilBAexl"></div>
    </div></div>';

    $itab[] = [
        'label' => 'Daftar Mahasiswa Baru',
        'content' => \app\modules\pleno\models\ListMahasiswa::widget([
                'sidang' => $model,
        ]),
        'active' => true,
    ];
    $tahap = $_GET['tahap'];

        $itab[] = [
            'label' => 'Hasil',
            'url' => empty($_GET['tahun'])? ['/pleno/sidang/hasil','id'=>$_GET['id'],'tahap'=>$tahap] : ['/pleno/sidang/hasil','id'=>$_GET['id'],'tahap'=>$tahap,'tahun'=>$_GET['tahun']],
            'active' => false,
        ];

        

    echo Tabs::widget([
        'items' => $itab,
    ]);
    $url = yii\helpers\Url::to(["/pleno/sidang/sidangid"]);
    $jsScript = <<< JS
    $('#pilihantahap').val('$tahap');

    $('#pilihantahap').on('change', function(e) {
        var urlto = "$urlto"
        var tahun = "$tahun";
        var tahap = $(this).val();
        var jenisSidang = "$jenisSidang_id";
        var urlsidang = "$url";
        var data2 = {};
        data2['tahun'] = tahun;
        data2['tahap'] = tahap;
        data2['jenisSidang'] = jenisSidang;

        $.ajax({
        type: 'POST', 
        url: urlsidang,
        data: {'sm': data2},
        success: function(data, textStatus, jqXHR){
                console.log(jqXHR.status);
                var urlfull = urlto + data +"?tahap="+ tahap +"&tahun="+ tahun;
                window.location.href= urlfull;
                    
                
            }
        });

              
      
    });
JS;
    $this->registerJs("
        $jsScript;
        
        ", View::POS_END, 'runSidang');
    ?>


</div>
