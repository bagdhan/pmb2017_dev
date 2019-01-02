<?php
/* @var $this yii\web\View */
/* @var $data */

use app\adminlte\assets\plugin\DataTableAsset;
use yii\web\View;


DataTableAsset::register($this);
$this->title = isset($_GET['meja']) ? strtoupper($_GET['meja']) : '' . 'Verifikasi  ';
$this->params['breadcrumbs'][] = ['label' => $this->title];
$this->params['sidebar'] = 0;

$cssadition = <<< CSS
    tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
        color: black;
    }
    
    table .label 
    {
        margin: 5px;
        display:block;
        width: 70%;
        
    }
    
    td button {
        margin: 5px;
        box-sizing: border-box;
    }
    
CSS;
$this->registerCss($cssadition);

?>
    <div id="prosesverivikasi">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i><?= $this->title; ?></h3>
            </div>
            <div class="box-body">
                <h2><?= $data['title']?></h2>
                <div class="">
                    <table class="table datatable" id="tabel"
                           data-turl="<?= $data['tabelurl'] ?>"
                           data-aurl="<?= \yii\helpers\Url::to(['/verifikasi/proses/api']) ?>">
                        <thead>
                        <tr>
                            <th>No Antrian</th>
                            <th>No Pendaftaran</th>
                            <th>Nama</th>
                            <th>Strata</th>
                            <th>PS</th>
                            <th>tahap</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th>No Antrian</th>
                            <th>No Pendaftaran</th>
                            <th>Nama</th>
                            <th>Strata</th>
                            <th>PS</th>
                            <th>tahap</th>
                            <th>Aksi</th>
                        </tr>
                        </tfoot>
                    </table>


                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
        <div style="display: none;" id="isiklik" align="center">
            <div align="center"><i class="fa fa-spinner fa-pulse fa-4x fa-fw margin-bottom"></i></div>
        </div>
    </div>

<?php

$lst = '[2,3]';
if (isset($_GET['meja']) && (strtoupper($_GET['meja']) == 'M0' || strtoupper($_GET['meja']) == 'M1'))
    $lst = '[1,2,3,4]';

if (isset($_GET['meja']) && (strtoupper($_GET['meja']) == 'MK' ))
    $lst = '[1,2,3,4,5]';

$am5 = '';
if (isset($_GET['meja']) && (strtoupper($_GET['meja']) == 'M5' ))
    $am5 = 'm5';

$m3Cek = (isset($_GET['meja']) && (strtoupper($_GET['meja']) == 'M3' )) ? 3 : 0;
$loadingHtml = '<div align="center"><i class="fa fa-spinner fa-pulse fa-4x fa-fw margin-bottom"></i></div>';
$detailurl = \yii\helpers\Url::to('/verifikasi/proses/detail');
$setbeaurl = \yii\helpers\Url::to('/verifikasi/proses/set-beasiswa');

$csrf = Yii::$app->request->getCsrfToken();

