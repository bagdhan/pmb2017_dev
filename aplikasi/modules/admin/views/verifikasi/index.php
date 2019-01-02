<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\bootstrap\Tabs;
// use yii\googlechart\GoogleChart;
// use yii\fontawesome\FA;
// use yii\bootstrap\BootstrapAsset; 
// use app\adminlte\assets\plugin\DataTableAsset;
// use app\models\ModelData;
use yii\helpers\Url;

// DataTableAsset::register($this);
$this->params['sidebar'] = 0;
$this->title = 'Sudah Verifikasi';
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
                <h3 class="box-title"><i class="fa fa-tag"></i> Pendaftar T.A. 2017/2018 Yang Sudah Melakukan Verifikasi PIN </h3>
            </div>
        <div class="box-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1>

    <p>
        
    </p> -->
    <?php
echo Yii::$app->controller->renderPartial('verifikasi', array('data' => $data));?>
</div>

</div>
<div id="dataurl" data-detail='<?= \yii\helpers\Url::to(["/admin/verifikasi/detaildata"]) ?>'
    >
    <div style="display: none;" id="isiklik">
        
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
  $('.table tfoot th').each( function () {
        var title = $(this).text();
        if(title == 'Status'){
            $(this).html( '<select name="status" > <option value="">Pilih Semua</option> <option value="1">x : IPK & Mayor </option> <option value="2">x : Mayor </option> <option value="3">x : IPK </option> <option value="4">v : IPK & Mayor </option><option value="5">Indikasi DO </option></select>' );
        }else if(title == 'Formulir A'){
            $(this).html( '<select name="formulir" > <option value="">Pilih Semua</option> <option value="0">Belum Lengkap</option> <option value="1">Lengkap</option></select>' );
        }else if(title == 'Pleno 1'){
            $(this).html( '<select name="pleno" > <option value="">Pilih Semua</option> <option value="Belum">Belum Pilih</option> <option value="Tahap 1">Tahap 1</option><option value="Tahap 2">Tahap 2</option><option value="Khusus">Khusus</option><option value="by Research">by Research</option><option value="Tidak">Tidak Memenuhi</option></select>' );
        }else if(title == 'Berkas'){
            $(this).html( '<select name="berkas" > <option value="">Pilih Semua</option> <option value="0">Belum Lengkap</option> <option value="1">Lengkap</option></select>' );
        }else if(title == 'Tahap'){
            $(this).html( '<select name="periode" > <option value="">Pilih Semua</option> <option value="2016">Menunda</option> <option value="1">1</option><option value="2">2</option><option value="Khusus">Khusus</option><option value="by Research">by Research</option></select>' );
        }else if (title != '' && title != 'No'){
            $(this).html( '<input type="text" placeholder=" '+title+'" />' );
        }else{
            $(this).html( '' );
        }
    } );
    var urlDOM = $('#dataurl');
    var detailurl = urlDOM.attr('data-detail');
    // DataTable
    var table = $('#tabeldata').DataTable(
        {
            "columns": [
                { "width": "5%" , "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "className":'details-control' },
                { "width": "5%", "className":'details-control' },
                { "width": "5%", "className":'details-control' },
                { "width": "5%", "className":'details-control' },
                { "width": "5%", "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "width": "10%", "className":'details-control' },
                { "width": "5%", "className":'details-control' },
                { "width": "5%", "className":'details-control' },
                { "width": "5%" }
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
    $('#tabeldata tbody tr').each(function () {
        $(this).attr('title','Silakan click untuk melihat detail ');
        // $(this).attr('data-placement','center');
        // $(this).tooltip();
        $(this).tooltip({
               content: "Silakan click untuk melihat detail ",
               track:true
            });
    });

    $('#tabeldata  tbody').on('click', 'td.details-control', function () {
        var tr  = $(this).closest('tr');
                var row = table.row(tr);
                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    table.rows().every(function () {
                        if (this.child.isShown()) {
                            var tr2 = $(this).closest('tr');
                            tr2.removeClass('shown');
                            this.child.hide();
                        }
                    });
                    // Open this row
                    art = $(this).closest('td').attr('name'); //ini ambil no pendaftaran

                    if(row.child() == undefined){
                        row.child(urlDOM.find('#isiklik').html());
                        $.post(detailurl,{'data':{"nop":art}},function(dataresponse, status, xhr) {
                            if(status == "success")
                                row.child(dataresponse);
                            if(status == "error")
                                alert("Error: " + xhr.status + ": " + xhr.statusText);
                        });
                    }
                    row.child.show();
                    
                    //console.log('aaaa '+art);
                    tr.addClass('shown');
                }
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
    
$('[data-action=backup]').click(function (e) {
        e.preventDefault();
        $elemnet = $(e.target);
        exurl = $elemnet.attr('data-url');
        //alert('aa ' + $elemnet.attr('data-url'));
        select = $('#selectfak');
        //console.log(select.val());
        //alert(select);die;
        filterurl='';dta='';
        if (select.val() != null){
            arrdta = select.val();
            if(arrdta == 'Belum'){
                dta = arrdta;
            }else{
                dta = arrdta.join(',');
            }
            filterurl='?fak='+dta;
        }

        select2 = $('#selecttahap');
        //console.log(select.val());
        //alert(select);die;
        filterurl2='';dta2='';
        if (select2.val() != null){
            dta2 = select2.val();
            filterurl2='&tahap='+dta2;
        }

        select3 = $('#tahun');
        filterurl3='';dta3='';
        if (select3.val() != null){
            dta3 = select3.val();
            filterurl3='&tahun='+dta3;
        }

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
        // success: function(data){
        //         $($elemnet).replaceWith('<a class="btn btn-default btn-sm" href="'+
        //         data.link +
        //         '" role="button">'+title+' <br>download</a>');
        //         //$elemnet.attr("disabled","disabled");
        //     }
        success: function(data){
                $("#hasil").html('<a class="btn btn-default btn-sm" href="'+
                data.link +
                '" role="button"> download Fak '+dta+'</a>');
                $($elemnet.parent()).unblock();
            }
        });
    });

};
// function getDetile(urk){
//     var isi = document.getElementById("isiklik");
//     $.ajax({
//         type: 'POST', 
//         url: urk,
//         beforeSend: function(){
//                 loading('#tabeldata');    
//             },
//         error: function(){
//                 alert('tidak bisa mengload data...'); 
//                 $('#tabeldata').unblock();   
//             },
//         success: function(data){
//                 isi.innerHTML=data;
//                 $('#tabeldata').unblock();
//             }
//         });
//    };
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

var data2 = {};
function submit_strata(value, nopend){
       
        path = 'strata_'+nopend+' option[value="'+value+'"]';
        path2 = 'strata_'+nopend+'_save';
        data2['value'] = value;
        data2['nopend'] = nopend;
        data2['kode'] = 'strata';
        $.ajax({
        type: 'POST', 
        url: '<?= yii\helpers\Url::to(["/pendaftar/verifikasi/updatedata"]);?>',
        data: {'sm': data2},
        success: function(){
                if(value == 'S3'){
                path3 = 'strata_'+nopend+' option[value="S2"]';
                $('#'+path).attr("selected","selected");
                $('#'+path3).attr("selected",false);
              }else{
                path4 = 'strata_'+nopend+' option[value="S3"]';
                $('#'+path).attr("selected","selected");
                $('#'+path4).attr("selected",false);
              }
              $('#'+path2).attr("style","display:true");
                
            }
        });
       
    
        
};

function submit_lengkap(value, nopend){
       
        path = 'lengkap_'+nopend+' option[value="'+value+'"]';
        path2 = 'lengkap_'+nopend+'_save';
        data2['value'] = value;
        data2['nopend'] = nopend;
        data2['kode'] = 'lengkap';
        $.ajax({
        type: 'POST', 
        url: '<?= yii\helpers\Url::to(["/admin/verifikasi/updatedata"]);?>',
        data: {'sm': data2},
        success: function(){
            if(value == 2){
                path3 = 'lengkap_'+nopend+' option[value="1"]';
                $('#'+path).attr("selected","selected");
                $('#'+path3).attr("selected",false);
              }else{
                path4 = 'lengkap_'+nopend+' option[value="2"]';
                $('#'+path).attr("selected","selected");
                $('#'+path4).attr("selected",false);
              }
                $('#'+path2).attr("style","display:true");
                //  alert( 'NoPendaftaran '+name+ ' berhasil ubah '+name
                // );
                
            }
        });
        
};

function submit_berkas(value, nopend){
       
        path = 'berkas_'+nopend+' option[value="'+value+'"]';
        path2 = 'berkas_'+nopend+'_save';
        data2['value'] = value;
        data2['nopend'] = nopend;
        data2['kode'] = 'berkas';
        $.ajax({
        type: 'POST', 
        url: '<?= yii\helpers\Url::to(["/admin/verifikasi/updatedata"]);?>',
        data: {'sm': data2},
        success: function(){
            if(value == 2){
                path3 = 'berkas_'+nopend+' option[value="1"]';
                $('#'+path).attr("selected","selected");
                $('#'+path3).attr("selected",false);
              }else{
                path4 = 'berkas_'+nopend+' option[value="2"]';
                $('#'+path).attr("selected","selected");
                $('#'+path4).attr("selected",false);
              }
                $('#'+path2).attr("style","display:true");
                //  alert( 'NoPendaftaran '+name+ ' berhasil ubah '+name
                // );
                
            }
        });
        
};

function submit_do(value, nopend){
       
        path = 'do_'+nopend+' option[value="'+value+'"]';
        path2 = 'do_'+nopend+'_save';
        data2['value'] = value;
        data2['nopend'] = nopend;
        data2['kode'] = 'do';
        $.ajax({
        type: 'POST', 
        url: '<?= yii\helpers\Url::to(["/admin/verifikasi/updatedata"]);?>',
        data: {'sm': data2},
        success: function(){
            if(value == 2){
                path3 = 'do_'+nopend+' option[value="1"]';
                $('#'+path).attr("selected","selected");
                $('#'+path3).attr("selected",false);
              }else{
                path4 = 'do_'+nopend+' option[value="2"]';
                $('#'+path).attr("selected","selected");
                $('#'+path4).attr("selected",false);
              }
                $('#'+path2).attr("style","display:true");
                //  alert( 'NoPendaftaran '+name+ ' berhasil ubah '+name
                // );
                
            }
        });
        
};

function submit_pleno(value, nopend){
       
        path = 'pleno_'+nopend+' option[value="'+value+'"]';
        path2 = 'pleno_'+nopend+'_save';
        data2['value'] = value;
        data2['nopend'] = nopend;
        data2['kode'] = 'pleno';
        $.ajax({
        type: 'POST', 
        url: '<?= yii\helpers\Url::to(["/admin/verifikasi/updatedata"]);?>',
        data: {'sm': data2},
        success: function(){
            path3 = 'pleno_'+nopend+' option[value="1"]';
            path4 = 'pleno_'+nopend+' option[value="2"]';
            path5 = 'pleno_'+nopend+' option[value="3"]';
            path6 = 'pleno_'+nopend+' option[value="4"]';
            path7 = 'pleno_'+nopend+' option[value="5"]';
			path8 = 'pleno_'+nopend+' option[value="6"]';
            if(value == 2){
                $('#'+path).attr("selected","selected");
				$('#'+path8).attr("selected",false);
                $('#'+path7).attr("selected",false);
                $('#'+path6).attr("selected",false);
                $('#'+path5).attr("selected",false);
                $('#'+path3).attr("selected",false);
                
            }else{

                if(value == 3){
                    $('#'+path).attr("selected","selected");
                    $('#'+path3).attr("selected",false);
                    $('#'+path4).attr("selected",false);
                    $('#'+path6).attr("selected",false);
                    $('#'+path7).attr("selected",false);
					$('#'+path8).attr("selected",false);
                }else{
                    if(value == 4){
                        $('#'+path).attr("selected","selected");
                        $('#'+path3).attr("selected",false);
                        $('#'+path4).attr("selected",false);
                        $('#'+path5).attr("selected",false);
                        $('#'+path7).attr("selected",false);
						$('#'+path8).attr("selected",false);
                    }else{
                        if(value == 5){
                            $('#'+path).attr("selected","selected");
                            $('#'+path3).attr("selected",false);
                            $('#'+path4).attr("selected",false);
                            $('#'+path5).attr("selected",false);
                            $('#'+path6).attr("selected",false);
							$('#'+path8).attr("selected",false);
                            
                        }else{
							if(value == 6){
								$('#'+path).attr("selected","selected");
								$('#'+path3).attr("selected",false);
								$('#'+path4).attr("selected",false);
								$('#'+path5).attr("selected",false);
								$('#'+path6).attr("selected",false);
								$('#'+path7).attr("selected",false);
							
							}else{
								$('#'+path).attr("selected","selected");
								$('#'+path4).attr("selected",false);
								$('#'+path5).attr("selected",false);
								$('#'+path6).attr("selected",false);
								$('#'+path7).attr("selected",false);
								$('#'+path8).attr("selected",false);
                            
                        }
                    }
                  }
				}
            }

                $('#'+path2).attr("style","display:true");
                //  alert( 'NoPendaftaran '+name+ ' berhasil ubah '+name
                // );
                
            }
        });
        
};

function submit_pindah(value, nopend){
       
        path = 'pindah_'+nopend+' option[value="'+value+'"]';
        path2 = 'pindah_'+nopend+'_save';
        data2['value'] = value;
        data2['nopend'] = nopend;
        data2['kode'] = 'pindah';
        $.ajax({
        type: 'POST', 
        url: '<?= yii\helpers\Url::to(["/admin/verifikasi/updatedata"]);?>',
        data: {'sm': data2},
        success: function(){
            path3 = 'pindah_'+nopend+' option[value="1"]';
            path4 = 'pindah_'+nopend+' option[value="2"]';
            path5 = 'pindah_'+nopend+' option[value="3"]';
			path6 = 'pindah_'+nopend+' option[value="4"]';
            if(value == 2){
                $('#'+path).attr("selected","selected");
				$('#'+path6).attr("selected",false);
                $('#'+path5).attr("selected",false);
                $('#'+path3).attr("selected",false);
            }else{
				if(value == 3){
                    $('#'+path).attr("selected","selected");
                    $('#'+path3).attr("selected",false);
                    $('#'+path4).attr("selected",false);
					$('#'+path6).attr("selected",false);
				}else{
					if(value == 4){
						$('#'+path).attr("selected","selected");
						$('#'+path3).attr("selected",false);
						$('#'+path4).attr("selected",false);
						$('#'+path5).attr("selected",false);
					}else{
						$('#'+path).attr("selected","selected");
						$('#'+path4).attr("selected",false);
						$('#'+path5).attr("selected",false);
						$('#'+path6).attr("selected",false);
                
					}
				}
			}

                $('#'+path2).attr("style","display:true");
            }
        });
        
};

function tombol_ipk(nopend){
       
        $('#edit_'+nopend).attr("style","display:true; width:100px;");
        $('#tombol_'+nopend).attr("style","display:none");
        $('#ipk_'+nopend).attr("style","display:none");
     
        
};

function tombol_akr(nopend){
       
        $('#edit_akr_'+nopend).attr("style","display:true; width:100px;");
        $('#tombol_akr_'+nopend).attr("style","display:none");
        $('#akr_'+nopend).attr("style","display:none");
     
        
};

// function submit_pleno(value, nopend){
       
//         path = 'pleno_'+nopend+' option[value="'+value+'"]';
//         path2 = 'pleno_'+nopend+'_save';
//         data2['value'] = value;
//         data2['nopend'] = nopend;
//         data2['kode'] = 'pleno';
//         $.ajax({
//         type: 'POST', 
//         url: '<?= yii\helpers\Url::to(["/pendaftar/verifikasi/updatedata"]);?>',
//         data: {'sm': data2},
//         success: function(){
//             if(value == 2){
//                 path3 = 'pleno_'+nopend+' option[value="1"]';
//                 $('#'+path).attr("selected","selected");
//                 $('#'+path3).attr("selected",false);
//               }else{
//                 path4 = 'pleno_'+nopend+' option[value="2"]';
//                 $('#'+path).attr("selected","selected");
//                 $('#'+path4).attr("selected",false);
//               }
//                 $('#'+path2).attr("style","display:true");
//                 //  alert( 'NoPendaftaran '+name+ ' berhasil ubah '+name
//                 // );
                
//             }
//         });
        
// };

function save_akrS2(value, nopend){
       
        data2['value'] = value;
        data2['nopend'] = nopend;
        data2['kode'] = 'save_akrs1';
        $.ajax({
        type: 'POST', 
        url: '<?= yii\helpers\Url::to(["/admin/verifikasi/updatedata"]);?>',
        data: {'sm': data2},
        success: function(){
            $('#edit_akr_'+nopend).attr("style","display:none");
            $('#tombol_akr_'+nopend).attr("style","display:true");
            $('#akr_'+nopend).attr("style","display:true");
            document.getElementById("akr_"+nopend).innerHTML = value+" ";
            }
        });
        
};

function save_akrS3(value, nopend){
       
        data2['value'] = value;
        data2['nopend'] = nopend;
        data2['kode'] = 'save_akrs2';
        $.ajax({
        type: 'POST', 
        url: '<?= yii\helpers\Url::to(["/admin/verifikasi/updatedata"]);?>',
        data: {'sm': data2},
        success: function(){
            $('#edit_akr_'+nopend).attr("style","display:none");
            $('#tombol_akr_'+nopend).attr("style","display:true");
            $('#akr_'+nopend).attr("style","display:true");
            document.getElementById("akr_"+nopend).innerHTML = value+" ";
            }
        });
        
};

function save_ipks1(value, nopend){
        var arr = nopend.split("_");
        data2['value'] = value;
        data2['nopend'] = nopend;
        data2['kode'] = 'save_ipks1';
        $.ajax({
        type: 'POST', 
        url: '<?= yii\helpers\Url::to(["/admin/verifikasi/updatedata"]);?>',
        data: {'sm': data2},
        success: function(data){
            $('#edit_'+arr[0]).attr("style","display:none");
            $('#tombol_'+arr[0]).attr("style","display:true");
            $('#ipk_'+arr[0]).attr("style","display:true");
            document.getElementById("ipk_"+arr[0]).innerHTML = value+" (IPK Asal : "+data+") ";
            }
        });
        
};

function save_ipks2(value, nopend){
        var arr = nopend.split("_");
        data2['value'] = value;
        data2['nopend'] = nopend;
        data2['kode'] = 'save_ipks2';
        $.ajax({
        type: 'POST', 
        url: '<?= yii\helpers\Url::to(["/admin/verifikasi/updatedata"]);?>',
        data: {'sm': data2},
        success: function(data){
            $('#edit_'+arr[0]).attr("style","display:none");
            $('#tombol_'+arr[0]).attr("style","display:true");
            $('#ipk_'+arr[0]).attr("style","display:true");
            document.getElementById("ipk_"+arr[0]).innerHTML = value+" (IPK Asal : "+data+") ";
            }
        });
        
};

function cetak_unggah(nopend, docid){
        var arr = nopend.split("_");
        data2['value'] = docid;
        data2['nopend'] = arr[0];
        data2['kode'] = 'cetak_unggah';
        // $('#batal_'+nopend).attr("style","display:true");
        
        $.ajax({
        type: 'POST', 
        url: '<?= yii\helpers\Url::to(["/admin/verifikasi/updatedata"]);?>',
        data: {'sm': data2},
        success: function(){
            $('#batal_'+nopend).attr("style","display:true");
            }
        });
        
};

function batal_cetak(nopend, docid){
        var arr = nopend.split("_");
        data2['value'] = docid;
        data2['nopend'] = arr[1];
        data2['kode'] = 'batal_cetak';
        // $('#batal_'+nopend).attr("style","display:true");
        
        $.ajax({
        type: 'POST', 
        url: '<?= yii\helpers\Url::to(["/admin/verifikasi/updatedata"]);?>',
        data: {'sm': data2},
        success: function(){
            $('#'+nopend).attr("style","display:none");
            }
        });
        
};

function cetak_forma(nopend){
    var url = '<?= yii\helpers\Url::to(["/admin/verifikasi/cetak"]);?>';
    var redirectWindow = window.open(url+'/'+nopend, '_blank');
    redirectWindow.location;
        
        
};


</script>
