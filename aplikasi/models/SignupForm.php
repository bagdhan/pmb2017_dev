<?php

namespace app\models;

use app\modelsDB\JenisIdentitas;
use app\modules\pendaftaran\models\Identitas;
use app\modules\pendaftaran\models\Kontak;
use app\modules\pendaftaran\models\Pendaftaran;
use app\modules\pendaftaran\models\PinVerifikasi;
use app\usermanager\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\NotAcceptableHttpException;

/**
 * Signup form
 *
 * @property int $paketPendaftaranID
 * @property int $choseNationality
 * @property int $idNegara
 */
class SignupForm extends Model
{
    // kebutuhan User
    public $username;
    public $email;
    public $password;
    public $email2;
    public $password2;

    //kebutuhan Orang
    public $namaLengkap;
    public $jenisKelamin;
    public $jenisIdentitas;
    public $identitas;
    public $noTlp;
    public $noHP;

    //capcha
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
//            ['username', 'unique',
//                'targetClass' => User::className(),
//                ],

            ['username', 'string', 'min' => 3, 'max' => 255],
            ['username', 'match', 'pattern' => '/^[a-z]\w*$/i',
                'message' => Yii::t('app', "Username yang anda masukan salah. Username harus berupa karakter (a - z), 
                    tidak boleh diawali dengan angka dan mengandung spasi .")],

            [['email', 'email2'], 'trim'],
            [['email', 'email2'], 'required'],
            [['email', 'email2'], 'email'],
            [['email', 'email2'], 'string', 'max' => 255],
//            ['email', 'unique',
//                'targetClass' => User::className(), 'message' => 'This email address has already been taken.'],
            ['email2', 'compare', 'compareAttribute' => 'email'],

            [['username', 'email'], 'checkDaftar'],

            [['password', 'password2'], 'required'],
            [['password', 'password2'], 'string', 'min' => 6],
            ['password2', 'compare', 'compareAttribute' => 'password',
                ],

            [['namaLengkap', 'jenisKelamin', 'jenisIdentitas', 'identitas', 'noHP'], 'required'],
            [['namaLengkap'], 'string', 'max' => 200],
//            [['noHP', 'noTlp'], 'number'],
//            [['noHP'], 'match', 'pattern' => '/^\+62-[8][1-9][0-9]-\d{3,}-\d{3,}$/i',
            [['noHP'], 'match', 'pattern' => '/^\+62[8]\d{5,}$/i',
                'message' => 'Format penulisan tidak sesuai'],

//            [['noTlp'], 'match', 'pattern' => '/^\+62-\d{2,}-\d{7,}$/i',
            [['noTlp'], 'match', 'pattern' => '/^\+62[1-9]\d{7,}$/i',
                'message' => 'Format penulisan tidak sesuai'],

            [['idNegara', 'choseNationality', 'identitas', 'verifyCode'], 'required'],

            [['jenisIdentitas', 'idNegara'], 'verifikasiIdentitas'],

            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('pmb', 'Email Address'),
            'email2' => Yii::t('pmb', 'Retype Email Address'),
            'password2' => Yii::t('pmb', 'Retype Password'),
            'namaLengkap' => Yii::t('pmb', 'Full Name'),
            'jenisIdentitas' => Yii::t('pmb', 'Identitas Used'),
            'jenisKelamin' => Yii::t('pmb', 'Gender'),
            'noTlp' => Yii::t('pmb', 'Telephone Number'),
            'noHP' => Yii::t('pmb', 'Mobile Phone'),
            'identitas' => Yii::t('pmb', 'ID Number'),
            'idNegara' => Yii::t('pmb', 'Nationality'),

            'verifyCode' => Yii::t('pmb', 'Verification Code'),

        ];
    }

    public function verifikasiIdentitas($attribute, $param)
    {
        if ($attribute == 'jenisIdentitas' && $this->identitas == null)
            $this->addError('jenisIdentitas', '');
        if ($attribute == 'idNegara' && $this->choseNationality == null)
            $this->addError('idNegara', '');
    }


    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     * @throws NotAcceptableHttpException
     */
    public function signup()
    {
        if ($this->choseNationality == 1)
            $this->idNegara = 1;

        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->accessRole_id = 7; // rule pendaftar
        $user->setPassword($this->password);
        $user->generateAuthKey();

        $person = new Orang();
        $person->nama = $this->namaLengkap;
        $person->jenisKelamin = $this->jenisKelamin;
        $person->negara_id = $this->idNegara;
        $usKtp = Orang::findOne(['KTP' => $this->identitas]);
        if ($this->jenisIdentitas == 1 && $usKtp == null) // jika KTP *1 menyatakan ID KTP
            $person->KTP = $this->identitas;
        $idPerson = $person->save(false) ? $person->id : null;

        $paketId = $this->getPaketPendaftaranID();

        if ($idPerson == !null) {
            $identitas = new Identitas();
            $identitas->jenisIdentitas_id = $this->jenisIdentitas;
            $identitas->identitas = $this->identitas;
            $identitas->orang_id = $idPerson;
            $identitas->save(false);

            $hp = new Kontak();
            $hp->jenisKontak_id = 2;
            $hp->kontak = $this->noHP;
            $hp->orang_id = $idPerson;
            $hp->save(false);

            if ($this->noTlp != null) {
                $tlp = new Kontak();
                $tlp->jenisKontak_id = 3;
                $tlp->kontak = $this->noTlp;
                $tlp->orang_id = $idPerson;
                $tlp->save(false);
            }

            $email = new Kontak();
            $email->jenisKontak_id = 1;
            $email->kontak = $this->email;
            $email->orang_id = $idPerson;
            $email->save(false);

            if ($paketId > 0) {
                $pendaftaran = new Pendaftaran();
                $pendaftaran->orang_id = $idPerson;
                $pendaftaran->paketPendaftaran_id = $paketId;
                $pendaftaran->generateNoPendaftaran();
                $pendaftaran->save(false);
                PinVerifikasi::getPin($pendaftaran->noPendaftaran);
                $this->sendEmail($pendaftaran, $this->email);
            }

            $user->orang_id = $idPerson;

        }


        return $user->save() ? $user : null;
    }

