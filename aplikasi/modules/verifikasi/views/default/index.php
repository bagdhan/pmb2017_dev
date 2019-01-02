<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\bootstrap\Tabs;

// use yii\bootstrap\BootstrapAsset; 
use app\adminlte\assets\plugin\DataTableAsset;
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
                <h3 class="box-title"><i class="fa fa-tag"></i> Data LOG Proses Verifikasi</h3>
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
    $('.table tfoot th').each( function () {
        var title = $(this).text();
        if(title == 'Surat'){
            $(this).html( '<select name="formulir" > <option value="">Pilih Semua</option> <option value="0">Belum Download</option> <option value="1">Sudah Download</option></select>' );
        }else if(title == 'Strata'){
            $(this).html( '<select name="strata" > <option value="">Pilih Semua</option> <option value="S2">Magister</option> <option value="S3">Doktor</option></select>' );
        }else if (title != '' && title != 'No'){
            $(this).html( '<input type="text" placeholder=" '+title+'" />' );
        }else{
            $(this).html( '' );
        }
    } );
  
    // DataTable
    var table = $('#tabeldata').DataTable(
        {
            "columns": [
                { "width": "5%" , "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "className":'details-control' },
                { "width": "5%", "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "width": "10%", "className":'details-control' }
              ],
            "dom": '<"top"l>rt<"bottom"ip><"clear">',
  //           "createdRow": function( row, data, dataIndex ) {
  //   if ( data[3] == "S2" ) {
  //     $(row).addClass( 'important' );
  //   }
  // }
        }   
    );

    // Apply the search
    table.columns().every( function () {
        var that = this;
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
        $( 'select', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

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
 

 $('[data-action=absen]').click(function (e) {
        e.preventDefault();
        $elemnet = $(e.target);
        exurl = $elemnet.attr('data-url');
 
        $.ajax({
        type: 'POST', 
        url: exurl,
        dataType: "json",
        beforeSend: function(){
                loading($elemnet.parent());
            },
        error: function(){
                alert('silahkan ulangi ...'); 
                $($elemnet.parent()).unblock();   
            },
  
        success: function(data){
                $("#hasil").html('<a class="btn btn-default btn-sm" href="'+
                data.link +
                '" role="button"> download</a>');
                $($elemnet.parent()).unblock();
            }
        });
    });

};




</script>
