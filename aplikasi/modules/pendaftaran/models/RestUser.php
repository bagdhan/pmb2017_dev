<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 3/13/2017
 * Time: 1:06 PM
 */

namespace app\modules\pendaftaran\models;

use app\models\Orang;
use app\usermanager\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;

/**
 * Class RestUser
 * @package app\modules\pendaftaran\models
 *
 *
 */
class RestUser extends Model
{
    // kebutuhan User
    public $username;
    public $email;
    public $password;
    public $password2;

    // kebutuhan Pendaftran
    public $noPendaftaran;

    /**
     * @var User
     */
    public $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique',
                'targetClass' => User::className(),
                'message' => 'Username telah digunakan.'],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['username', 'match', 'pattern' => '/^[a-z]\w*$/i',
                'message' => Yii::t('app', "Username yang anda masukan salah. Username harus berupa karakter (a - z), 
                    tidak boleh diawali dengan angka dan mengandung spasi .")],

            [['email'], 'trim'],
            [['email'], 'required'],
            [['email'], 'email'],


            [['password', 'password2'], 'required'],
            [['password', 'password2'], 'string', 'min' => 6],
            ['password2', 'compare', 'compareAttribute' => 'password',
                'message' => Yii::t('app', "Password dan Konfirmasi Password tidak sama")],

            ['noPendaftaran', 'required'],
            ['noPendaftaran', 'verifikasiNoPendaftaran'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Alamat Email'),
            'password2' => Yii::t('app', 'Konfirmasi Password'),

        ];
    }


    public function verifikasiNoPendaftaran($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $listNopen = ListResetUser::getListNopen($this->email);
            if (!(in_array($this->$attribute, $listNopen))) {
                $this->addError($attribute, 'No Pendaftaran tidak valid.');
            }
        }
    }

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        $this->_user = self::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }
        $this->email = $this->_user->email;
        parent::__construct($config);
    }

    /**
     * @param $token
     * @return null|User
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return User::findOne([
            'passwordResetToken' => $token,
            'status' => 10,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = 172800;
        return $timestamp + $expire >= time();
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetAkun()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = $this->_user;
        $user->username = $this->username;
        $user->email = $this->email;

        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        self::setNopen();

        return $user->save(false);
    }

    public function setNopen()
    {
        $idOrang = $this->_user->orang_id;
        $dataDaftar = Pendaftaran::findAll(['orang_id' => $idOrang]);
        foreach ($dataDaftar as $item) {
            $item->orang_id = 1;
            $item->save(false);
        }
        $pendaftaran = Pendaftaran::findOne($this->noPendaftaran);
        $pendaftaran->orang_id = $idOrang;
        $pendaftaran->save(false);
    }
}