<?php
/**
 * Copyright (c) 2017.
 */

/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 4/22/2017
 * Time: 8:35 PM
 */

namespace app\modules\pleno\models;

use Yii;
use app\modelsDB\UserHasFakultas;
use app\modelsDB\UserHasDepartemen;
use app\modelsDB\UserHasProgramStudi;
use app\adminlte\assets\plugin\DataTableAsset;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

/**
 * @property string jsonData
 * @property string btn
 * @property string hasilSeleksi
 * @property array columns
 */
class Table extends Widget
{

    /** @var  Sidang */
    public $sidang;

    public $jenisSidang;

    public $urutanPs;

    public $classTable = 'table datatable';

    public $w_id;

    public $listNopen;

    /** @var  HasilKeputusan[] */
    public $listHkep;

    public $listHasilSidang;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        if ($this->w_id === null)
            $this->w_id = str_replace('.', '', uniqid('t', true));

        if ($this->urutanPs === null)
            $this->urutanPs = 1;

        if ($this->sidang === null || $this->listNopen === null) {
            throw new InvalidConfigException('Please specify the "model" property.');
        }

        $this->jenisSidang = $this->sidang->jenisSidang_id;
        $this->listHkep = HasilKeputusan::find()->where(['jenisSidang_id' => $this->jenisSidang, 'aktif' => 1])->all();

