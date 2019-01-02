<?php
use  yii\web\View;
use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\pleno1 */
$this->title = 'Hasil '.$model->jenisSidang->title;
$this->params['breadcrumbs'][] = ['label' => 'Tahap Seleksi Pendaftar', 'url' => ['/pleno/plenoslk/index','tahap'=>$tahap]];
$this->params['breadcrumbs'][] = $this->title;
$this->params['sidebar'] = 0;
$tahun = substr($tahap, 0, 4);
$periode = substr($tahap, 4, 5);

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

    <hr>
    <?php 

        $itab2[] = [
        'label' => 'Daftar Mahasiswa Baru',
        // 'content' => $this->render('data',['tahap'=>$tahap]),
        'url' => empty($_GET['tahun'])? ['/pleno/sidang/view','id'=>$id,'tahap'=>$tahap] : ['/pleno/sidang/view','id'=>$id,'tahap'=>$tahap,'tahun'=>$_GET['tahun']],
        'active' => false,
        ];

        $itab2[] = [
            'label' => 'Hasil',
            'url' => empty($_GET['tahun'])? ['/pleno/sidang/hasil','id'=>$id,'tahap'=>$tahap] : ['/pleno/sidang/hasil','id'=>$id,'tahap'=>$tahap,'tahun'=>$_GET['tahun']],
            'active' => true,
        ];

        echo Tabs::widget([
            'items' => $itab2,
        ]);
    
?>

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
    $data = '<div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-tag"></i> '.$this->title.' </h3>
    </div><div class="row"><br/><div class="col-lg-3">
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
    <br/></div></div>';

    $data2 = '<div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-tag"></i> '.$this->title.' </h3>
    </div><div class="row"><br/><div class="col-lg-3">
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
    <br/></div></div>';

    $data3 = '<div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-tag"></i> '.$this->title.' </h3>
    </div><div class="row"><br/><div class="col-lg-3">
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
    <br/></div><div class="col-lg-3">
    <div class="form-group">
          <label>Pilih Fakultas</label>
          <select id="selectfak" multiple class="form-control">
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
      <label>Jenis Surat</label>
      <select id="jenissurat" class="form-control">
        <option value="1">Penerimaan</option>
        <option value="2">Penolakan</option>
    </select>
    </div><br/>'. Html::a('Cetak Surat Hasil Seleksi',['#'],['class'=>'btn btn-primary',
        'data-fak'=>'', 
        'data-tahap'=>$model->paketPendaftaran->uniqueUrl, 
        'data-action'=>'wordsurat',
        'data-url'=>\yii\helpers\Url::to([
            "/pleno/sidang/createsurathasil"]),
        ]).'
    <div id="hasilSRTwrd"></div>
    <br/></div><div class="col-lg-3">'. Html::a('Data Penerimaan Mahasiswa Baru',['#'],['class'=>'btn btn-primary',
        'data-tahap'=>$model->paketPendaftaran->uniqueUrl, 
        'data-action'=>'excelall',
        'data-url'=>\yii\helpers\Url::to([
            "/pleno/sidang/createsurathasilall"]),
        ]).'

    <div id="hasilexcel"></div>
    <br/></div></div>';

                 $accessRoleId = Yii::$app->user->identity->accessRole_id;
                if($accessRoleId <= 3 || $accessRoleId ==8){
                    if($model->jenisSidang_id == 2){
                        echo Html::tag('div', "$data3", ['class' => "box box-info"]);
                    }else{
                        echo Html::tag('div', "$data", ['class' => "box box-info"]);
                    }
                }elseif($model->jenisSidang_id == 3){
                    echo Html::tag('div', "$data2", ['class' => "box box-info"]);
                }

                
                    ?>
                
<div class="box box-success color-palette-box">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-tag"></i> Rekap Statistik <?=$this->title ?> </h3>
    </div>
    <div class="box-body">
        <?php
        if($model->jenisSidang_id == 1){ // pleno 1
            $itab[] = [
                'label' => 'Grafik',
                'content' => '',
                'active' => false,
            ];
            $itab[] = [
                'label' => 'Tabel',
                'content' => '',
                'active' => true,
            ];
        }elseif($model->jenisSidang_id == 2){ // pleno 2
            $itab[] = [
                'label' => 'Grafik',
                'content' => '',
                'active' => false,
            ];
            $itab[] = [
                'label' => 'Tabel',
                'content' => $this->render('pleno2/tabel1', ['data' => $dataTable['data']]).
                             $this->render('pleno2/tabel2', ['data' => $dataTable2['data']]).
                             $this->render('pleno2/tabel2s3', ['data' => $dataTable2s3['data']]),
                'active' => true,
            ];
            
        }elseif($model->jenisSidang_id == 3){ // seleksi 
            $itab[] = [
                'label' => 'Grafik',
                'content' => '',
                'active' => false,
            ];
            $itab[] = [
                'label' => 'Tabel',
                'content' => $this->render('seleksi/tabel1', ['data' => $dataTable['data']]),
                'active' => true,
            ];
            
        }
        echo Tabs::widget([
            'items' => $itab,
        ]);

        ?>
    </div><!-- /.box-body -->