$jsScript = <<< JS
var verifikasi = (function () {
    $.extend($.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fTl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        }
    });
    var labeltempstatus = {
        0: '<span class="label label-info" >belum distatuskan</span>',
        1: '<span class="label label-success" >Biasa</span>',
        2: '<span class="label label-warning" >Percobaan</span>',
        3: '<span class="label label-danger" >Ditolak</span>',
    };

    var button = {
        1: "<button class='btn btn-sm btn-success' data-action='status' data-tujuan='$data[nextm]' >Lanjut ke $data[nextm]</button>",
        2: "<button class='btn btn-sm btn-warning' data-action='status' data-tujuan='MK' >Meja Khusus</button>",
        3: "<button class='btn btn-sm btn-danger' data-action='status' data-tujuan='tunda' >Tunda</button>",
        4: "",
    };

    var htmlDOM = $('#prosesverivikasi');

    var tabelurl = htmlDOM.find('#tabel').attr('data-turl');
    var aurl = htmlDOM.find('#tabel').attr('data-aurl');

    var tableGen = {
        tabelAjax: function () {
            var domtable = htmlDOM.find('#tabel');
            domtable.find('tfoot th').each(function () {
                var title = $(this).text();
                if (title == 'Strata') {
                    $(this).html('<select name="status" class="form-control"> ' +
                        '<option value="">Pilih Semua</option> ' +
                        '<option value="S2"><span class="label label-success">S2 - Magister</span> </option> ' +
                        '<option value="S3"><span class="label label-warning">S3 - Doktor</span> </option></select>');
                } else if (title != '' && title != 'No' && title != 'Aksi') {
                    $(this).html('<input type="text" placeholder=" ' + title + '" />');
                } else {
                    $(this).html('');
                }
            });
            var table = domtable.DataTable(
                {
                    "ajax": tabelurl,
                    rowId: 'noPendaftaran',
                    "columns": [
                        {
                            "width": "2%", "className": 'details-control',
                            "defaultContent": '<i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom"></i>'
                        },
                        {"className": 'details-control', "data": "noPendaftaran"},
                        {"className": 'details-control', "data": "nama"},
                        {"className": 'details-control', "data": "strata"},
                        {"className": 'details-control', "data": "inisial"},
                        {"className": 'details-control', "data": "tahap"},
                        //{"className": 'details-control',"defaultContent": ""},
                        {"className": 'details-control', "defaultContent": ""}
                    ],
                    //    "processing": true,
                    "initComplete": function (settings, json) {
                        tableGen.serialnumber(table);

                        // tableGen.statuspleno(table,statustable);
                        // if (statustable == 2)
                        // intervalloaddata = setInterval(function(){
                        //     table.ajax.reload( function() {
                        //         tableGen.serialnumber(table);
                        //         // tableGen.statusseleksi(table,statustable);
                        //         tableGen.statuspleno(table,statustable);
                        //     });
                        //     console.log('reload');
                        // }, 60000);
                    }//,
                    //"dom"    : '<"top"lf>rt<"bottom"ip><"clear">'
                }
            );

            table.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
                $('select', this.footer()).on('keyup change', function () {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value, true, false)
                            .draw();
                    }
                });
            });
            if ($m3Cek === 3) {
                domtable.on('click', 'tbody .details-control', function () {
                    var tr = $(this).closest('tr');
                    tr.toggleClass('selected');

                    var row = table.row(tr);

                    var noPendaftaran = row.data().noPendaftaran;

                    if (row.child.isShown()) {
                        // This row is already open - close it
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {

                        if (row.child() === undefined) {
                            row.child('$loadingHtml');
                            $.post('$detailurl', {
                                'data': {
                                    "nop": noPendaftaran,

                                },
                                "_csrf": "$csrf"
                            }, function (dataresponse, status, xhr) {
                                if (status === "success")
                                    row.child(dataresponse);
                                if (status === "error")
                                    alert("Error: " + xhr.status + ": " + xhr.statusText);
                            });
                        }
                        row.child.show();

                        tr.addClass('shown');
                    }
                });
            }
            domtable.on('click', '[data-action=status]', function (e) {
                e.preventDefault();
                var elemen = $(e.target);
                if (elemen.is("i")) {
                    elemen = elemen.closest('button');
                }
                tableGen.setputusan(elemen, table);
            });

            domtable.on('change', '[data-action=statusbeasiswa]', function (e) {
                var elemen = $(e.target);
                var td = elemen.closest('td');
                var nopen = td.find('[name=nopen]').val();
                var putusan = elemen.val();
                var iloading = td.find('i');
                
              
                $.ajax({
                    type: 'POST',
                    url: '$setbeaurl',
                    dataType: 'json',
                    data: {'nop': nopen, 'idbea': putusan, 'ket': '', "_csrf": "$csrf"},
                    beforeSend: function () {
                        iloading.attr('class','fa fa-spin fa-spinner ');
                        iloading.attr('style','display: inline;');
                        
                        // loading(div[0]);
                        // //     div.html('loading.....');
                    },
                    success: function (data) {
                        iloading.attr('class','fa fa-check');
                        iloading.attr('style','display: block; color: green;');
                        //location.reload();
                        // $(div[0]).unblock();
                    },
                    error: function () {
                        iloading.attr('class','fa fa-close');
                        iloading.attr('style','display: block; color: red;');
                        //alert('in Lock');
                        // $(div[0]).unblock();
                    }
                });


            });

            var p = domtable.find(' thead th');
            //$(p.eq(0)[0]).attr('style', 'width:2%');
            $(p.eq(2)[0]).attr('style', 'width:25%');
            $(p.eq(6)[0]).attr('style', 'width:40%');


        },
        serialnumber: function (table) {
            var btn = '';
            $.each($lst, function (id, vl) {
                if ('$am5' == 'm5' && vl == 1) {
                    btn = btn + "<button class='btn btn-sm btn-success' data-action='status' data-tujuan='Selesai' >Selesai</button>";
                } else {
                    btn = btn + button[vl];
                }

            });

            if ($lst.length == 5) {
                btn = "<button class='btn btn-sm btn-success' data-action='status' data-tujuan='M1' >M1</button>" +
                    "<button class='btn btn-sm btn-success' data-action='status' data-tujuan='M2' >M2</button>" +
                    "<button class='btn btn-sm btn-success' data-action='status' data-tujuan='M3' >M3</button>" +
                    "<button class='btn btn-sm btn-success' data-action='status' data-tujuan='M4' >M4</button>" +
                    "<button class='btn btn-sm btn-danger' data-action='status' data-tujuan='tolak' >Tolak</button>";
            }

            table
                .column(0)
                .data()
                .each(function (value, index) {
                    var bt = btn;
                    var b = '';
                    var ct = 'disabled';

                    if (table.row(index).data().tahapVerifikasi_id === 1) {
                        ct = '';
                        bt =
                            "<button class='btn btn-sm btn-success' data-action='status' disabled data-tujuan='M2'>Lanjut ke M2</button>" +
                            "<button class='btn btn-sm btn-warning' data-action='status' data-tujuan='MK' >Meja Khusus</button>" +
                            "<button class='btn btn-sm btn-danger' data-action='status' data-tujuan='tolak' >Tolak</button>";
                    }


                    if ($lst.length == 4) {
                        b = "<button class='btn btn-sm btn-info' data-action='status' data-tujuan='M1' " + ct +
                            " >Siap Berkas</button> ";
                    }

                    if (table.row(index).data().selesai == 1)
                        bt = '<span class="label label-info" >Selesai</span>';

                    table.cell(index, 0).data(table.row(index).data().noantrian);
                    //table.cell(index,5).data( labeltempstatus[table.row(index).data().idHasilPleno] );
                    table.cell(index, 6).data(b + bt);

                });
        },
        setputusan: function (element, table) {
            var tr = element.closest('tr');
            var td = element.closest('td');
            var nop = tr.attr('id');
            var tujuan = element.attr('data-tujuan');
            console.log(nop);
            $.ajax({
                type: 'POST',
                url: aurl,
                dataType: 'json',
                data: {'nop': nop, 'setproses': 1, 'mtujuan': tujuan},
                beforeSend: function () {
                    // loading(div[0]);
                    // //     div.html('loading.....');
                },
                success: function (data) {
                    location.reload();
                    // $(div[0]).unblock();
                },
                error: function () {
                    //alert('in Lock');
                    // $(div[0]).unblock();
                }
            });
        }
    };

    function init() {
        tableGen.tabelAjax();
    };

    return {
        init: init(),


    }
})();
verifikasi.init;
JS;

$this->registerJs($jsScript, View::POS_READY, 'runtable');
?>