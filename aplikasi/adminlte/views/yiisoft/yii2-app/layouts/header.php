<?php
/**
 *
 * @var $directoryAsset string
 * @var $this \yii\web\View
 * @var $content string
 *
 */

use app\adminlte\widgets\MenuHeader;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Orang;
use app\components\languageSwitcher;
use app\components\Lang;

$model = new \app\models\LoginForm();

$username = Yii::$app->user->isGuest ? 'User' : Yii::$app->user->identity->username;
$deskripUser = !Yii::$app->user->isGuest && Yii::$app->user->identity->accessRole_id > 0 ?
    ' - ' . Yii::$app->user->identity->accessRole->roleName : ' - Guest';
$sincedate = empty(Yii::$app->user->identity->dateCreate) ? '--' :
    date(' M. Y', strtotime(Yii::$app->user->identity->dateCreate));

$activUrl = \app\models\PaketPendaftaran::findActive('Reguler');
$activUrlK = \app\models\PaketPendaftaran::findActive('Khusus');
$activUrlbRc = \app\models\PaketPendaftaran::findActive('by Research');

$activUrlR = \app\models\PaketPendaftaran::findActive('Reguler', true);
$activUrlKR = \app\models\PaketPendaftaran::findActive('Khusus', true);
$activUrlbRcR = \app\models\PaketPendaftaran::findActive('by Research', true);

?>

<header class="kop clearfix">
    <div class="row">
        <div class="col-lg-2 col-md-2  logo" align='center' style="top: 40px;">
            <img width="100" src="<?= $directoryAsset ?>/img/logo.png" alt="">
        </div>
        <div class="col-lg-9 col-md-4 hidden-sm hidden-xs logo" align='center' style="top: 45px;">
            <h3><strong><?= Lang::t('Pendaftaran Mahasiswa Baru','Student Admission')?> <br> <?= Lang::t('Sekolah Pascasarjana IPB','Graduate School of Bogor Agricultural University')?></strong></h3>
        </div>
    </div>
</header>

