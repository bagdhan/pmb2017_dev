<?php
/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $switch int */
/* @var $model \app\modules\pendaftaran\models\Pendaftaran*/

use app\modules\pendaftaran\models\FormLengkapData;
use yii\widgets\ActiveForm;
use app\components\Lang;

$this->params['sidebar'] = 0;

\app\modules\pendaftaran\models\tempWiz::renderCSS();
//\app\modules\pendaftaran\models\tempWiz::renderJS();

\app\assets\AutoCompleteAsset::register($this);

$stap = FormLengkapData::findStep($switch);

$this->title = Yii::t('app', isset($stap['name']) ? $stap['name'] : 'PMB');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="">

    <div class="stepwizard" id="topwiz">
        <div class="stepwizard-row setup-panel">
            <?= FormLengkapData::renderHeader($switch) ?>
        </div>
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'form_registrasi',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-8\">{input}{hint}{error}</div>",
            'labelOptions' => ['class' => 'col-md-4 control-label'],

        ],
    ]);

    switch ($switch) {
        case '1' :
            ?>
            <div class="row setup-content">
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <h3><?= Lang::t('Data Diri','Personal Data')?></h3>
                        <?= $this->render('step/datapribadi', ['form' => $form, 'datapribadi' => $datapribadi]); ?>
                        <h3><?= Lang::t('Alamat/Kontak','Address / Contact')?></h3>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->render('step/alamat', ['form' => $form, 'alamat' => $alamat]); ?>
                            </div>
                            <div class="col-md-6">
                                <?= ''//$this->render('step/kontak', ['form' => $form, 'kontak' => $kontak]); ?>
                                <?= $this->render('step/identitas', ['form' => $form, 'identitas' => $identitas]); ?>
                            </div>
                        </div>
                        <?= FormLengkapData::renderButton($switch) ?>
                    </div>
                </div>
            </div>

            <?php
            break;
        case '2' :
            ?>

            <div class="row setup-content">
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <h3><?= $this->title?></h3>
                        <p><?= Lang::t('Siapakah yang akan menanggung biaya pendidikan di Sekolah Pascasarjana IPB?',
                                'Who will support the education fee at IPB Graduate School?')?></p>
                        <?= $this->render('step/rencanabiaya', ['form' => $form, 'rencanabiaya' => $rencanabiaya]); ?>

                        <?= FormLengkapData::renderButton($switch) ?>
                    </div>
                </div>
            </div>


            <?php
            break;
        case '3' :
            ?>

            <div class="row setup-content">
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <h3><?= $this->title?></h3>
                        <p><?= Lang::t('Strata Pendidikan yang ingin diikuti','Education degree')?> </p>
                        <?= $this->render('step/pilihprodi', ['form' => $form, 'pilihprodi' => $pilihprodi, 'model' => $model]); ?>

                        <?= FormLengkapData::renderButton($switch) ?>
                    </div>
                </div>
            </div>

            <?php
            break;
        case '4' :
            ?>

            <div class="row setup-content">
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <h3><?= $this->title?></h3>
                        <?= $this->render('step/pendidikan', ['form' => $form, 'pendidikan' => $pendidikan, 'model' => $model]); ?>

                        <div class="margin">
                            <?= FormLengkapData::renderButton($switch) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            break;
        case '5' :
            ?>

            <div class="row setup-content">
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <h3><?= $this->title?></h3>

                        <?= $this->render('step/pekerjaan', ['form' => $form, 'pekerjaan' => $pekerjaan]); ?>

                        <div class="margin">
                            <?= FormLengkapData::renderButton($switch) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            break;
        case '6' :
            ?>

            <div class="row setup-content">
                <div class="col-xs-12">
                    <div class="col-md-12">

                        <p><?= Lang::t('Isi kolom berikut ini, jika anda pernah mengikuti test TOEFL atau TPA atau keduanya.',
                                'Fill in the TOEFL score')?></p>
                        <?= $this->render('step/syaratTambahan', ['form' => $form, 'syaratTambahan' => $syaratTambahan, 'dataSyarat' => $dataSyarat]); ?>

                        <div class="margin">
                            <?= FormLengkapData::renderButton($switch) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            break;
        case '7' :
            ?>

            <div class="row setup-content">
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <h3><?= $this->title?></h3>
                        <p><?= Lang::t('Masukkan tiga nama pemberi rekomendasi sebagai syarat untuk mendaftar ke Sekolah Pascasarjana IPB.',
                                'Include three names of recommenders')?></p>
                        <?= $this->render('step/rekomendasi', ['form' => $form, 'rekomendasi' => $rekomendasi, 'dataRekom' => $dataRekom]); ?>

                        <div class="margin">
                            <?= FormLengkapData::renderButton($switch) ?>
                        </div>
                    </div>
                </div>

            </div>
            <?php
            break;
        case '8' :
            ?>

            <div class="row setup-content">
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <h3><?= Lang::t('Publikasi', 'Publication')?></h3>
                        <p><?= Lang::t('Terkait dengan publikasi karya ilmiah, pilih satu atau lebih:',
                                'Select one or more:')?></p>
                        <?= $this->render('step/publikasi', ['form' => $form, 'publikasi' => $publikasi]); ?>

                        <div class="margin">
                            <?= FormLengkapData::renderButton($switch) ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            break;
        case '9' :
            ?>

            <div class="row setup-content">
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <?= $this->render('step/upload', ['form' => $form, 'upload' => $upload]); ?>

                        <div class="margin">
                            <?= FormLengkapData::renderButton($switch) ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            break;
        case '10' :
            ?>

            <div class="row setup-content">
                <div class="col-xs-12">
                    <div class="col-md-12">

                        <?= $this->render('step/selesai', ['form' => $form, 'model' => $model]); ?>

                        <div class="margin">
                            <?= FormLengkapData::renderButton($switch) ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            break;
        default:
            throw new \yii\web\NotAcceptableHttpException('NO Page.');

    }
    ActiveForm::end(); ?>
</div>

