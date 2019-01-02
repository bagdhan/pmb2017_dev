<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use app\components\Lang;

$this->title = '';
// Lang::t('Pendaftaran Mahasiswa Baru (PMB) Sekolah Pascasarjana IPB','Pendaftaran Mahasiswa Baru (PMB) Sekolah Pascasarjana IPB en');
$this->params['sidebar'] = 0;

$nopen = empty(Yii::$app->user->identity->orang->pendaftarans) ? '' :
    Yii::$app->user->identity->orang->pendaftarans[0]->noPendaftaran;

$activUrl = \app\models\PaketPendaftaran::findActive('Reguler', true);
$activUrlKhusus = \app\models\PaketPendaftaran::findActive('Khusus', true);
$activUrlbyResearch = \app\models\PaketPendaftaran::findActive('by Research', true);

$linkkirimemailr = Html::a(
    '<span class="badge bg-purple"></span>
                    <i class="fa fa-users"></i> '.Lang::t('Reguler','Regular'), ['/site/register', 'jenismasuk' => $activUrl],
        ['class' => 'btn btn-app']
    );

$linkkirimemailk = Html::a(
    '<span class="badge bg-purple"></span>
                    <i class="fa fa-star"></i> '.Lang::t('Kelas Khusus','Non Regular'), ['/site/register', 'jenismasuk' => $activUrlKhusus],
        ['class' => 'btn btn-app btn-sm']
    );

$linkkirimemail = Yii::$app->user->isGuest ? $linkkirimemailr . $linkkirimemailk : Html::a(
    Lang::t('Kirim ulang No Pendaftaran ke email','Resend Registration Number to email'), ['/api/send-ulang', 'nopen' => $nopen],
    ['style' => 'color: #fff;', 'id' => 'sendemail']);

$contenpanelregister = '<div class="small-box bg-yellow">
                                <div class="inner">
                                    <h4>'.Lang::t('Membuat Akun Pendaftaran','Create Account').'</h4>
                                    <p>' . $linkkirimemail . ' </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div> </div>';

$panelregister =  $contenpanelregister;
?>
    <div class="site-index">


        <!--<div class="jumbotron">
            <div class="row" style="margin: 10px;">
                <?php if (Yii::$app->user->isGuest ||
                    (isset(Yii::$app->user->identity->accessRole_id) &&
                        Yii::$app->user->identity->accessRole_id == 7)
                ) { ?>
                    <div class="col-lg-4 col-xs-12">

                        <?= $panelregister ?>

                    </div>

                    <?php
//                    $p = Yii::$app->user->identity->orang->pendaftaran->pinVerifikasi;
//                $p->status = 1 ;$p->save();
//                    print_r('');die;
                    if (Yii::$app->user->isGuest || (!Yii::$app->user->isGuest && Yii::$app->user->identity->orang != null &&
                        Yii::$app->user->identity->orang->negara_id == 1)) {


                        ?>
                    <div class="col-lg-4 col-xs-12">

                        <?= Html::a('<div class="small-box bg-aqua">
                    <div class="inner">
                        <h4>'.Lang::t('Verifikasi PIN','PIN Verification').'</h4>
                        <p><br></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-refresh"></i>
                    </div> </div>', ['/pendaftaran/verifikasi/pin']) ?>

                    </div>
                    <?php }?>
                    <div class="col-lg-4 col-xs-12">

                        <?= Html::a('<div class="small-box bg-green">
                    <div class="inner">
                        <h4>'.Lang::t('Lengkapi Data','Complete The Data').'</h4>
                        <p><br></p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-database"></i>
                    </div> </div>', ['/pendaftaran/lengkap-data?step=1']) ?>

                    </div>

                <?php } elseif (!Yii::$app->user->isGuest && Yii::$app->user->identity->accessRole_id <= 3) { ?>

                    <div class="col-lg-4 col-xs-12">

                        <?= Html::a('<div class="small-box bg-yellow">
                                <div class="inner">
                                  <h3>' . $akun . '</h3>
                                  <p><strong>'.Lang::t('Membuat Akun','Create Account').'</strong></p>
                                </div>
                                <div class="icon">
                                  <i class="ion ion-person-add"></i>
                                </div>
                            </div>') ?>

                    </div>

                    <div class="col-lg-4 col-xs-12">
                        <?= Html::a('<div class="small-box bg-aqua"> 
                                    <div class="inner">
                                      <h3>' . $verif . '</h3>
                                      <p><strong>'.Lang::t('Verifikasi PIN','PIN Verification').'</strong></p>
                                  </div>
                                  <div class="icon">
                                      <i class="ion ion-refresh"></i>
                                  </div>
                              </div>') ?>

                    </div>
                    <div class="col-lg-4 col-xs-12">
                        <?= Html::a('<div class="small-box bg-green">
                            <div class="inner">
                              <h3>' . $lengkap . '</h3>
                              <p><strong>'.Lang::t('Data Lengkap','Complete The Data').'</strong></p>
                          </div>
                          <div class="icon">
                              <i class="fa fa-database"></i>
                          </div>
                      </div>') ?>

                    </div>

                <?php } ?>
            </div>


        </div>!-->

        <div class="body-content">

            <div class="row">
                <?php foreach($post as $value){?>
                <div class="col-lg-4">
                    <h2><?= Lang::id()? $value->title : $value->title_en;?></h2>
                    <?= Lang::id()? $value->content : $value->content_en;?>
                    <?php if($value->url != '#'){?>
                    <p><a class="btn btn-default"
                          href="<?= \yii\helpers\Url::to([$value->url]) ?>"><?= Lang::t('Baca lebih banyak','Read More')?> &raquo;</a></p>
                    <?php } ?>
                </div>
                <?php }?>
            </div>

        </div>
    </div>


<?php


$jsCode = <<< JS

$('#sendemail').on('click', function(e) {
    e.preventDefault();
    var a = $(e.target);
    var div = $(e.target).closest('div');
    
    
    $.ajax({
        type      : 'POST',
        url       : a.attr('href'),
        beforeSend: function () {
            div.find('p').append('<i class="fa fa-refresh fa-spin"></i>');
        },
        success   : function (data) {
            div.find('p').find('i').remove();
            if (data == 'true')
                div.find('p').append('<i class="fa fa-check" style="color:green;"></i>');
            else 
                div.find('p').append('<i class="fa fa-close" style="color:red;"></i>');
        },
        error     : function () {
            div.find('p').find('i').remove();
            div.find('p').append('<i class="fa fa-close" style="color:red;"></i>');
        }
    });
})
JS;

$this->registerJs($jsCode, \yii\web\View::POS_READY, 'homedir')
?>