        DataTableAsset::register($this->view);
        $this->runJs();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $head = $this->urutanPs == 1 ? $this->headerTable() : '';
        $add = $this->jenisSidang == 2 ? "<th></th>" : "";
        $tunda = $this->urutanPs == 3 ? "" : "<th></th>";
        return $head .
            Html::tag('table', "<tfoot><tr>$add<th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>$tunda</tr></tfoot>",
                ['class' => "table table-responsive", 'width' => "100%", 'id' => $this->w_id]);
    }

    protected function getListNopenChace()
    {

    }

    public function headerTable()
    {
        $out = '';
        foreach ($this->listHkep as $item) {
            $out .= "<button class='btn btn-sm btn-$item->tempClass'>$item->temp</button>
                <i class='fa fa-angle-right' aria-hidden='true'></i> $item->keputusan;";
        }


        return "<hr>Keterangan Simbol : $out<hr/>";
    }

    /**
     * @param Pendaftar $pendaftar
     * @return mixed|string
     *
     * @var ProsesSidang $proSd
     */
    public function getActionButton(Pendaftar $pendaftar, $idProses = null)
    {
        $out = '';
        $date = date('Y-m-d H:i:s');
        if ($this->urutanPs == 3) {
            $proSd = ProsesSidang::findOne($idProses);

            $class = $proSd->hasilKeputusan->tempClass;
            $putusan = $proSd->hasilKeputusan->keputusan;
            return "<span data-id='$proSd->hasilKeputusan_id' class='label label-$class'  
                data-ps='3' >$putusan</span>";
        }

        $proSd = ProsesSidang::findOne($idProses);

        if (!empty($proSd) && $proSd->hasilKeputusan_id > 0) {
            return $proSd->actionBtn;
        }

        $accessRule = Yii::$app->user->identity->accessRole_id;
        $disabled = '';
        if($accessRule > 3 && $accessRule < 7){
            $disabled = ($this->getLock(Yii::$app->user->identity->id,$accessRule) == 0) ? '' : 'disabled';
            $disabled = ($this->jenisSidang != 3 || $this->sidang->tanggalSidang >= $date)? 'disabled' : $disabled;
        }
        

        foreach ($this->listHkep as $item) {
            if($this->sidang->paketPendaftaran->title = 'Tahap 2' && $item->keputusan == 'Pending'){ // non aktif tombol pending di pendaftaran tahap 2
                $out .= "<span style='display: none;'>zzz</span>
                    <button data-id='$item->id' class='btn btn-sm btn-$item->tempClass' data-action='status' 
                    data-ps='$this->urutanPs' title='$item->keputusan' disabled>$item->temp</button>";
            }else{ 
                $out .= "<span style='display: none;'>zzz</span>
                    <button data-id='$item->id' class='btn btn-sm btn-$item->tempClass' data-action='status' 
                    data-ps='$this->urutanPs' title='$item->keputusan' $disabled>$item->temp</button>";
            }
        }
        return $out;
    }

    private function getLock($user_id,$accessRole_id)
    {
        
        switch ($accessRole_id){
                case 4 :
                    $dtakses = UserHasFakultas::findOne($user_id);
                    $data = $dtakses->lock_seleksi;
                    break;
                case 5 :
                    $dtakses = UserHasDepartemen::findOne($user_id);
                    $data = $dtakses->lock_seleksi;
                    break;
                case 6 :
                    $dtakses = UserHasProgramStudi::findOne($user_id);
                    $data = $dtakses->lock_seleksi;
                    break;
            }
            if($dtakses)
                   return $data;
                else
                   return 0;

            
    }


    protected function getJsonData()
    {
        $out = [];
        $i = 1;
        foreach ($this->listNopen as $key => $noPend) {
            $pendaftar = Pendaftar::findOne($noPend);
            $strata = 'S' . $pendaftar->strata;
            $ipksebelumnya = $pendaftar->lastIpk;
            if ($pendaftar != null) {
                $prodi1 = $pendaftar->kodeProdi1;
                $span = '';
                $ipk = false;
                if ((isset($prodi1) && $strata == 'S2' && $ipksebelumnya >= '2.5') || (isset($prodi1) && $strata == 'S3' && $ipksebelumnya >= '3.25')) {
                    $ipk = true;
                    $value = '<a style="visibility:hidden;">A</a>';
                    $span = $value . '<a style="color:green;"> ' . $ipksebelumnya . '</a>';
                } elseif ((isset($prodi1) && $strata == 'S2' && $ipksebelumnya < '2.5') || (isset($prodi1) && $strata == 'S3' && $ipksebelumnya < '3.25')) {
                    $ipk = false;
                    $value = '<a style="visibility:hidden;">C</a>';
                    $span = $value . '<a style="color:red;">' . $ipksebelumnya . '</a>';
                }

                $tpa_score = $pendaftar->lastTpa;
                $tpa = $tpa_score;
                if (($ipk == false && $strata == 'S2' && $tpa_score >= '450') || ($ipk == false && $strata == 'S3' && $tpa_score >= '475')) {
                    $tpa = '<a style="color:green;">' . $tpa_score . '</a>';
                } elseif (($ipk == false && $strata == 'S2' && $tpa_score < '450') || ($ipk == false && $strata == 'S3' && $tpa_score < '475')) {
                    $tpa = '<a style="color:red;">' . $tpa_score . '</a>';
                }

                $seleksi = $this->loadHasilSeleksi($key);

                $out[] = [
                    "idProses" => $key,
                    "noPendaftaran" => $noPend,
                    "nomor" => $i,
                    "nama" => $pendaftar->fullName,
                    "strata" => "S" . $pendaftar->strata,
                    "prodi1" => $pendaftar->kodeProdi1,
                    "prodi2" => $pendaftar->kodeProdi2,
                    'ps' => $seleksi['ps'],
                    "ipk" => $span,
                    "akreditasi" => $pendaftar->lastAkr,
                    "tpa" => $tpa,
					"usia" => $pendaftar->usia,
                    "hseleksi" => $seleksi['hasil'],
                    "action" => $this->getActionButton($pendaftar, $key),
                ];
                $i++;
            }
        }

        return Json::encode($out);
    }

    public function loadHasilSeleksi($id)
    {
        $prosesSeleksi = ProsesSidang::find()->joinWith(['pendaftaranHasProgramStudi' => function($query){
                $query->joinWith(['programStudi']);
        }])->where([ProsesSidang::tableName().'.id' => $id])->one();

        if (empty($prosesSeleksi))
            return [
                'ps' => '',
                'hasil' => '',
            ];

        return [
            'ps' => $prosesSeleksi->pendaftaranHasProgramStudi->programStudi->inisial,
            'hasil' => $prosesSeleksi->hasilSeleksi,
        ];

//        if ($this->listHasilSidang === null) {
//            /** @var ProsesSidang[] $prosesSidangs */
//            $prosesSidangs = ProsesSidang::find()
//                ->innerJoinWith('sidang')
//                ->where([
//                    'jenisSidang_id' => 3,
//                    'paketPendaftaran_id' => $this->sidang->paketPendaftaran_id,
//                ])
//                ->all();
//            foreach ($prosesSidangs as $prosesSidang) {
//                $this->listHasilSidang[$prosesSidang->id] = $prosesSidang->hasilSeleksi;
//            }
//        }
//        print_r($this->listHasilSidang);die();
//        return isset($this->listHasilSidang[$id]) ? $this->listHasilSidang[$id] : '';
    }

    /**
     * @return array
     */
    public function getBtn()
    {
        return [
            1 => Html::button('{label}', ['class' => 'btn btn-sm btn-success']),
            2 => Html::button('{label}', ['class' => 'btn btn-sm btn-warning']),
            3 => Html::button('{label}', ['class' => 'btn btn-sm btn-danger']),
            4 => Html::button('{label}', ['class' => 'btn btn-sm btn-info']),
        ];
    }

    public $lable = [
        1 => "<i class='fa fa-forward'></i>",
        2 => "<i class='fa fa-pause'></i>",
        3 => "<i class='fa fa-stop'></i>",
        4 => "B",
        5 => "P",
        6 => "T",
        7 => "<i class='fa fa-question'></i>",
        8 => "",
    ];

    /**
     * @return array
     */
    public function getLabelTemplate()
    {
        return [
            1 => Html::tag('span', '{label}', ['class' => 'label label-success']),
            2 => Html::tag('span', '{label}', ['class' => 'label label-warning']),
            3 => Html::tag('span', '{label}', ['class' => 'label label-danger']),
            4 => Html::tag('span', '{label}', ['class' => 'label label-info']),
        ];
    }

    protected function getLabelFilter()
    {
        $out = '';
        foreach ($this->listHkep as $item) {
            $out .= '<option value="' . $item->keputusan . '">' . $item->keputusan . ' </option>';
        }
        return $out;
    }

    public function getColumns()
    {
        $classDetail = $this->urutanPs != 3 ? 'details-control' : '';
        $defalt = [
            [
                "width" => "2%",
                "className" => $classDetail,
                "data" => "nomor",
                "title" => "No",
            ],
            [
                "width" => "25%",
                "className" => $classDetail,
                "data" => "nama",
                "title" => "Nama",
            ],
            [
                "className" => $classDetail,
                "data" => "strata",
                "title" => "Strata",
            ],
            [

                "className" => $classDetail,
                "data" => "prodi1",
                "title" => "PS1",
            ],
            [
                "className" => $classDetail,
                "data" => "prodi2",
                "title" => "PS2",
            ],
            [
                "className" => $classDetail,
                "data" => "ipk",
                "title" => "IPK",
            ],
            [
                "className" => $classDetail,
                "data" => "akreditasi",
                "title" => "Akr",
            ],
            [
                "className" => $classDetail,
                "data" => "tpa",
                "title" => "TPA",
            ],
			[
				"width" => "10%",
				"className" => $classDetail,
                "data" => "usia",
                "title" => "Usia (Thn)",
            ],

        ];
        $tunda = [
            [
                "width" => "2%",
                "className" => $classDetail,
                "data" => "nomor",
                "title" => "No",
            ],
            [
                "width" => "25%",
                "className" => $classDetail,
                "data" => "nama",
                "title" => "Nama",
            ],
            [
                "className" => $classDetail,
                "data" => "strata",
                "title" => "Strata",
            ],
            [

                "className" => $classDetail,
                "data" => "ps",
                "title" => "PS",
            ],
            [
                "className" => $classDetail,
                "data" => "ipk",
                "title" => "IPK",
            ],
            [
                "className" => $classDetail,
                "data" => "akreditasi",
                "title" => "Akr",
            ],
            [
                "className" => $classDetail,
                "data" => "tpa",
                "title" => "TPA",
            ],
			[
				"width" => "10%",
				"className" => $classDetail,
                "data" => "usia",
                "title" => "Usia (Thn)",
            ],

        ];

        return $this->urutanPs != 3 ? $defalt : $tunda;
    }

    protected function runJs()
    {
        $csrf = Yii::$app->request->getCsrfToken();
        $detailUrl = Url::to(['/pleno/api/detail-pelamar']);
        $putusanUrl = Url::to(['/pleno/api/putusan']);
        $loadingHtml = '<div align="center"><i class="fa fa-spinner fa-pulse fa-4x fa-fw margin-bottom"></i></div>';
        $filter = $this->getlabelFilter();

        $columns = $this->columns;

        if ($this->jenisSidang == 2) {
            $columns[] = [
                "className" => 'details-control',
                "data" => "hseleksi",
                "title" => "Hasil Seleksi",
            ];
        }

        $columns[] = [
            "width" => "28%",
            "data" => "action",
            "title" => "Action",
        ];

        $optionColumn = Json::encode($columns);

        $jsScript = <<< JS
window.$this->w_id = (function () {
    var dataSet = $this->jsonData;
    var domElement = $('#$this->w_id');
    var detailurl = "$detailUrl";
    var urlputusan = "$putusanUrl";

    var table = domElement.DataTable({
        data: dataSet,
        rowId: 'noPendaftaran',
        columns: $optionColumn,
        initComplete: function () {

        }

    });

    // domElement.append(
    //     $('<tfoot/>').append(domElement.find("thead tr").clone())
    // );
    domElement.find('thead th').each(function () {
        var title = $(this).text();
        var idx = $(this).index();
        var foo = domElement.find('tfoot th');

        if (title === 'Action') {
            $(foo.eq(idx)).html('<select name="status" class="form-control"> ' +
                '<option value="">Pilih Semua</option> ' +
                '$filter' +
                '<option value="zzz">Belum diputuskan </option></select>');
        } else if (title === 'Strata') {
            $(foo.eq(idx)).html('<select name="status" class="form-control"> ' +
                '<option value="">Pilih Semua</option> ' +
                '<option value="S2"><span class="label label-success">S2 - Magister</span> </option> ' +
                '<option value="S3"><span class="label label-warning">S3 - Doktor</span> </option></select>');
        } else if (title === 'IPK') {
            $(foo.eq(idx)).html('<select name="status" class="form-control"> ' +
                '<option value="">Pilih Semua</option> ' +
                '<option value="A"><span class="label label-success">IPK - Memenuhi</span> </option> ' +
                '<option value="C"><span class="label label-warning">IPK - Kurang</span> </option></select>');
        } else if (title !== '' && title !== 'No') {
            $(foo.eq(idx)).html('<input type="text" placeholder=" ' + title + '" />');
        } else {
            $(foo.eq(idx)).html('');
        }
    });

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

    domElement.on('click', 'tbody .details-control', function () {
        var tr = $(this).closest('tr');
        tr.toggleClass('selected');

        var row = table.row(tr);

        var noPendaftaran = row.data().noPendaftaran;
        var idProses = row.data().idProses;

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {

            if (row.child() === undefined || row.child().find('textarea').length < 1) {
                row.child('$loadingHtml');
                $.post(detailurl, {
                    'data': {
                        "nop": noPendaftaran,
                        "idProses": idProses
                    }
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

    domElement.on('click', 'tbody [data-action=status]', function () {

        var tr = $(this).closest('tr');
        var elemen = $(this);
        var td = elemen.closest('td');
        var row = table.row(tr);

        var noPendaftaran = row.data().noPendaftaran;
        var urutanPs = elemen.attr('data-ps');
        var idProses = row.data().idProses;
        var idPutusan = elemen.attr('data-id');

        var findtext = '';

        if (row.child() !== undefined) {
            findtext = row.child().find('textarea').val();
        }

        findtext = typeof findtext === 'string' ? findtext : '';

        var defaultvalua = '';

        var runajax = function () {
            $.ajax({
                type: 'POST',
                url: urlputusan,
                dataType: 'json',
                data: {
                    'nop': noPendaftaran,
                    'idProses': idProses,
                    'idPutusan': idPutusan,
                    'text': findtext,
                    'ps': urutanPs,
                    "_csrf": "$csrf"
                },
                beforeSend: function () {
                    blockLoading(td);
                },
                success: function (data) {
                    table.cell(td).data(data.data);
                    if (row.child() !== undefined) {
                        row.child().find('textarea').attr('disabled', 'disabled');
                    }
                    td.unblock();
                },
                error: function () {

                    td.unblock();
                }
            });
        };

        if (!(findtext !== '' && findtext !== undefined) && ($.inArray(parseInt(idPutusan), [2, 3, 5, 6, 7, 8, 10, 11]) !== -1)) {
            var dialog = bootbox.prompt({
                title: "Alasan Keputusan",
                value: defaultvalua,
                inputType: 'textarea',
                callback: function (result) {
                    if (result === null) {

                    } else {
                        if (result === '') {
                            var box = $('.bootbox-form');

                            if (box.find('p').length === 0) {
                                box.append('<p style="color:red;">tidak boleh kosong...</p>');
                            }

                            return false;
                        } else {
                            findtext = result;
                            runajax();
                        }
                    }
                }
            });
        } else {
            runajax();
        }


    });

    domElement.on('click', 'tbody [data-action=labelstatus]', function () {
        console.log('asd');
        var tr = $(this).closest('tr');
        var elemen = $(this);
        var td = elemen.closest('td');
        var row = table.row(tr);

        var noPendaftaran = row.data().noPendaftaran;
        var urutanPs = elemen.attr('data-ps');
        var idProses = row.data().idProses;
        var idPutusan = elemen.attr('data-id');


        $.ajax({
            type: 'POST',
            url: urlputusan,
            dataType: 'json',
            data: {
                'nop2': noPendaftaran,
                'idProses': idProses,
                'idPutusan': idPutusan,
                'text': '',
                'ps': urutanPs,
                "_csrf": "$csrf"
            },
            beforeSend: function () {
                blockLoading(td);
            },
            success: function (data) {
                table.cell(td).data(data.data);
                if (row.child() !== undefined) {
                    row.child().find('textarea').removeAttr('disabled');
                }
                td.unblock();
            },
            error: function () {

                td.unblock();
            }
        });


    });

    return {
        "test": function () {
            alert('daa');
        },
        table: table
    };
})();        

JS;


        $this->view->registerJs($jsScript, View::POS_READY, 'runfor_' . $this->w_id);
    }


}