</div><!-- /.box -->
</div>

<script type="text/javascript">
function loading(block) {
        $(block).block({ 
            message: '<i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom"></i>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.5,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #fff'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none',
                color: '#000'
            }
        });
    };
function tabelajk(){
    $('[data-action=excel]').click(function (e) {
        e.preventDefault();
        $elemnet = $(e.target);
        fak = $elemnet.attr('data-fak');
        exurl = $elemnet.attr('data-url');
        //alert('aa ' + $elemnet.attr('data-url'));
        title = $elemnet.text();
        $.ajax({
        type: 'POST', 
        url: exurl,
        dataType: "json",
        beforeSend: function(){
                loading($elemnet);    
            },
        error: function(){
                alert('silahkan ulangi ...'); 
                $($elemnet).unblock();   
            },
        success: function(data){
                $($elemnet).replaceWith('<a class="btn btn-default btn-sm" href="'+
                data.link +
                '" role="button">'+title+' <br>download</a>');
                //$elemnet.attr("disabled","disabled");
            }
        });
    });

    $('[data-action=excelba]').click(function (e) {
        e.preventDefault();
        $elemnet = $(e.target);
        tahap2 = $elemnet.attr('data-tahap'); 
        fak = $elemnet.attr('data-fak');
        exurl = $elemnet.attr('data-url');
        
        select = $('#selectmulti');
        //console.log(select.val());
        //alert(select);die;
        filterurl='';dta='';
        if (select.val() != null){
            arrdta = select.val();
            dta = arrdta.join(',');
            filterurl='?fak='+dta;
        }

        select2 = $('#selectpejabat');
        //console.log(select.val());
        //alert(select);die;
        filterurl2='';dta2='';
        if (select2.val() != null){
            dta2 = select2.val();
            filterurl2='&jab='+dta2;
        }
        filterurl3='&tahap='+tahap2 ;
                //alert(filterurl);die;
        title = $elemnet.text();
        $.ajax({
        type: 'POST', 
        url: exurl+filterurl+filterurl2+filterurl3,
        dataType: "json",
        beforeSend: function(){
                loading($elemnet.parent());    
            },
        error: function(){
                alert('silahkan ulangi ...'); 
                $($elemnet.parent()).unblock();   
            },
        success: function(data){
                $("#hasilBAexl").html('<a class="btn btn-default btn-sm" href="'+
                data.link +
                '" role="button"> download Fak '+dta+'</a>');
                $($elemnet.parent()).unblock();
            }
        });
    });

    $('[data-action=wordsurat]').click(function (e) {
        e.preventDefault();
        $elemnet = $(e.target);
        per = $elemnet.attr('data-tahap');
        fak = $elemnet.attr('data-fak');
        exurl = $elemnet.attr('data-url');
        
        select = $('#selectfak');
        //console.log(select.val());
        //alert(select);die;
        filterurl='';dta='';
        if (select.val() != null){
            arrdta = select.val();
            dta = arrdta.join(',');
            filterurl='?fak='+dta;
        }

        select2 = $('#jenissurat');
        //console.log(select.val());
        //alert(select);die;
        filterurl2='';dta2='';
        if (select2.val() != null){
            dta2 = select2.val();
            filterurl2='&jenis='+dta2;
        }

        filterurl3 = '&tahap='+per;

                //alert(filterurl);die;
        title = $elemnet.text();
        $.ajax({
        type: 'POST', 
        url: exurl+filterurl+filterurl2+filterurl3,
        dataType: "json",
        beforeSend: function(){
                loading($elemnet.parent());    
            },
        error: function(){
                alert('silahkan ulangi ...'); 
                $($elemnet.parent()).unblock();   
            },
        success: function(data){
                $("#hasilSRTwrd").html('<a class="btn btn-default btn-sm" href="'+
                data.link +
                '" role="button"> download surat Fak '+dta+'</a>');
                $($elemnet.parent()).unblock();
            }
        });
    });

    $('[data-action=excelall]').click(function (e) {
        e.preventDefault();
        $elemnet = $(e.target);
        per = $elemnet.attr('data-tahap');
        exurl = $elemnet.attr('data-url');
        
        //alert(filterurl);die;
        filterurl = '?tahap='+per;
        title = $elemnet.text();
        $.ajax({
        type: 'POST', 
        url: exurl+filterurl,
        dataType: "json",
        beforeSend: function(){
                loading($elemnet.parent());    
            },
        error: function(){
                alert('silahkan ulangi ...'); 
                $($elemnet.parent()).unblock();   
            },
        success: function(data){
                $("#hasilexcel").html('<a class="btn btn-default btn-sm" href="'+
                data.link +
                '" role="button"> download all Data</a>');
                $($elemnet.parent()).unblock();
            }
        });
    });
}
</script>

<?php 
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
        $(document).ready(function() {
                tabelajk();
                
        } );
        $jsScript;
        ", View::POS_END, 'runtable');
   
    
?>