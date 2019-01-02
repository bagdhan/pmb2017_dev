<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/23/2017
 * Time: 2:31 PM
 */

namespace app\modules\pendaftaran\models;

use app\components\Lang;
use app\modelsDB\PendaftaranHasProgramStudi;
use app\modelsDB\StatusForm;
use app\modules\pendaftaran\models\lengkapdata\DataPribadi;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotAcceptableHttpException;

/**
 * Class FormLengkapData
 * @package app\modules\pendaftaran\models
 *
 * @property ::$modelPendaftaran Pendaftaran
 */
class FormLengkapData extends Model
{

    public $_stepActive = 1;

    public static $noPendaftaran;

    /** @var Pendaftaran */
    public static $modelPendaftaran;

    public static $statusForm;

    public $idOrang;

    public $strata;

    public $header;
    public $button;
    public $content;
    public $name;
    public $id;


    public $setuju;
    public static $setujustatic;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setuju',], 'required'],
            ['setuju', 'syaratSetuju'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function syaratSetuju($attribute, $params)
    {
        if (!$this->hasErrors()) {

            if ($this->setuju != 1) {
                $this->addError($attribute, 'Anda belum menyetujui pernyataan.');
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'setuju' => Lang::t('Apakah anda setuju dengan peryataan di atas.',
                'Do you agree with the statement above.'),

        ];
    }

    /**
     * FormLengkapData constructor.
     * @param array $config
     * @throws NotAcceptableHttpException
     */
    public function __construct(array $config = [])
    {
        $this->idOrang = Yii::$app->user->identity->orang_id;
        $orang = Yii::$app->user->identity->orang;
        $dataDaftar = Pendaftaran::findOne(['orang_id' => $this->idOrang]);
        if (empty($dataDaftar)) {
            throw new NotAcceptableHttpException('No pendaftaran Tidak Valid');
        } else {
            if (!empty($dataDaftar->pinVerifikasi) && $dataDaftar->pinVerifikasi->status == 1) {
                self::$noPendaftaran = $dataDaftar->noPendaftaran;//Yii::$app->user->identity->orang->pendaftarans[0]->noPendaftaran;
                self::$modelPendaftaran = $dataDaftar;
                static::setStatusForm();
                $this->setuju = $dataDaftar->setujuSyarat;
                $this->setStrata();
                self::$setujustatic = $dataDaftar->setujuSyarat;
            } else {
                $t = $orang != null && $orang->negara_id == 1 ? "Anda belum melakukan Verifikasi PIN dengan 
                    No Pendaftaran $dataDaftar->noPendaftaran. <br>" .
                    Html::a('Silahkan Verifikasi PIN disini', ['/pendaftaran/verifikasi/pin'])
                    : 'Please wait for confirmation from our administrator to proceed to the next step. contact +62-251- 8628448';

                throw new NotAcceptableHttpException($t);
            }
        }
        $this->setStep();
        parent::__construct($config);
    }


    private static function setStep()
    {
        self::$step = [
            1 => [
                'id' => 1,
                'name' => Lang::t('Personal', 'Personal'),
            ],
            2 => [
                'id' => 2,
                'name' => Lang::t('Perencanaan Biaya', 'Source of Funding'),
            ],
            3 => [
                'id' => 3,
                'name' => Lang::t('Pilih Program Studi', 'Select Program'),
            ],
            4 => [
                'id' => 4,
                'name' => Lang::t('Pendidikan', 'Educational Background'),
            ],
            5 => [
                'id' => 5,
                'name' => Lang::t('Pekerjaan', 'Employment History'),
            ],
            6 => [
                'id' => 6,
                'name' => Lang::t('Syarat Tambahan','Additional Terms'),
            ],
            7 => [
                'id' => 7,
                'name' => Lang::t('Rekomendasi','Recommendation'),
            ],
            8 => [
                'id' => 8,
                'name' => Lang::t('Publikasi','Publication'),
            ],
            9 => [
                'id' => 9,
                'name' => Lang::t('Unggah Berkas','Upload Document'),
            ],
            10 => [
                'id' => 10,
                'name' => Lang::t('Selesai','Done'),
            ],
        ];
    }

    public static $step = [];

    /**
     * @inheritdoc
     */
    public static function findStep($id)
    {
        self::setStep();
        return isset(self::$step[$id]) ? new static(self::$step[$id]) : null;
    }

    public static function renderButton($id)
    {
        $wizButton = '';
        $btnDis = self::$setujustatic == 1 ? 'disabled' : '';
        if ($id == 10)
            $wizButton .= Html::button(Lang::t('Selesai', 'Completed'),
                ['class' => 'btn btn-success nextBtn pull-right', 'type' => "submit",
                    'data-confirm' => Yii::t('yii', 'Apakah anda yakin data sudah selesai? 
Perhatian!!! Setelah anda menyetujui ini perubahan data tidak bisa dilakukan kembali.')]);
        if ($id != 10)
            $wizButton .= Html::button(Lang::t('Simpan dan Lanjutkan', 'Save and Continue'),
                ['class' => 'btn btn-primary nextBtn pull-right', 'type' => "submit", $btnDis => '']);
        if ($id != 1)
            $wizButton .= Html::a(Lang::t('Kembali', 'Back'), ['/pendaftaran/lengkap-data/', 'step' => ($id - 1),],
                ['class' => 'btn btn-info nextBtn pull-right', 'type' => "button", 'style' => 'margin-right: 10px;']);

        return $wizButton;
    }

    /**
     * @param $switch
     * @return string
     */
    public static function renderHeader($switch)
    {
        $wizHeader = '';

        foreach (self::$step as $idx => $stepname) {
            $numForm = 'form' . $idx;
            $active = $switch == $idx ? 'cuse' : '';
            if (self::$statusForm->$numForm > 0 || $idx == 1) {
                $class = 'primary';
                $url = ['/pendaftaran/lengkap-data/', 'step' => $idx,];
                $disable = '';
                if (self::$statusForm->$numForm == 2) {
                    $class = 'success';

                }

            } else {
                $class = 'default';
                $url = 'javascript: void(0)';
                $disable = 'disabled';
            }

            $wizHeader .= Html::tag('div',
                Html::a("$idx", $url,
                    ['class' => "btn btn-$class btn-circle", 'type' => "button", $disable => '']) .
                Html::tag('p', ""),
                ['class' => 'stepwizard-step ' . $active]);
        }

        return $wizHeader;
    }

    /**
     * @param StatusForm $statusForm
     */
    public static function setStatusForm()
    {
        self::$statusForm = StatusForm::findOne(['noPendaftaran' => self::$noPendaftaran]);
        if (empty(self::$statusForm)) {
            self::$statusForm = new StatusForm();
            self::$statusForm->noPendaftaran = self::$noPendaftaran;
            self::$statusForm->form1 = 1;
            self::$statusForm->save(false);
        } else {
            if (self::$statusForm->form1 != 2) {
                self::$statusForm->form1 = 1;
                self::$statusForm->save(false);
            }
        }
    }


    /**
     * @return mixed
     */
    public function getOrang()
    {
        if (!isset(Yii::$app->user->identity->orang_id))
            return Yii::$app->response->redirect(Url::to(['/site', 'id' => 1]));

        return DataPribadi::findOne(Yii::$app->user->identity->orang_id);
    }

    /**
     * @param mixed $strata
     */
    public function setStrata()
    {
        $pendaftarProdi = PendaftaranHasProgramStudi::findOne(['pendaftaran_noPendaftaran' => self::$noPendaftaran]);
        $this->strata = empty($pendaftarProdi) ? 2 : $pendaftarProdi->programStudi->strata;
    }

    public function saveData()
    {
        if (!$this->validate()) {
            return false;
        }
        $pendaftar = Pendaftaran::findOne(['noPendaftaran' => self::$noPendaftaran]);
        $pendaftar->setujuSyarat = 1;
        return $pendaftar->save();
    }
}