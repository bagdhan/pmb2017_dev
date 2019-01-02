<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 5/8/2017
 * Time: 11:16 PM
 */

namespace app\modules\admin\models;

use Yii;
use app\modelsDB\User;
use app\modelsDB\Fakultas;
use app\modelsDB\UserHasFakultas;
use app\modelsDB\UserHasDepartemen;
use app\modelsDB\UserHasProgramStudi;

/**
 * Class UserFdp
 * @property mixed listAssign
 * @package app\modules\admin\models
 */
class UserFdp extends User
{
    public $fakultas;
    public $departemen;
    public $prodi;

    public function rules()
    {
        return [
            [['username', 'email', 'accessRole_id'], 'required'],
            [['status', 'accessRole_id', 'orang_id'], 'integer'],
            [['dateCreate', 'dateUpdate','fakultas','departemen','prodi'], 'safe'],
            [['username', 'email'], 'string', 'max' => 100],
            [['passwordHash', 'authKey', 'accessToken', 'passwordResetToken'], 'string', 'max' => 200],
            [['username'], 'unique'],
            [['authKey'], 'unique'],

        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'passwordHash' => Yii::t('app', 'Password'),
            'fakultas' => Yii::t('app', 'Fakultas'),
            'departemen' => Yii::t('app', 'Departemen'),
            'prodi' => Yii::t('app', 'Prodi'),
        ]);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->dateCreate = date('Y-m-d H:i:s');
                $this->dateUpdate = date('Y-m-d H:i:s');
                $this->setPassword($this->passwordHash);
                $this->generateAuthKey();
                $this->status = 10;
            } else {
                $this->dateUpdate = date('Y-m-d H:i:s');
                $this->setPassword($this->passwordHash);                
            }           

            return true;
        }
        return false;

    }

    public function afterSave($insert, $changedAttributes)
    {
       parent::afterSave($insert, $changedAttributes); 
                    
                switch ($this->accessRole_id) {
                    case 4 :
                    $model = UserHasFakultas::find()->where(['user_id'=>$this->id])->one();
                    if(isset($this->fakultas)){
                        if(empty($model) || (!empty($model) && $this->fakultas != '' && $model->fakultas_id != $this->fakultas)){
                            $model = new UserHasFakultas();
                            $model->user_id = $this->id;
                            $model->fakultas_id = $this->fakultas;
                            $model->save(false);
                        }
                    }
                    $level = $model->fakultas->nama;
                    $jabatan = 'Dekan / Wakil Dekan';
                    break;
                    case 5 :
                    $model = UserHasDepartemen::find()->where(['user_id'=>$this->id])->one();
                    if(isset($this->departemen)){
                        if(empty($model) || (!empty($model) && $this->departemen != '' && $model->departemen_id != $this->departemen)){
                            $model = new UserHasDepartemen();
                            $model->user_id = $this->id;
                            $model->departemen_id = $this->departemen;
                            $model->save(false);
                        }
                    }
                    $level = $model->departemen->nama;
                    $jabatan = 'Ketua Departemen';
                    break;
                    case 6 :
                    $model = UserHasProgramStudi::find()->where(['user_id'=>$this->id])->one();
                    if(isset($this->prodi)){
                        if(empty($model) || (!empty($model) && $this->prodi != '' && $model->programStudi_id != $this->prodi)){
                            $model = new UserHasProgramStudi();
                            $model->user_id = $this->id;
                            $model->programStudi_id = $this->prodi;
                            $model->save(false);
                        }
                    }
                    $level = $model->programStudi->nama;
                    $jabatan = 'Ketua Program Studi'; 
                    break;
                };
            
                
        $this->sendEmail($this->email,$level,$jabatan);     
        return true;
    }

    /**
     * @return bool
     */
    public function sendEmail($email,$level,$jabatan)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'akun-fdp-html', 'text' => 'akun-fdp-text'],
                ['level' => $level, 'username' => $this->username, 'email' => $this->email,'jabatan'=>$jabatan]
            )
            ->setFrom([Yii::$app->params['norepEmail'] => Yii::$app->name . ' - IPB '])
            ->setTo($email)
            ->setSubject('Verifikasi Akun Seleksi '. Yii::$app->name)
            ->send();

    }

    public function generateAuthKey()
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        if($password != ''){
            $this->passwordHash = Yii::$app->security->generatePasswordHash($password);
        }else{
            $model = User::findOne($this->id);
            $this->passwordHash = !empty($model)? $model->passwordHash : null;
        }

    }

    public function getListAssign()
    {
        $out = '<table class="table">';
        $out .= "<tr><th>Kode</th><th>Nama</th><th>action</th></tr>";
        switch ($this->accessRole_id) {
            case 4 :
                foreach ($this->userHasFakultas as $userHasFakulta) {
                    $k = $userHasFakulta->fakultas->kode;
                    $n = $userHasFakulta->fakultas->nama;
                    $out .= "<tr><td>$k</td><td>$n</td><td></td></tr>";
                }
                break;
            case 5 :
                foreach ($this->userHasDepartemens as $userHasDepartemen) {
                    $k = $userHasDepartemen->departemen->kode;
                    $n = $userHasDepartemen->departemen->nama;
                    $out .= "<tr><td>$k</td><td>$n</td><td></td></tr>";
                }
                break;
            case 6 :
                foreach ($this->userHasProgramStudis as $userHasPs) {
                    $k = $userHasPs->programStudi->kode;
                    $n = $userHasPs->programStudi->nama;
                    $out .= "<tr><td>$k</td><td>$n</td><td></td></tr>";
                }
                break;
        }
        $out .= '</table>';
        return $out;
    }
}