    /**
     * @param Pendaftaran $pendaftaran
     * @return bool
     */
    public function sendEmail($pendaftaran, $email)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'akun-html', 'text' => 'akun-text'],
                ['model' => $pendaftaran, 'username' => $this->username, 'email' => $this->email,])
            ->setFrom([Yii::$app->params['norepEmail'] => Yii::$app->name . ' - IPB '])
            ->setTo($email)
            ->setSubject('Verifikasi Akun ' . Yii::$app->name)
            ->send();
    }

    /**
     * @param Pendaftaran $pendaftaran
     * @return bool
     */
    public function sendEmailWna($pendaftaran, $email)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'akunWna-html', 'text' => 'akunWna-text'],
                ['model' => $pendaftaran, 'username' => $this->username, 'email' => $this->email,])
            ->setFrom([Yii::$app->params['norepEmail'] => Yii::$app->name . ' - IPB '])
            ->setTo($email)
            ->setSubject('Verifikasi Akun ' . Yii::$app->name)
            ->send();
    }
    /**
     * @return int
     * @throws NotAcceptableHttpException
     */
    public function getPaketPendaftaranID()
    {
        CostumDate::tiemZone();
        $get = Yii::$app->request->get();
        $jalurUrl = isset($get['jenismasuk']) ? $get['jenismasuk'] : 1;

        $paketPendaftar = PaketPendaftaran::findOne(['uniqueUrl' => $jalurUrl]);
        $paketId = 0;
        if (!empty($paketPendaftar)) {
            if ($paketPendaftar->active == 0)
                throw new NotAcceptableHttpException("Pendaftaran belum tersedia untuk saat ini, <br> untuk lebih jelasnya  silahkan hubungi 0251 8628448. ");

            if (strtotime($paketPendaftar->dateEnd) < time())
                throw new NotAcceptableHttpException("Pendaftaran telah ditutup,<br> untuk lebih jelasnya  silahkan hubungi 0251 8628448. ");

            $tm = date('D, d M Y', strtotime($paketPendaftar->dateStart));
            if (strtotime($paketPendaftar->dateStart) > time())
                throw new NotAcceptableHttpException("Pendaftaran akan dibuka pada $tm ,<br> untuk lebih jelasnya  silahkan hubungi 0251 8628448. ");

            $paketId = $paketPendaftar->id;
        } else {
            if ($jalurUrl == 'not_available')
                throw new NotAcceptableHttpException("Pendaftaran telah ditutup,<br> untuk lebih jelasnya  silahkan hubungi 0251 8628448. ");

            throw new NotAcceptableHttpException("Terjadi kesalahan pada alamat url");
        }
        return $paketId;
    }

    /**
     * SignupForm constructor.
     * @param array $config
     * @throws NotAcceptableHttpException
     */
    public function __construct(array $config = [])
    {
        $this->getPaketPendaftaranID();
        parent::__construct($config);
    }

    public $_idNegara;

    /**
     * @return int
     */
    public function getIdNegara()
    {
        return $this->_idNegara;
    }

    /**
     * @param int $idNegara
     */
    public function setIdNegara($idNegara)
    {
        $this->_idNegara = $idNegara;
    }

    public $_choseNationality;

    /**
     * @return int
     */
    public function getChoseNationality()
    {
        return $this->_choseNationality;
    }

    /**
     * @param int $choseNationality
     */
    public function setChoseNationality($choseNationality)
    {
        $this->_choseNationality = $choseNationality;
    }

    /**
     * @param $nsty
     * @return string
     */
    public function getJenisId($nsty)
    {
        $cht = [
            1 => [1,3,4],
            2 => [2],
        ];
        $jenisIdentitas = ArrayHelper::map(JenisIdentitas::findAll(isset($cht[$nsty]) ? $cht[$nsty] : [1] ), 'id', 'nama');

        $out = '';
        foreach ($jenisIdentitas as $idx => $jenisIdentita) {
            $out .= "<option value='$idx'>$jenisIdentita</option>";
        }

        return $out;
    }

    public function checkDaftar($attribute, $param)
    {
        $t = Yii::t('yii', '{attribute} "{value}" has already been taken.');
        $t = str_replace('{attribute}', $attribute, $t);
        $t = str_replace('{value}', $this->$attribute, $t);
        if (!$this->cekData($attribute))
            $this->addError($attribute, $t);
    }

    public function cekData($attribute)
    {
        $u = User::findOne([$attribute => $this->$attribute]);

        if ($u == null)
            return true;

        if ((time() - strtotime($u->dateCreate)) > ( 300 * 24 * 60 * 60)
            && date('Y') > date('Y', strtotime($u->dateCreate))) {
            $u->$attribute = $u->$attribute . '-' . date('Y');
            return $u->save();
        }

        return false;
    }

}
