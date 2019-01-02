<?php

namespace app\usermanager\models;

use app\models\Orang;
use app\modules\pendaftaran\models\FormLengkapData;
use app\models\Pendaftaran;
use app\modelsDB\PaketPendaftaran;
use app\modelsDB\Sidang;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Json;
use app\components\Lang;


/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $kode
 * @property string $nama
 * @property string $url
 * @property string $parent_id
 */
class Menu extends Model
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode', 'nama', 'parent_id'], 'string', 'max' => 45],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode',
            'nama' => 'Nama',
            'url' => 'Url',
            'parent_id' => 'Parent ID',
        ];
    }

    // /**
    //  * @return array
    //  */
    // public function getUrl($url,$tahun)
    // {
    //     $tahun = ['tahun'=>$tahun];

    //     if($tahun != ''){
    //         array_push($url, $tahun);
    //     }
    //     return $url;
    // }

    /**
     * @return array
     * @throws \yii\web\NotAcceptableHttpException
     */
    public static function getMenu()
    {
        if(Yii::$app->user->identity->accessRole_id == 7){
            $orang_id = Yii::$app->user->identity->orang_id;
            $pendaftaran = Pendaftaran::find()->where(['orang_id'=>$orang_id])->one();
            $menunda = $pendaftaran->cek_menunda();

            $surat = $pendaftaran->paketPendaftaran->surats;
            if($menunda == 1){
                $paket = PaketPendaftaran::find()->where(['tahun'=>date('Y'),'title'=>'Tahap 1','active'=>1])->one();
                $surat = isset($paket)? $paket->surats : '';
            }

            $tanggalPengumuman=[]; $tanggalPengumuman[0]=$tanggalPengumuman[1]='';
            if($surat != ''){
                foreach($surat as $sur){
                    $statusPenerimaan                       = $sur->statusPenerimaan;
                    $tanggalPengumuman[$statusPenerimaan]   = $sur->tanggalPengumumanBuka;
                }
            }

            $tanggalPengumuman[1]= '2018-05-25 00:00:00';
        }

        $sidang=[];
        $tahap  =  (!empty($_GET['tahap']) && $_GET['tahap'] != 1 && $_GET['tahap'] != 2) ? $_GET['tahap'] : 'tahap1';
        $_POST['tahap'] = $tahap;
        $data_sidang = Sidang::find()->joinWith(['paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>!empty($_GET['tahun'])? $_POST['tahap'].'_'.$_GET['tahun'] : $_POST['tahap'].'_'.date('Y')]);}])->all();
        foreach($data_sidang as $value){
            $sidang[$value->jenisSidang_id] = $value->id;
        }

        $tahun = !empty($_GET['tahun'])? $_GET['tahun'] : null;

        $sidangseleksi = [];
        if (!empty($sidang)) {
            $sidangseleksi = [
                ['label' => 'Sidang Pleno 1', 'icon' => 'fa fa-chevron-right', 'url' => empty($tahun)? ['/pleno/sidang/view','id'=>$sidang[1],'tahap'=>$tahap] : ['/pleno/sidang/view','id'=>$sidang[1],'tahap'=>$tahap,'tahun'=>$tahun] ,'active'=> \Yii::$app->controller->id == 'sidang' && in_array(\Yii::$app->controller->action->id, ['view','hasil']),],
                ['label' => 'Seleksi', 'icon' => 'fa fa-chevron-right', 'url' => empty($tahun)? ['/pleno/sidang/view','id'=>$sidang[3],'tahap'=>$tahap] : ['/pleno/sidang/view','id'=>$sidang[3],'tahap'=>$tahap,'tahun'=>$tahun] ,'active'=> \Yii::$app->controller->id == 'sidang' && in_array(\Yii::$app->controller->action->id, ['view','hasil']),],
                ['label' => 'Sidang Pleno 2', 'icon' => 'fa fa-chevron-right', 'url' => empty($tahun)? ['/pleno/sidang/view','id'=>$sidang[2],'tahap'=>$tahap] : ['/pleno/sidang/view','id'=>$sidang[2],'tahap'=>$tahap,'tahun'=>$tahun] ,'active'=> \Yii::$app->controller->id == 'sidang' && in_array(\Yii::$app->controller->action->id, ['view','hasil'])],

            ];
        }

        $orang = Yii::$app->user->identity->orang == null ? new Orang() : Yii::$app->user->identity->orang;

        $modelPendaftaran = $orang->pendaftaran;

        $verifBoll = $orang->negara_id == 1 ? ($modelPendaftaran == null ? false : $modelPendaftaran->statusVerifikasiPin) : true;
        return [
            'options' => ['class' => 'sidebar-menu'],
            'items' => [
                ['label' => 'Navigation Menu', 'options' => ['class' => 'header']],
//                ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
//                ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']],
                ['label' => 'Login', 'icon' => 'fa fa-user', 'url' => ['/site/login'], 'visible' => Yii::$app->user->isGuest],
                ['label' => 'User Manager', 'icon' => 'fa fa-user', 'url' => ['/usermanager/users'],
                    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->accessRole_id == 1],
                ['label' => 'Manajemen Lock', 'icon' => 'fa fa-lock', 'url' => ['/admin/accesslock'],
                    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->accessRole_id == 8],
                ['label' => 'Manajemen NRP', 'icon' => 'fa fa-database', 'url' => ['/admin/editnrp'],
                    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->accessRole_id == 8],
                ['label' => 'User FDP Manager', 'icon' => 'fa fa-user', 'url' => ['/admin/user-fdp'],
                    'visible' => !Yii::$app->user->isGuest && (Yii::$app->user->identity->accessRole_id <= 3 || Yii::$app->user->identity->accessRole_id == 8)],
                ['label' => 'Post', 'icon' => 'fa fa-bookmark-o', 'url' => ['/admin/post'],
                    'visible' => !Yii::$app->user->isGuest && (Yii::$app->user->identity->accessRole_id <= 3 || Yii::$app->user->identity->accessRole_id == 8)],
                ['label' => 'Konfirmasi MHS Asing', 'icon' => 'fa fa-bookmark-o', 'url' => ['/admin/confirmasi-pin', 'tahun' => date('Y')],
                    'visible' => !Yii::$app->user->isGuest && (Yii::$app->user->identity->accessRole_id <= 3 || Yii::$app->user->identity->accessRole_id == 8)],
                ['label' => 'Broadcast Email', 'icon' => 'fa fa-envelope', 'url' => ['/site/broadcast', 'tahun' => date('Y')],
                    'visible' => !Yii::$app->user->isGuest && (Yii::$app->user->identity->accessRole_id <= 3 || Yii::$app->user->identity->accessRole_id == 8)],
                ['label' => 'Pendaftar', 'icon' => 'fa fa-user-plus', 'url' => ['#'],
                    'visible' => !Yii::$app->user->isGuest && (Yii::$app->user->identity->accessRole_id <= 3 || Yii::$app->user->identity->accessRole_id == 8),
                    'items' => [
                        ['label' => 'Sudah Verifikasi', 'icon' => 'fa fa-file-text-o', 'url' => ['/admin/verifikasi'],],
                        ['label' => 'Rekap Tahap 1', 'icon' => 'fa fa-bookmark-o', 'url' => empty($tahun)? ['/admin/rekap','tahap'=>1] : ['/admin/rekap','tahap'=>1,'tahun'=>$tahun],'active'=> \Yii::$app->controller->id == 'rekap' && @$_GET['tahap'] == 1,],
                        ['label' => 'Rekap Tahap 2', 'icon' => 'fa fa-bookmark-o', 'url' => empty($tahun)? ['/admin/rekap','tahap'=>2] : ['/admin/rekap','tahap'=>2,'tahun'=>$tahun],'active'=> \Yii::$app->controller->id == 'rekap' && @$_GET['tahap'] == 2,],

                    ],
                ],

                ['label' => 'Proses Seleksi PMB', 'icon' => 'fa fa-user-secret', 'url' => ['#'],
                    'visible' => !Yii::$app->user->isGuest && !in_array(Yii::$app->user->identity->accessRole_id, [7,9]),
                    'items' => $sidangseleksi,
                ],
                ['label' => 'Panduan Seleksi PMB', 'icon' => 'fa fa-book', 'url' => ['#'],
                    'visible' => !Yii::$app->user->isGuest && !in_array(Yii::$app->user->identity->accessRole_id, [7,9]),
                    'items' => [
                        ['label' => 'Panduan Pengguna', 'icon' => 'fa fa-chevron-right', 'url' => ['/formulir/spsipb_tutorial-pmbpasca_sidangPleno1.pdf'],],
                        ['label' => 'Kriteria Seleksi', 'icon' => 'fa fa-chevron-right', 'url' => ['/formulir/kriteria_seleksi_penerimaan_mahasiswa_baru.pdf'],],
                    ],
                ],
                ['label' => Lang::t('Verifikasi PIN','PIN Verification'), 'icon' => 'fa fa-plus', 'url' => ['/pendaftaran/verifikasi/pin'],
                    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->accessRole_id == 7 &&
                        !$verifBoll,
                ],
                ['label' => Yii::t('pmb','Complete the Data'), 'icon' => 'fa fa-plus', 'url' => ['/pendaftaran/lengkap-data'],
                    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->accessRole_id == 7,
                ],
                ['label' => Lang::t('Cetak Formulir A','Print Form A'), 'icon' => 'fa fa-plus',
                    'url' => ['/pendaftaran/lengkap-data/cetak', 'id' => FormLengkapData::$noPendaftaran],
                    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->accessRole_id == 7
                        && FormLengkapData::$setujustatic == 1,
                ],
                ['label' => Lang::t('Pengumuman Seleksi','Announcement'), 'icon' => 'fa fa-plus',
                    'url' => ['/pendaftaran/default/pengumuman'],
                    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->accessRole_id == 7
                        &&  date('Y-m-d H:i:s') >= $tanggalPengumuman[1],
                ],
                ['label' => 'Absensi', 'icon' => 'fa fa-plus',
                    'url' => ['#'],
                    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->accessRole_id != 7,
                    'items' => [
                        ['label' => 'Absensi M0', 'icon' => 'fa fa-chevron-right', 'url' => ['/verifikasi/absen'],
                            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->username != 7,],
                        ['label' => 'Absensi M3', 'icon' => 'fa fa-chevron-right', 'url' => ['/verifikasi/absenm3'],
                            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->username != 7,],
                        ['label' => 'Absensi M4', 'icon' => 'fa fa-chevron-right', 'url' => ['/verifikasi/absenm4'],
                            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->username != 7,],
                        ['label' => 'Absensi M5', 'icon' => 'fa fa-chevron-right', 'url' => ['/verifikasi/absenm5'],
                            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->username != 7,],
                    ],
                ],
				['label' => 'Verifikasi Berkas', 'icon' => 'fa fa-plus',
                    'url' => ['#'],
                    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->accessRole_id != 7,
                    'items' => [
                        ['label' => 'M0/M1', 'icon' => 'fa fa-chevron-right', 'url' => ['/verifikasi/proses','meja'=>'m0'],
                            'visible' => !Yii::$app->user->isGuest && in_array(Yii::$app->user->identity->username,['m0', 'm1']),],
                        ['label' => 'M2', 'icon' => 'fa fa-chevron-right', 'url' => ['/verifikasi/proses','meja'=>'m2'],
                            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->username == 'm2',],
                        ['label' => 'M3', 'icon' => 'fa fa-chevron-right', 'url' => ['/verifikasi/proses','meja'=>'m3'],
                            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->username == 'm3',],
                        ['label' => 'Daftar Beasiswa', 'icon' => 'fa fa-chevron-right', 'url' => ['/verifikasi/list-beasiswa'],
                            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->username == 'm3',],
                        ['label' => 'M4', 'icon' => 'fa fa-chevron-right', 'url' => ['/verifikasi/proses','meja'=>'m4'],
                            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->username == 'm4',],
                        ['label' => 'M5', 'icon' => 'fa fa-chevron-right', 'url' => ['/verifikasi/proses','meja'=>'m5'],
                            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->username == 'm5',],
                        ['label' => 'M6', 'icon' => 'fa fa-chevron-right', 'url' => ['/verifikasi/proses','meja'=>'m6'],
                            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->username == 'm6',],
                        ['label' => 'MK', 'icon' => 'fa fa-chevron-right', 'url' => ['/verifikasi/proses','meja'=>'mk'],
                            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->username == 'mk',],
                        ['label' => 'tolak', 'icon' => 'fa fa-chevron-right', 'url' => ['/verifikasi/proses','meja'=>'tolak'],
                            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->username == 'tolak',],
						['label' => 'Log', 'icon' => 'fa fa-chevron-right', 'url' => ['/verifikasi/proses/log'],
                            'visible' => !Yii::$app->user->isGuest && in_array(Yii::$app->user->identity->username,['m0', 'm1', 'm4', 'm5']),],
                    ],
                ],
            ],

        ];
    }

    public static function listMenu()
    {

    }

    public static function parentMenu($start, $arrlist = [])
    {
        $idgrup = Yii::$app->user->identity->grup_user_id;
        $grup = GrupUser::findOne($idgrup);

        if (empty($arrlist))
            $arrlist = [];

        $seting = Json::decode($grup->setting);
        if (empty($seting['menu']))
            $seting['menu'] = [];
        $arrMenu = [];
        $menu = self::getMenu();
        //print_r($menu);die;
        if (!empty($menu)) {
            foreach ($menu as $m) {
                if ($m['id'] != $start) {
                    if ($m['parent'] == $start)
                        $arrMenu[$m['kode']] = [
                            'id' => $m['id'],
                            'name' => $m['name'],
                            'url' => $m['url'],
                            'kode' => $m['kode'],
                            'class' => '',
                            'select' => in_array($m['id'], $arrlist),
                            'visible' => self::getvisible($m['id'], $seting['menu']),
                            'active' => Url::to() == $m['url'],
                            'child' => self::parentMenu($m['id'], $arrlist),
                        ];
                }
            }
        }
        return $arrMenu;
    }

    public static function makeTagChild($arrtag)
    {
        $li = '';
        if (isset($arrtag['visible']) && $arrtag['visible']) {
            $url = Url::to($arrtag['url'] == '' ? '#' : $arrtag['url']);
            $a = Html::a($arrtag['name'], $url);
            if ($arrtag['class'] == 'navigation-header')
                $a = $arrtag['name'];
            $ul = '';
            $clsAktiv = '';
            if (isset($arrtag['child']) && !empty($arrtag['child'])) {
                $child = '';
                foreach ($arrtag['child'] as $idx => $item) {
                    if ($idx != 'class')
                        $child .= self::makeTagChild($item);
                }
                $ul = Html::tag('ul', $child, [
                    'class' => isset($arrtag['child']['class']) ? $arrtag['child']['class'] : '']);
            }
            if ($arrtag['active'])
                $clsAktiv = 'active';
            $li = Html::tag('li', $a . $ul, ['class' => (isset($arrtag['class']) ? $arrtag['class'] : '') . ' ' . $clsAktiv]);
        }
        return $li;
    }

    public static function makeTag($arrtag)
    {
        $isi = '';
        foreach ($arrtag as $arr)
            $isi .= self::makeTagChild($arr);

        return Html::tag('ul', $isi, ['class' => "navigation navigation-main navigation-accordion"]);
    }

    public static function generateMenu()
    {

        $menu = self::$menu;

        if (!Yii::$app->user->isGuest) {
            $menu2 = self::parentMenu(0);

            foreach ($menu2 as $idx => $mn)
                $menu[$idx] = $mn;
        }
        return self::makeTag($menu);
    }

    public static function makeUmChild($arrtag)
    {
        $checklist = $arrtag['select'] == 1 ? 'checked' : '';
        $url = 'kode' . $arrtag['kode'];
        $ul = Html::tag('div', Html::tag('div', '', [
            'class' => 'panel-body',
            'style' => "padding: 0px 0px; padding-left: 30px;",]), [
            'id' => $url,
            'class' => "panel-collapse collapse",
        ]);
        $ipt =
            '<div class="pull-right" style="margin-right: 40px">
                <input value="' . $arrtag['id'] . '" type="checkbox" name="GrupUser[menu][]" class="switchery" ' . $checklist . '>
             </div>';

        if (strpos($arrtag['name'], 'Dashboard') !== false)
            $ipt = "";

        if (isset($arrtag['child']) && !empty($arrtag['child'])) {
            $a = Html::a($arrtag['name'], '#' . $url, [
                'data-toggle' => "collapse",
                'data-parent' => "#accordion-control-right",
                'class' => "collapsed",
            ]);


            $hedr = Html::tag('h6', $a . $ipt
                , ['class' => "panel-title"]);
            $dh = Html::tag('div', $hedr, ['class' => "panel-heading ", 'style' => "padding: 7px 15px;"]);
            $child = '';
            foreach ($arrtag['child'] as $idx => $item) {
                if ($idx != 'class')
                    $child .= self::makeUmChild($item);
            }
            $div = Html::tag('div', $child, [
                'class' => "panel-group panel-group-control panel-group-control-right content-group-lg"]);
            $ul = Html::tag('div', Html::tag('div', $div, [
                'class' => 'panel-body',
                'style' => "padding: 0px 0px; padding-left: 30px;",]), [
                'id' => $url,
                'class' => "panel-collapse collapse",
            ]);
        } else {
            $a = Html::tag('span', $arrtag['name'], [


            ]);
            $hedr = Html::tag('h6', $a . $ipt, ['class' => "panel-title"]);
            $dh = Html::tag('div', $hedr, ['class' => "panel-heading ", 'style' => "padding: 7px 15px;"]);
        }

        $li = Html::tag('div', $dh . $ul, ['class' => 'panel panel-white']);
        return $li;
    }

    public static function makeUmTag($arrtag)
    {
        $isi = '';
        foreach ($arrtag as $arr)
            $isi .= self::makeUmChild($arr);

        return Html::tag('div', $isi, [
            'class' => "panel-group panel-group-control panel-group-control-right content-group-lg",
            'id' => "treemenu",
        ]);
    }


    public static function generateMenuUm($arrmnulist = [])
    {

        $menu[100] = [
            'id' => 100,
            'name' => "User Manager",
            'url' => '#',
            'kode' => 'usermanager',
            'class' => '',
            'select' => self::accessMenu(100),
            'visible' => true,
            'active' => true,
            'child' => '',
        ];

        $view = Yii::$app->getView();

        $menu2 = self::parentMenu(0, $arrmnulist);
        foreach ($menu2 as $idx => $mn)
            $menu[$idx] = $mn;

        SwitcheryAsset::register($view);

        $jssript = <<< JS
            var elems;
            if (Array.prototype.forEach) {
                elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
                elems.forEach(function(html) {
                    var switchery = new Switchery(html);
                  
                });
            } else {
                elems = document.querySelectorAll('.switchery');
                for (var i = 0; i < elems.length; i++) {
                    var switchery = new Switchery(elems[i]);
                   
                }
            }
            
            var domtree = $('#treemenu');
            
            domtree.find('[type=checkbox]').on('change', function(e) {
                var elemen = $(this);
                var a = elemen.parent().parent().parent().find('a');
                var ch = this.checked;
                
                if (a.attr('href') != undefined){
                console.log(a.attr('href'));
                    domtree.find(a.attr('href')).find('[type=checkbox]').each(function() { 
                        if (ch != this.checked)
                            $(this).click();
                    });
                }
            });
            
JS;

        $view->registerJs($jssript, View::POS_READY, 'mymenulist');

        return self::makeUmTag($menu);
    }


    public static function getvisible($id, $arrlist)
    {
        if (Yii::$app->user->identity->id == 1)
            return true;
        $menu = Menu::find()->where(['parent_id' => $id])->all();
        if (!empty($menu)) {
            $sm = [];
            foreach ($menu as $item) {
                if (in_array($item->id, $arrlist))
                    return true;
                else
                    $sm[] = self::getvisible($item->id, $arrlist);
            }
            return in_array(true, $sm);
        } else
            return in_array($id, $arrlist);
    }

    public static function accessMenu($id)
    {
        $idrule = Yii::$app->user->identity->accessRoleId;
        $ruleaccess = Accessrole::findOne($idrule);

        $seting = Json::decode($ruleaccess->ruleSettings);
        if (empty($seting['menu']))
            $seting['menu'] = [];
        return in_array($id, $seting['menu']);
    }


}
