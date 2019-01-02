<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/19/2017
 * Time: 12:04 PM
 */

namespace app\modules\pendaftaran\models;

use app\models\CostumDate;
use Yii;
use app\modelsDB\PinVerifikasi;
use yii\helpers\ArrayHelper;

/**
 * @property mixed inputpin
 */
class FormVerifikasiPIN extends PinVerifikasi
{
    public $inputpin;

    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inputpin', 'noPendaftaran'], 'required'],
            [['noPendaftaran'], 'exist', 'skipOnError' => true,
                'targetClass' => Pendaftaran::className(), 'targetAttribute' => ['noPendaftaran' => 'noPendaftaran']],
            ['inputpin', 'validatePin'],
            ['noPendaftaran', 'noPendaftaranUser'],

            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inputpin' => Yii::t('app', 'PIN'),
            'verifyCode' => 'Verification Code',

        ];
    }

    /**
     * Validates PIN Verifikasi.
     * This method serves as the inline validation for verifikasi.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePin($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $PIN = self::findOne(['noPendaftaran' => $this->noPendaftaran]);
            if (!empty($PIN) && !($PIN->pin == $this->inputpin)) {
                $this->addError($attribute, Yii::t('app', 'PIN tidak valid'));
            }
        }
    }

    /**
     * Validasi No Pendaftaran dengan User.
     * This method serves as the inline validation for verifikasi.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function noPendaftaranUser($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $listNopen = Pendaftaran::find()
                ->where(['orang_id' => Yii::$app->user->identity->orang_id])
                ->select('noPendaftaran')
                ->asArray()->all();
            if (!in_array($this->noPendaftaran, ArrayHelper::getColumn($listNopen, 'noPendaftaran'))) {
                $this->addError($attribute, Yii::t('app',
                    "No Pendaftaran ($this->noPendaftaran) yang anda Masukan tidak sesuai dengan No pendaftaran Anda" ));
            }
        }
    }

    /**
     * @return bool
     */
    public function verifikasi()
    {
        CostumDate::tiemZone();
        $this->noPendaftaran = str_replace(' ', '', $this->noPendaftaran);
        $this->inputpin = str_replace(' ', '', $this->inputpin);
        if (!$this->validate()) {
            return false;
        }

        $PIN = self::findOne(['noPendaftaran' => $this->noPendaftaran]);
        $PIN->dateVerifikasi = date('Y-m-d H:i:s');
        $PIN->dateUpdate = date('Y-m-d H:i:s');

        $PIN->status = 1;

        $PIN->ipVerifikasi = Yii::$app->request->userIP;

        return $PIN->save(false);
    }

}