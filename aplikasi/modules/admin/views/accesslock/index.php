<?php
/* @var $this yii\web\View */
use yii\web\View;
use yii\helpers\Url;

$this->title = 'Manajemen Akses Penstatusan ';
$this->params['breadcrumbs'][] = ['label' => 'Admin', 'url' => ['/admin/index/']];
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="box box-default color-palette-box">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-tag"></i> Manajemen Lock Seleksi</h3>
    </div>
    <div class="box-body">
        <!-- <div id="temppaket">
            

        </div> -->
        <div id="temppengguna" data-url="<?= Url::to(['/admin/accesslock/changestate'])?>">
            <table class="table table-hover">
                <tr>
                    <th>Fakultas</th>
                    <th>Departemen</th>
                    <th>Program Studi</th>
                </tr>
                <!--<tr>
                    <td rowspan="5">asdasd</td>
                    <td rowspan="3">22</td>
                    <td>33</td>
                </tr>
                <tr><td>q</td></tr>
                <tr><td>q</td></tr>
                <tr><td rowspan="2">asda</td><td>q</td></tr>
                <tr><td>q</td></tr>-->
                <?php
                //print_r($data);die;
                foreach ($data as $i=>$p){
                    $col1 = 1;
                    if (!($i == 'id' || $i == 'aktif' || $i == 'name' || $i == 'jumdep' || $i == 'jumprodi')) {

                        foreach ($p as $i1 => $p2) {
                            $col2 = 1;
                            if (!($i1 == 'id' || $i1 == 'aktif' || $i1 == 'name' || $i1 == 'jumdep' || $i1 == 'jumprodi')) {
                                if (isset($p2['name'])){
                                    $depname =  $p2['name'] ;
                                    $depid =  $p2['id'] ;
                                    $aktf = @$p2['aktif'] == 1 ? 'checked' : '';
                                }else{
                                    $depname =  '' ;
                                    $depid =  '';
                                    $aktf = '';
                                }

                                foreach ($p2 as $i2 => $p3) {
                                    if (!($i2 == 'id' || $i2 == 'aktif' || $i2 == 'name' || $i2 == 'jumdep' || $i2 == 'jumprodi')) {
                                        if ($col1 == 1 && $col2 == 1){
                                            echo "<tr>";
                                            $cek1 = @$p['aktif'] == 1 ? 'checked' : '';
                                            echo "<td rowspan='$p[jumprodi]'>".@$p['name'] .
                                                ' <div class="" >
                                                <input data-id="'.@$p['id'].'" data-size="mini" type="checkbox" '.$cek1
                                                .' data-toggle="toggle">
                                                </div>'."</td>";
                                            $cek2 = @$p2['aktif'] == 1 ? 'checked' : '';
                                            echo "<td rowspan='$p2[jumprodi]'>".@$p2['name'] .
                                                ' <div class="" >
                                                <input data-id="'.@$p2['id'].'" data-size="mini" type="checkbox" '.$cek2
                                                .' data-toggle="toggle">
                                                </div>'."</td>";
                                            $cek3 = @$p3['aktif'] == 1 ? 'checked' : '';
                                            echo "<td>".@$p3['name'] .
                                                ' <div class="" >
                                                <input data-id="'.@$p3['id'].'" data-size="mini" type="checkbox" '.$cek3
                                                .' data-toggle="toggle">
                                                </div>'."</td>";
                                            echo "</tr>";
                                        }elseif ( $col2 == 1){
                                            echo "<tr>";
                                            echo "<td rowspan='$p2[jumprodi]'>".$depname .
                                                ' <div class="" >
                                                <input data-id="'.$depid.'" data-size="mini" type="checkbox" '.$aktf
                                                .' data-toggle="toggle">
                                                </div>'."</td>";
                                            $cek4 = @$p3['aktif'] == 1 ? 'checked' : '';
                                            echo "<td>".@$p3['name'] .
                                                ' <div class="" >
                                                <input data-id="'.@$p3['id'].'" data-size="mini" type="checkbox" '.$cek4
                                                .' data-toggle="toggle">
                                                </div>'."</td>";
                                            echo "</tr>";
                                        }else{
                                            echo "<tr>";
                                            $cek5 = @$p3['aktif'] == 1 ? 'checked' : '';
                                            echo "<td>".@$p3['name'] .
                                                ' <div class="" >
                                                <input data-id="'.@$p3['id'].'" data-size="mini" type="checkbox" '.$cek5
                                                .' data-toggle="toggle">
                                                </div>'."</td>";
                                            echo "</tr>";
                                        }
                                        $col1++;
                                        $col2++;
                                    }
                                }
                            }
                        }
                    }


//                    echo "<tr>
//                        <td rowspan='4'>$p[name]</td>
//                        <td></td>
//                        <td></td>
//                    </tr>";

                }
                ?>
            </table>
        </div>
    </div><!-- /.box-body -->
</div><!-- /.box -->
<!-- START ALERTS AND CALLOUTS -->



<?php
    $this->registerJs("
    var pengguna = (function () {
        var modulDUM = $('#temppengguna');
        var ur = modulDUM.attr('data-url');
        
        function senddata(set,id) {
            $.ajax({
                type: 'POST',
                url: ur,
                dataType: 'json',
                beforeSend: function(){
                    //loading('.tableid');
                },
                data: {'state':{'set':set,'id':id}},
                error: function(){
                    console.log('error');
                },
                success: function(data){
                    //console.log(data);
                    //console.log('sssd');
                }
            });
        };
        
        function init() {
            modulDUM.on('change','input',function (e){
                elemen = $(this);
                set = elemen.prop('checked');
                id = elemen.attr('data-id');
               // console.log(set + id);
                senddata(set,id);     
               // console.log($(this).prop('checked'));
            });
        };

        return {
            init : init()
        }
    })();
    pengguna.init;
    ",View::POS_READY,'initpengguna');
$this->registerJs("
    var paket = (function () {
        var modulDUM = $('#temppaket');
        var ur = modulDUM.attr('data-url');
        
        function senddata(set,id) {
            $.ajax({
                type: 'POST',
                url: ur,
                dataType: 'json',
                beforeSend: function(){
                    //loading('.tableid');
                },
                data: {'state':{'set':set,'id':id}},
                error: function(){
                    console.log('error');
                },
                success: function(data){
                    //console.log(data);
                    //console.log('sssd');
                }
            });
        };
        
        function init() {
            modulDUM.on('change','input',function (e){
                elemen = $(this);
                set = elemen.prop('checked');
                id = elemen.attr('data-id');
               // console.log(set + id);
                senddata(set,id);     
               // console.log($(this).prop('checked'));
            });
        };

        return {
            init : init()
        }
    })();
    paket.init;
    ",View::POS_READY,'initpaket');
    $this->registerCssFile("https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css",
                ['depends'=>['yii\web\YiiAsset']]);
    $this->registerJsFile("https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js",
                ['depends'=>['yii\web\YiiAsset']]);
?>