<?php if (Yii::$app->user->isGuest) { ?>
    <header class="main-header">
        <nav class="navbar navbar-static-top">
            <div class="container">

                <div class="navbar-header">
                    <!--                <a href="../../index2.html" class="navbar-brand">PMB</a>-->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#navbar-collapse">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                    <?= MenuHeader::widget([
                        'options' => ['class' => 'nav navbar-nav'],
                        'items' => [
                            ['label' => Lang::t("Beranda", "Home"), 'icon' => 'fa fa-home', 'url' => ['/site/index'],],
//                            ['label' => 'Berita', 'url' => ['#'],],
                            ['label' => Lang::t('Agenda','Agenda'), 'url' => ['/info/jadwal-pmb'],], // jadwal banyak
                            ['label' => Lang::t('Tata Cara/Tahapan','Procedures'),
                                'url' => ['/info/tahapan-pmb'],
                                'options' => ['class' => 'dropdown'],
                                'items' => [
                                    ['label' => Lang::t('Reguler','Regular'), 'url' => ['/info/tahapan-pmb'],],
                                    ['label' => Lang::t('Kelas Khusus & Profesional','Non Regular'), 'url' => ['/info/tahapank', 'jenismasuk' => $activUrlK],],
                                ],
                            ], // syarat pak heru
                            ['label' => Lang::t('Informasi','Information'), 'url' => ['#'],
                                'options' => ['class' => 'dropdown'],
                                'items' => [
                                    ['label' => Lang::t('Kurikulum','Curriculum'),
                                        'template' => '<a href="http://pdps.pasca.ipb.ac.id/index.php/site/kurikulum" target="_blank">{label}</a>',
                                    ],
                                    ['label' => Lang::t('Jenis Pendaftaran','Type of Registration'), 'url' => ['/info/jenis'],],
                                    ['label' =>  Lang::t('Program Studi Reguler','Study Program (regular)'), 'url' => ['/info/list-prodi', 'jenismasuk' => $activUrl],],
                                    ['label' =>  Lang::t('Program Studi Kelas Khusus & Profesional','Study Program (Non-regular)'), 'url' => ['/info/list-prodi', 'jenismasuk' => $activUrlK],],
									//['label' =>  Lang::t('Program Studi Kelas by Research','Study Program (Non-regular)'), 'url' => ['/info/list-prodi', 'jenismasuk' => $activUrlbRc],],
                                    ['options' => ['class' => 'divider'],],
                                    ['label' => Lang::t('Beasiswa Kementerian','Ministry Scholarship'), 'template' => '{label}',
                                        'options' => ['class' => 'dropdown-header'],
                                    ],
                                    ['label' => 'LPDP - Kemenkeu',
                                        'template' => '<a href="https://www.lpdp.kemenkeu.go.id/pembukaan-beasiswa-lpdp-tahun-2018/" target="_blank">{label}</a>',
                                    ],
                                    ['label' => 'BUDI - Kemenkeu',
                                        'template' => '<a href="https://www.lpdp.kemenkeu.go.id/pembukaan-beasiswa-unggulan-dosen-indonesia-budi-2018/" target="_blank">{label}</a>',
                                    ],
									['label' => 'PasTi - Kemenristekdikti',
                                        'template' => '<a href="http://beasiswa.ristekdikti.go.id/pasti/index.php" target="_blank">{label}</a>',
                                    ],
                                    ['options' => ['class' => 'divider'],],
                                    ['label' => Lang::t('Beasiswa Swasta','Private Scholarship'),
                                        'template' => '{label}',
                                        'options' => ['class' => 'dropdown-header'],
                                    ],
                                    ['label' => 'Tanoto Fondation',
                                        'template' => '<a href="http://www.tanotofoundation.org/education/id/" target="_blank">{label}</a>',
                                    ],

                                ],],
                            ['label' => Lang::t('Program Kerjasama','Academic Collaboration Program'), 'url' => ['#'],
                                'options' => ['class' => 'dropdown'],
                                'items' => [
                                    ['label' => 'Double Degree', 'url' => ['/info/double-degree'],],
                                    ['label' => 'Joint Degree', 'url' => ['/info/joint-degree'],],
                                    ['label' => 'Credit Earning', 'url' => ['/info/credit-earning'],],
                                ],],
//                            ['label' => 'Panduan', 'url' => ['#'],], // yg bni
//                            ['label' => 'FAQ', 'url' => ['#'],],
                            ['label' => Lang::t('Kontak Kami','Contact'), 'url' => ['/info/kontakkami'],],
                        ],
                    ]) ?>

                </div><!-- /.navbar-collapse -->
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li>
                            <?= languageSwitcher::Widget() ?>
                        </li>
                        <li class="dropdown user user-menu">

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-lock"></i> <?= Lang::t("Masuk", "Login") ?>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header" style="height: 190px;">

                                    <div class="box box-info">

                                        <div class="box-body ">

                                            <?php $form = ActiveForm::begin([
                                                'id' => 'login-form',
                                                'action' => ['/site/login'],
                                                'options' => ['class' => 'form-horizontal'],
                                                'fieldConfig' => [
                                                    'template' => "{label}\n<div class=\"col-lg-12\">{input}{error}</div>",
                                                    'labelOptions' => ['class' => 'col-lg-3 control-label'],
                                                ],
                                            ]); ?>

                                            <?= $form->field($model, 'username')->textInput(['placeholder' => 'Nomor Pendaftaran'])
                                                ->label(false) ?>

                                            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Tanggal Lahir'])
                                                ->label(false) ?>

                                            <?= Html::submitButton('Login', [
                                                'class' => 'btn btn-flat btn-sm',
                                                'name' => 'login-button',
//                                                'style' => 'display:none;',
                                            ]) ?>

                                            <?php ActiveForm::end(); ?>

                                        </div>

                                    </div>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="col-xs-6 text-center">
                                        <?= Html::a(Lang::t('Membuat Akun ', 'Create Account'), ['/site/register', 'jenismasuk' => $activUrlR]) ?>
                                    </div>

                                    <div class="col-xs-6 text-center">
                                        <?= Html::a(Lang::t('Lupa Password','Forgot the Password'), ['/site/request-password-reset']) ?>
                                    </div>

                                </li>

                            </ul>
                        </li>

                    </ul>
                </div><!-- /.navbar-custom-menu -->
            </div><!-- /.container-fluid -->
        </nav>
    </header>

