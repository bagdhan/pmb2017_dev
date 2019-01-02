<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "orang".
 *
 * @property integer $id
 * @property string $nama
 * @property string $KTP
 * @property string $tempatLahir
 * @property string $tanggalLahir
 * @property integer $jenisKelamin
 * @property integer $statusPerkawinan_id
 * @property string $NPWP
 * @property string $waktuBuat
 * @property string $waktuUbah
 * @property integer $negara_id
 *
 * @property Gelar[] $gelars
 * @property Identitas[] $identitas
 * @property Keamanaan[] $keamanaans
 * @property Kontak[] $kontaks
 * @property Negara $negara
 * @property StatusPerkawinan $statusPerkawinan
 * @property OrangHasAlamat[] $orangHasAlamats
 * @property Alamat[] $alamats
 * @property Pekerjaan[] $pekerjaans
 * @property Pendaftaran[] $pendaftarans
 * @property Pendidikan[] $pendidikans
 * @property User[] $users
 * @property User $user
 */
class Orang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama', 'jenisKelamin', 'waktuBuat', 'waktuUbah'], 'required'],
            [['tanggalLahir', 'waktuBuat', 'waktuUbah'], 'safe'],
            [['jenisKelamin', 'statusPerkawinan_id', 'agama_id', 'negara_id'], 'integer'],
            [['nama'], 'string', 'max' => 200],
            [['KTP'], 'string', 'max' => 64],
            [['tempatLahir'], 'string', 'max' => 50],
            [['NPWP'], 'string', 'max' => 45],
            [['KTP'], 'unique'],
            [['negara_id'], 'exist', 'skipOnError' => true, 'targetClass' => Negara::className(), 'targetAttribute' => ['negara_id' => 'id']],
            [['statusPerkawinan_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusPerkawinan::className(), 'targetAttribute' => ['statusPerkawinan_id' => 'id']],
			[['agama_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agama::className(), 'targetAttribute' => ['agama_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nama' => Yii::t('app', 'Nama Lengkap'),
            'KTP' => Yii::t('app', 'Ktp'),
            'tempatLahir' => Yii::t('app', 'Tempat Lahir'),
            'tanggalLahir' => Yii::t('app', 'Tanggal Lahir'),
            'jenisKelamin' => Yii::t('app', 'Jenis Kelamin'),
            'statusPerkawinan_id' => Yii::t('app', 'Status Perkawinan ID'),
			'agama_id' => Yii::t('app', 'Agama ID'),
            'NPWP' => Yii::t('app', 'Npwp'),
            'waktuBuat' => Yii::t('app', 'Waktu Buat'),
            'waktuUbah' => Yii::t('app', 'Waktu Ubah'),
            'negara_id' => Yii::t('app', 'Negara ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGelars()
    {
        return $this->hasMany(Gelar::className(), ['orang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdentitas()
    {
        return $this->hasMany(Identitas::className(), ['orang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeamanaans()
    {
        return $this->hasMany(Keamanaan::className(), ['orang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKontaks()
    {
        return $this->hasMany(Kontak::className(), ['orang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNegara()
    {
        return $this->hasOne(Negara::className(), ['id' => 'negara_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusPerkawinan()
    {
        return $this->hasOne(StatusPerkawinan::className(), ['id' => 'statusPerkawinan_id']);
    }
	
	public function getAgama()
    {
        return $this->hasOne(Agama::className(), ['id' => 'agama_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrangHasAlamats()
    {
        return $this->hasMany(OrangHasAlamat::className(), ['orang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlamats()
    {
        return $this->hasMany(Alamat::className(), ['id' => 'alamat_id'])->viaTable('orang_has_alamat', ['orang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaans()
    {
        return $this->hasMany(Pekerjaan::className(), ['orang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftarans()
    {
        return $this->hasMany(Pendaftaran::className(), ['orang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendidikans()
    {
        return $this->hasMany(Pendidikan::className(), ['orang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['orang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['orang_id' => 'id']);
    }
}
