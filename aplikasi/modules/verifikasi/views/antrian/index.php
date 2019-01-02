<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\bootstrap\Tabs;
use yii\fontawesome\FA;
// use yii\bootstrap\BootstrapAsset; 
use app\assets\DataTableAsset;
use yii\helpers\Url;
Html::csrfMetaTags();

DataTableAsset::register($this);

$this->title = 'Proses Verifikasi Calon Mahasiswa T.A. 2016/2017';
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
                <h3 class="box-title"><i class="fa fa-tag"></i> Daftar Antrian Masuk ke-M1</h3>
            </div>
        <div class="box-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1>

    <p>
        
    </p> -->
    <?php
$jumdata = sizeof($data);
echo Yii::$app->controller->renderPartial('pengumuman', array('data' => $data));?>
</div>
</div>


  

<?php 


    $this->registerJs("
        $(document).ready(function() {
                tabelajk();
                reload();
        } );
        ", View::POS_END, 'runtable');
    
?>
<script>


function tabelajk()
{
  
    // DataTable
    var table = $('#tabeldata').DataTable(
        {
            "columns": [
                
                { "width": "10%", "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "className":'details-control' },
                { "width": "5%", "className":'details-control' },
                { "width": "5%", "className":'details-control' },
                { "width": "35%", "className":'details-control' }
              ],
            "dom": '<"top"l>rt<"bottom"ip><"clear">',
  //           "createdRow": function( row, data, dataIndex ) {
  //   if ( data[3] == "S2" ) {
  //     $(row).addClass( 'important' );
  //   }
  // }
        }   
    );
 

};

function reload(){
setTimeout(function(){
    var dataawal = "<?= $jumdata;?>";
    $.ajax({
        type: 'POST', 
        url: '<?= yii\helpers\Url::to(["/verifikasi/antrian/jumlahantrian"]);?>',
        data: {'sm': dataawal},
        success: function(data){
                
                if(dataawal != data) {
                    window.location.reload(1);
                }else{
                    reload();
                     console.log('Received: ' + data);
                }
            }
        });
   
}, 5000);
};

// setTimeout(function(){
//    window.location.reload(1);
// }, 30000);



</script>