<?php } else { ?>
    <header class="main-header">
        <!-- Logo -->
        <a href="<?= Yii::$app->homeUrl ?>" class="logo">
            <!-- mini logo for sidebar mini     50x50 pixels -->
            <!--        <span class="logo-mini">PMB</span>-->
            <!-- logo for regular state and mobile devices -->
            <!--        <span class="logo-lg">PMB Pasca IPB</span>-->
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                    <?= MenuHeader::widget([
                        'options' => ['class' => 'nav navbar-nav'],
                        'items' => [
                            ['label' => Lang::t("Beranda", "Home"), 'icon' => 'fa fa-home', 'url' => ['/site/index'],],
//                            ['label' => 'Berita', 'url' => ['#'],],
                            ['label' => Lang::t('Agenda','Agenda'), 'url' => ['/info/jadwal-pmb'],], // jadwal banyak
                            ['label' => Lang::t('Tata Cara/Tahapan','Procedures'),
                                'url' => ['/info/tahapan-pmb'],
                                'options' => ['class' => 'dropdown'],
                                'items' => [
                                    ['label' => Lang::t('Reguler','Regular'), 'url' => ['/info/tahapan-pmb'],],
                                    ['label' => Lang::t('Kelas Khusus & Profesional','Non Regular'), 'url' => ['/info/tahapank', 'jenismasuk' => $activUrlK],],
                                ],
                            ], // syarat pak heru
                            ['label' => Lang::t('Informasi','Information'), 'url' => ['#'],
                                'options' => ['class' => 'dropdown'],
                                'items' => [
                                    ['label' => Lang::t('Kurikulum','Curriculum'),
                                        'template' => '<a href="http://pdps.pasca.ipb.ac.id/index.php/site/kurikulum" target="_blank">{label}</a>',
                                    ],
                                    ['label' => Lang::t('Jenis Pendaftaran','Type of Registration'), 'url' => ['/info/jenis'],],
                                    ['label' =>  Lang::t('Program Studi Reguler','Study Program (regular)'), 'url' => ['/info/list-prodi', 'jenismasuk' => $activUrl],],
                                    ['label' =>  Lang::t('Program Studi Kelas Khusus & Profesional','Study Program (Non-regular)'), 'url' => ['/info/list-prodi', 'jenismasuk' => $activUrlK],],
                                    ['options' => ['class' => 'divider'],],
                                    ['label' => Lang::t('Beasiswa Kementerian','Ministry Scholarship'), 'template' => '{label}',
                                        'options' => ['class' => 'dropdown-header'],
                                    ],
                                    ['label' => 'LPDP - Kemenkeu',
                                        'template' => '<a href="https://www.lpdp.kemenkeu.go.id/pembukaan-beasiswa-lpdp-tahun-2018/" target="_blank">{label}</a>',
                                    ],
                                    ['label' => 'BUDI - Kemenkeu',
                                        'template' => '<a href="https://www.lpdp.kemenkeu.go.id/pembukaan-beasiswa-unggulan-dosen-indonesia-budi-2018/" target="_blank">{label}</a>',
                                    ],
									['label' => 'PasTi - Kemenristekdikti',
                                        'template' => '<a href="http://beasiswa.ristekdikti.go.id/pasti/index.php" target="_blank">{label}</a>',
                                    ],
                                    ['label' => '5000 Doktor - Kementerian Agama',
                                        'template' => '<a href="http://5000doktor.diktis.id/registration" target="_blank">{label}</a>',
                                    ],
                                    ['options' => ['class' => 'divider'],],
                                    ['label' => Lang::t('Beasiswa Swasta','Private Scholarship'),
                                        'template' => '{label}',
                                        'options' => ['class' => 'dropdown-header'],
                                    ],
                                    ['label' => 'Tanoto Fondation',
                                        'template' => '<a href="http://www.tanotofoundation.org/education/id/" target="_blank">{label}</a>',
                                    ],

                                ],],
                            ['label' => Lang::t('Program Kerjasama','Academic Collaboration Program'), 'url' => ['#'],
                                'options' => ['class' => 'dropdown'],
                                'items' => [
                                    ['label' => 'Double Degree', 'url' => ['/info/double-degree'],],
                                    ['label' => 'Joint Degree', 'url' => ['/info/joint-degree'],],
                                    ['label' => 'Credit Earning', 'url' => ['/info/credit-earning'],],
                                ],],
//                            ['label' => 'Panduan', 'url' => ['#'],], // yg bni
//                            ['label' => 'FAQ', 'url' => ['#'],],
                            ['label' => Lang::t('Kontak Kami','Contact'), 'url' => ['/info/kontakkami'],],
                        ],
                    ]) ?>

                </div><!-- /.navbar-collapse -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li><?= languageSwitcher::Widget() ?></li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <?php
                    $dirfoto = Orang::getPhoto();
                    if (isset(Yii::$app->user->identity->orang->nama)) {
                        $name = Yii::$app->user->identity->orang->nama;
                        $type = ' - ' . Yii::$app->user->identity->accessRole->roleName;
                    } elseif (isset(Yii::$app->user->identity->username)) {
                        $name = Yii::$app->user->identity->username;
                        $type = '';
                    } else {
                        $name = 'Guest';
                        $type = '';
                    }

                    ?>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?= $dirfoto ?>" class="user-image"
                                 alt="Image of <?= $name ?>">
                            <span class="hidden-xs"><?= $name; ?></span>
                        </a>
                        <!-- Content of User Image -->
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="<?= $dirfoto ?>" class="img-circle"
                                     alt="Image of <?= $name ?>">
                                <p>
                                    <?= $name . $type; ?>
                                    <small>Member
                                        since <?= date('d, M Y', strtotime(Yii::$app->user->identity->dateCreate))
                                        ?></small>
                                </p>
                            </li>

                            <li class="user-footer">
                                <div class="pull-left">
                                    <?= Html::a(
                                        '<i class="fa fa-user"></i> Profile',
                                        ['site/index'],
                                        [
                                            'class' => 'btn btn-default btn-flat',
                                            //'data-toggle' => 'dropdown',
                                            'role' => 'button',
                                            'title' => 'profile',
                                            'alt' => 'Collapse the sidebar menu',
                                            'data-method' => 'POST'
                                        ]
                                    ) ?>
                                </div>
                                <div class="pull-right">
                                    <?= Html::a(
                                        '<i class="fa fa-sign-out"></i> Sign out',
                                        ['/site/logout'],
                                        [
                                            'class' => 'btn btn-default btn-flat',
                                            //'data-toggle' => 'dropdown',
                                            'role' => 'button',
                                            'title' => 'logout',
                                            'alt' => 'Collapse the sidebar menu',
                                            'data-method' => 'POST'
                                        ]
                                    ) ?>
                                </div>
                            </li>
                        </ul>
                    </li>


                </ul>
            </div>
        </nav>
    </header>


<?php } ?>
