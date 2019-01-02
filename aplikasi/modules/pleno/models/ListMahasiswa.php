<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 4/26/2017
 * Time: 10:04 AM
 */

namespace app\modules\pleno\models;

use Yii;
use app\modelsDB\UserHasFakultas;
use app\modelsDB\UserHasDepartemen;
use app\modelsDB\UserHasProgramStudi;
use app\models\Fakultas;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\web\View;

class ListMahasiswa extends Widget
{
    /** @var  Sidang */
    public $sidang;

    /** @var  array */
    public $listNopenByFakultas;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        if ($this->sidang === null) {
            throw new InvalidConfigException('Please specify the "model" property.');
        }

        $this->listNopenByFakultas = $this->sidang->listNopenByFakultas;

        $this->runJs();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        return $this->renderData();
    }

    public function renderData()
    {
        /** @var Fakultas[] $listFakultas */
        $listFakultas = Fakultas::find()->all();
        $itab = [];

        foreach ($listFakultas as $fakultas) {
            if (isset($this->listNopenByFakultas[$fakultas->id]))
                $itab[] = [
                    'label' => $fakultas->inisial,
                    'content' => $this->renderTableItem($fakultas->id),
                    'active' => false,
                ];
        }

        return Tabs::widget([
            'items' => $itab,
        ]);
    }

    protected function renderTableItem($idFakultas)
    {
        $listNopenPil1 = isset($this->listNopenByFakultas[$idFakultas][1]) ?
            $this->listNopenByFakultas[$idFakultas][1] : [];

        $table1 = Table::widget([
            'sidang' => $this->sidang,
            'listNopen' => $listNopenPil1,
        ]);

        $table2 = '';

        if ($this->sidang->jenisSidang_id != 1) {
            $listNopenPil2 = isset($this->listNopenByFakultas[$idFakultas][2]) ? $this->listNopenByFakultas[$idFakultas][2] : [];
            $table2 = "<hr><h2>Pendaftar Pilihan 2</h2>" . Table::widget([
                    'sidang' => $this->sidang,
                    'listNopen' => $listNopenPil2,
                    'urutanPs' => 2,
                ]);
        }

        $tabletunda = '';
        if (isset($this->listNopenByFakultas[$idFakultas][3])) {
            $listNopenPil3 = isset($this->listNopenByFakultas[$idFakultas][3]) ? $this->listNopenByFakultas[$idFakultas][3] : [];
            $tabletunda = "<hr><h2>Daftar Mahasiswa yang menunda tahun lalu</h2>" . Table::widget([
                    'sidang' => $this->sidang,
                    'listNopen' => $listNopenPil3,
                    'urutanPs' => 3,
                ]);
        }

        $kode_fak = Fakultas::findOne($idFakultas)->kode;
        $tahap = $this->sidang->paketPendaftaran->uniqueUrl;
        $selesai = '';
        if(Yii::$app->user->identity->accessRole_id > 3 && Yii::$app->user->identity->accessRole_id < 7 && $this->sidang->jenisSidang_id ==3){
            $lock = $this->getLock(Yii::$app->user->identity->id,Yii::$app->user->identity->accessRole_id);
            $disabled = ($lock == 1)? 'disabled' : false;
            $selesai = Html::tag('button', 'Selesai menstatuskan', ['class' => 'btn btn-primary', 'disabled'=>$disabled,
                'data-fak' => '',
                'onClick' => "bootbox.confirm(\"<h3>Apakan proses seleksi pendaftar tahapan pleno 2 telah selesai?</h3>\",  
                            function(result) {
                                if(result)
                                  $.ajax({
                                    type: 'POST', 
                                    url: '" . \yii\helpers\Url::to(["/admin/accesslock/setlock"]) . "',
                                    dataType: 'json',
                                    beforeSend: function(){
                                                                                            //loading('.tableid');    
                                    },
                                    data: {'state':result},
                                    error: function(){
                                        console.log('daa');
                                    },
                                    success: function(data){
                                        location.reload();
                                        console.log('sssd');
                                    }
                                });
                            }); "
            ]);
        }

        switch ($this->sidang->jenisSidang_id){
                case 1 : //pleno 1
                    $link = 'createbahanpleno';
                    break;
                case 2 : // pleno 2
                    $link = 'createbahanpleno2';
                    break;
                case 3 : // seleksi fakultas
                    $link = 'createbahanseleksi';
                    break;
            }

        $button = $this->sidang->jenisSidang->title;

        $table = '<div class="row"><br/>
                    <div class="col-lg-2 col-lg-offset-4">' .
            Html::a('Bahan '.$button.' Excel', ['#'], ['class' => 'btn btn-primary',
                'data-fak' => $idFakultas,
                // 'disabled' => '',
                'data-action' => 'excel',
                'data-url' => \yii\helpers\Url::to([
                    "/pleno/sidang/".$link, 'fak' => $kode_fak, 'tahap' => $tahap]),
            ]) . '
                    </div>
                    <div class="col-lg-3">' .
            $selesai . '
                    </div>
                </div>';


        return Html::tag('div', "$table $table1 $table2 $tabletunda", ['class' => "box box-info"]);
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
                   return false;

            
    }

    protected function runJs()
    {

        $jsScript = <<< JS
var listMahasiswaModule = (function () {
    var plenoBody = $('body');
    plenoBody.find('[data-action=excel]').click(function (e) {
        e.preventDefault();
        var elemnet = $(e.target);
        var fak = elemnet.attr('data-fak');
        var exurl = elemnet.attr('data-url');
        //alert('aa ' + elemnet.attr('data-url'));
        var title = elemnet.text();
        $.ajax({
            type: 'POST',
            url: exurl,
            dataType: "json",
            beforeSend: function () {
                blockLoading(elemnet);
            },
            error: function () {
                alert('silahkan ulangi ...');
                $(elemnet).unblock();
            },
            success: function (data) {
                $(elemnet).replaceWith('<a class="btn btn-default btn-sm" href="' +
                    data.link +
                    '" role="button">' + title + ' <br>download</a>');
                //elemnet.attr("disabled","disabled");
            }
        });
    });
    return {
        "test": function () {
            alert('daa');
        }
    };
});

var listHasilModule = (function () {
    var plenoBody = $('body');
    plenoBody.find('[data-action=excelba]').click(function (e) {
        e.preventDefault();
        var elemnet = $(e.target);
        var tahap2 = elemnet.attr('data-tahap'); 
        var fak = elemnet.attr('data-fak');
        var exurl = elemnet.attr('data-url');
        
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
        title = elemnet.text();
        $.ajax({
        type: 'POST', 
        url: exurl+filterurl+filterurl2+filterurl3,
        dataType: "json",
        beforeSend: function(){
                blockLoading(elemnet.parent());    
            },
        error: function(){
                alert('silahkan ulangi ...'); 
                $(elemnet.parent()).unblock();   
            },
        success: function(data){
                $("#hasilBAexl").html('<a class="btn btn-default btn-sm" href="'+
                data.link +
                '" role="button"> download Fak '+dta+'</a>');
                $(elemnet.parent()).unblock();
            }
        });
    });
    return {
        "test": function () {
            alert('daa');
        }
    };
});

listMahasiswaModule();
listHasilModule();
JS;


        $this->view->registerJs($jsScript, View::POS_READY, 'runfor_listmahasiswa');
    }
}