<?php

namespace app\controllers;

use app\components\Lang;
use app\models\NrpGenerator;
use app\models\Pendaftaran;
use app\models\Broadcast;
use app\models\LogFile;
use app\models\Orang;
use app\modules\pendaftaran\models\ListResetUser;
use app\modules\pendaftaran\models\RestUser;
use app\usermanager\models\UserLoginLog;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\modelsDB\PinVerifikasi;
use app\modelsDB\PinReference;
use app\modelsDB\Sidang;
use app\models\PaketPendaftaran;
use app\models\Fakultas;
use app\models\Post;
use mpdf;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SignupForm;

use yii\web\NotAcceptableHttpException;



class SiteController extends \app\components\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'register'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['register', ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function upfistarray($input){
        $pca = explode(' ', $input);
        $g='';
        foreach ($pca as $p)
            $g[]= ucfirst(strtolower($p));
        return join(' ',$g);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $tahun = isset($_GET['tahun'])? $_GET['tahun'] : date('Y'); 
        $model = PinVerifikasi::find();
        $post = Post::find()->where(['page'=>Yii::$app->controller->action->id])->all();

        $jum_akun = count(PinReference::find()->where(['use'=>1])->all());
        $jum_verif = count(Pendaftaran::find()->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);}])->andWhere(['=','year(waktuBuat)',$tahun])->all());
        $jum_lengkap = count(Pendaftaran::find()->andWhere(['setujuSyarat'=>1])->andWhere(['=','year(waktuBuat)',$tahun])->all());
        // Yii::$app->session->setFlash('info', "<strong>Hasil seleksi Tahap 1 dan 2 dapat dilihat pada laman pmbpasca.ipb.ac.id 
        // dengan melakukan login menggunakan akun Saudara.</strong>");
        // Yii::$app->session->setFlash('info', "<strong>Surat Panggilan Verifikasi bagi Calon Mahasiswa yang telah dinyatakan 
        // diterima dapat diunduh pada laman pmbpasca.ipb.ac.id dengan menggunakan akun Saudara. Informasi selengkapnya dapat
        // dilihat pada <a href='http://pasca.ipb.ac.id/index.php?option=com_content&task=view&id=866&Itemid=1' target='_blank'>
        // <ins>Informasi Verifikasi</ins></a> dan <a href='http://pasca.ipb.ac.id/index.php?option=com_content&task=view&id=869&Itemid=1' 
        // target='_blank'><ins>Jadwal Verifikasi</ins></a></strong>");
        // Yii::$app->session->setFlash('info', "<strong>Pengumuman hasil seleksi Penerimaan Mahasiswa Baru penyelenggaraan khusus 
        // Program Studi (KOM, ITP, MAN, MPD, MPI, MPM, PSL, PTP, PWL)akan dikirimkan via email, dan surat resmi dapat diambil
        // di Sekretariat Program Studi masing-masing</strong>");
        return $this->render('index',['akun'=>$jum_akun,'verif'=>$jum_verif,'lengkap'=>$jum_lengkap,'post'=>$post]);
    }

     /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
		$get_nomer = $_GET['NomorPendaftaran'];
		$model->username = $get_nomer;
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
			
            UserLoginLog::setLogin(Yii::$app->user->id);
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        UserLoginLog::setLogout(Yii::$app->user->id);
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionCetak($id,$verifikasi=null)
    {
        $st = [ 4 => "BIASA",
                5 => "PERCOBAAN",
                6 => "Ditolak",
                0 => '',
            ];

        $kd = ['A' => "01",
                 'B' => "02",
                 'C' => "03",
                 'D' => '04',
                 'E' => "05",
                 'F' => "06",
                 'G' => "07",
                 'H' => '08',
                 'I' => "09",
                 'P' => '16',
            ];

        $email = Yii::$app->user->identity->email;
        $idOrang = Yii::$app->user->identity->orang_id;
        $wni = (isset($idOrang) && Orang::findOne($idOrang)->negara_id == 1)? true : false;
        $listNopen = ListResetUser::getListNopen($email);

        if (!(in_array($id, $listNopen)) || ($verifikasi!=null && $wni == false)) {
            throw new NotAcceptableHttpException('No pendaftaran tidak valid.');
        }

        $gen = new NrpGenerator();
        $nrp = $gen->getNrp($id);
        $kode = substr($nrp,1);
        $pendaftaran = Pendaftaran::findOne($id);
        $menunda = $pendaftaran->cek_menunda();

        if(!isset($pendaftaran->terimaSurat)) {
            $pendaftaran->terimaSurat = 1;
            $pendaftaran->save(false);
        }

        $tingkat = ["2"=> "Magister Sains",
                  "3"=> "Doktor"];

        $pilihan=$inisial=$mayor=$kodefakultas=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=$kodefakultas[1]=$kodefakultas[2]=''; $i = 1; 
              $model = $pendaftaran;
              foreach($model['pendaftaranHasProgramStudis'] as $value){
                  $pilihan[$i] = $value->programStudi->kode;
                  $mayor[$i] = $value->programStudi->nama;
                  $inisial[$i] = $value->programStudi->inisial;
                  $strata = $value->programStudi->strata;
                  $kodefakultas[$i] = substr($value->programStudi->kode,0,1);
                  $i++;
              }

              foreach($pendaftaran->orang->alamats as $alamat){
                $jalan = isset($alamat->jalan)? $alamat->jalan : '';
                $rt = isset($alamat->rt)? $alamat->rt : '-';
                $rw = isset($alamat->rw)? $alamat->rw : '-';
                $keldes = isset($alamat->desaKelurahanKode->namaID)? $alamat->desaKelurahanKode->namaID : '';
                $kec = isset($alamat->desaKelurahanKode->kecamatanKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->namaID : '';
                $kab = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID : '';
                $prov = isset($alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID)? $alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID : '';
                $pos = isset($alamat->kodePos)? $alamat->kodePos : '';
              }

              $list_kontak = $pendaftaran->orang->kontaks;
              foreach($list_kontak as $value){
                  $jeniskontak = $value->jenisKontak_id;
                  if($jeniskontak == 2){ // hp
                      $hp = isset($value->kontak)? $value->kontak : '';
                  }
              }

              $idhasil=[]; $idhasil[1]=$idhasil[2]=0;
              $i = 1;
              $program = $pendaftaran->pendaftaranHasProgramStudis;
              foreach($program as $prog){
                  $proses_sidang = $prog->prosesSidangs;
                  foreach($proses_sidang as $pr => $proses){
                    $sidang = Sidang::findOne($proses->sidang_id);
                    if($sidang->jenisSidang_id == 2){
                       $idhasil[$i] = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : 0;
                       
                    }
                  }
                  $i++;
              }
       
        
        if($pos!='')
            $pos = ', '.$pos.'.';
        else
            $pos = '.';

        $kelurahan = $this->upfistarray($keldes.', '.$kec.', '.$kab.', '.$prov.$pos);

        $nopendaftaran = $pendaftaran->noPendaftaran;
        $tahap = $pendaftaran->paketPendaftaran->title;
        if ($tahap!= 'Tahap 1' && $tahap !='Tahap 2') {
            throw new NotAcceptableHttpException('Surat Panggilan Verifikasi bisa menghubungi panitia PMB Sekolah Pascasarjana IPB.');
        }
        $tahun = $pendaftaran->paketPendaftaran->tahun;
        $program = $pendaftaran->pendaftaranHasProgramStudis[0];
        $pleno = isset($program->prosesSidangs[0]->sidang->id)? $program->prosesSidangs[0]->sidang->paketPendaftaran->title : '';
        $surat = isset($program->prosesSidangs[0]->sidang->id)? $program->prosesSidangs[0]->sidang->paketPendaftaran->surats : '';
        if($menunda == 1){
          $paket = PaketPendaftaran::find()->where(['tahun'=>date('Y'),'title'=>'Tahap 1','active'=>1])->one();
          $surat = isset($paket)? $paket->surats : '';
        }
        if($surat == ''){
          $surat = isset($pendaftaran->paketPendaftaran->id)? $pendaftaran->paketPendaftaran->surats : '';
        }
        $tanggal=$nomor=$perihal=[]; $tanggal[0]=$nomor[0]=$perihal[0]=$tanggal[1]=$nomor[1]=$perihal[1]=$tanggal[2]=$nomor[2]=$perihal[2]='';
        if($surat != ''){
          foreach($surat as $sur){
            $statusPenerimaan           = $sur->statusPenerimaan;
            $tanggal[$statusPenerimaan] = $sur->tanggal;
            $nomor[$statusPenerimaan]   = $sur->nomor;
            $perihal[$statusPenerimaan] = $sur->perihal;
          }
        }

       
        if($pleno==''){
        $pleno = isset($pendaftaran->verifikasiPMB)? $pendaftaran->verifikasiPMB : 0;
        }

        $nama = $this->upfistarray($pendaftaran->orang->nama);
        $jk = $pendaftaran->orang->jenisKelamin;
        $alamat = $this->upfistarray($jalan.' Rt. '.$rt.'/'.$rw);
        $hp = 'HP '.$hp;
        $kodefak = $kodefakultas[1];
        $hsl = $idhasil[1];

        $program = $tingkat[$strata];
        $ps = $mayor[1].' ('.$inisial[1].')';
        $status = $st[$hsl];

     if($idhasil[1] == 6 && $pilihan[2] != '' && $pilihan[1] != $pilihan[2]){
        // $idhasil=0;
        $hsl = $idhasil[2];

        $ps = $mayor[2].' ('.$inisial[2].')';
        $status = $st[$hsl];
        $kodefak = $kodefakultas[2];
        }

        $kodebayar = $kd[$kodefak].$kode;
        $namafak = Fakultas::findOne(['kode'=>$kodefak])->nama;
    
    $mpdf=new mpdf('','A4',12,'Norasi',15,15,5,10,9,9,'P');

    if($status == 'BIASA' || $status == 'PERCOBAAN' ){
        if(isset($verifikasi)){
            $mpdf=new mpdf('',[210,330],12,'Norasi',20,20,5,5,9,9,'P');
            $mpdf->WriteHTML($this->renderPartial('_penerimaan2',array('nopendaftaran'=>$nopendaftaran, 'nama'=>$nama,'strata'=>$program,'ps'=>$ps,'status'=>$status, 'fakultas'=>$namafak, 'kodefak'=>$kodefak,'tanggal'=>$tanggal[2],'nomor'=>$nomor[2],'perihal'=>$perihal[2],'tahun'=>$tahun,'jenisKelamin'=>$jk,'alamat'=>$alamat,'kodebayar'=>$kodebayar,'hp'=>$hp, 'kelurahan'=>$kelurahan)));
            $mpdf->SetHTMLFooter('<div style="text-align: right; font-family:"Times New Roman", Georgia, Serif; font-weight: bold;font-size: 7pt; "><barcode code="'.$nopendaftaran.'" size="0.6" type="QR" error="M" class="barcode" /></div>');
        }elseif($tahap == 'Tahap 1' || $pleno = 'Tahap 1'){
            $mpdf->WriteHTML($this->renderPartial('_penerimaan',array('nopendaftaran'=>$nopendaftaran, 'nama'=>$nama,'strata'=>$program,'ps'=>$ps,'status'=>$status,'tanggal'=>$tanggal[1],'nomor'=>$nomor[1],'perihal'=>$perihal[1],'tahun'=>$tahun,'jenisKelamin'=>$jk,'alamat'=>$alamat,'hp'=>$hp, 'kelurahan'=>$kelurahan)));
            $mpdf->SetHTMLFooter('<div style="text-align: left; font-family: Arial, Helvetica,
                sans-serif; font-weight: bold;font-size: 7pt; "><barcode code="'.$nopendaftaran.'" size="0.6" type="QR" error="M" class="barcode" /></div>');
            // $mpdf->AddPage();
            // $mpdf->WriteHTML($this->renderPartial('_lampiran',array('nopendaftaran'=>$nopendaftaran, 'nama'=>$nama,'strata'=>$program,'ps'=>$ps,'status'=>$status)));
        }


    }elseif($status == 'Ditolak' || $pleno == 2 || ($status == 'Ditolak' && $pleno == 'Tahap 2')){

        if($tahap == 'Tahap 2' || $pleno == 'Tahap 2'){

//            $mpdf->WriteHTML($this->renderPartial('_penolakan2',array('nopendaftaran'=>$nopendaftaran, 'nama'=>$nama, 'alamat'=>$alamat)));
            $mpdf->WriteHTML($this->renderPartial('_penolakan',array('nopendaftaran'=>$nopendaftaran, 'nama'=>$nama,'tanggal'=>$tanggal[0],'nomor'=>$nomor[0],'perihal'=>$perihal[0],'tahun'=>$tahun,'jenisKelamin'=>$jk,'alamat'=>$alamat,'hp'=>$hp, 'kelurahan'=>$kelurahan)));


        }else{
            $mpdf->WriteHTML($this->renderPartial('_penolakan',array('nopendaftaran'=>$nopendaftaran, 'nama'=>$nama,'tanggal'=>$tanggal[0],'nomor'=>$nomor[0],'perihal'=>$perihal[0],'tahun'=>$tahun,'jenisKelamin'=>$jk,'alamat'=>$alamat,'hp'=>$hp, 'kelurahan'=>$kelurahan)));
        }
    }else{
        throw new NotAcceptableHttpException("Akses anda ditolak, <br> untuk lebih jelasnya  silahkan hubungi 0251 8628448. ");
    }    

        // $mpdf->AddPage();
        // $mpdf->WriteHTML($this->renderPartial('_reportCover',array('data'=>$data,'document'=>$getDocument, 'tbl_mayor'=>$tbl_mayor)));
        if($verifikasi!=null){
          $mpdf->Output('surat_verifikasi_'.$nopendaftaran.'.pdf', 'D');  
        }else{
          $mpdf->Output('surat_'.$nopendaftaran.'.pdf', 'D');
        }

    }

    public function actionKartu($id)
    {
        $st = [ 4 => "BIASA",
                5 => "PERCOBAAN",
                6 => "Ditolak",
                0 => '',
            ];

        $kd = ['A' => "01",
                 'B' => "02",
                 'C' => "03",
                 'D' => '04',
                 'E' => "05",
                 'F' => "06",
                 'G' => "07",
                 'H' => '08',
                 'I' => "09",
                 'P' => '16',
            ];

        $email = Yii::$app->user->identity->email;
        $listNopen = ListResetUser::getListNopen($email);

        if (!(in_array($id, $listNopen))) {
            throw new NotAcceptableHttpException('No pendaftaran tidak valid.');
        }

        $gen = new NrpGenerator();
        $nrp = $gen->getNrp($id);
        $kode = substr($nrp,1);
        $pendaftaran = Pendaftaran::findOne($id);
        $menunda = $pendaftaran->cek_menunda();

        if(!isset($pendaftaran->terimaSurat)) {
            $pendaftaran->terimaSurat = 1;
            $pendaftaran->save(false);
        }

        $tingkat = ["2"=> "Magister Sains",
                  "3"=> "Doktor"];

        $pilihan=$inisial=$mayor=$kodefakultas=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=$kodefakultas[1]=$kodefakultas[2]=''; $i = 1; 
              $model = $pendaftaran;
              foreach($model['pendaftaranHasProgramStudis'] as $value){
                  $pilihan[$i] = $value->programStudi->kode;
                  $mayor[$i] = $value->programStudi->nama;
                  $inisial[$i] = $value->programStudi->inisial;
                  $strata = $value->programStudi->strata;
                  $kodefakultas[$i] = substr($value->programStudi->kode,0,1);
                  $i++;
              }

              $idhasil=[]; $idhasil[1]=$idhasil[2]=0;
              $i = 1;
              $program = $pendaftaran->pendaftaranHasProgramStudis;
              foreach($program as $prog){
                  $proses_sidang = $prog->prosesSidangs;
                  foreach($proses_sidang as $pr => $proses){
                    $sidang = Sidang::findOne($proses->sidang_id);
                    if($sidang->jenisSidang_id == 2){
                       $idhasil[$i] = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : 0;
                       
                    }
                  }
                  $i++;
              }

        $nopendaftaran = $pendaftaran->noPendaftaran;
        $tahap = $pendaftaran->paketPendaftaran->title;

        $tahun = $pendaftaran->paketPendaftaran->tahun;
        $program = $pendaftaran->pendaftaranHasProgramStudis[0];
        $pleno = isset($program->prosesSidangs[0]->sidang->id)? $program->prosesSidangs[0]->sidang->paketPendaftaran->title : '';
        
        $nama = $this->upfistarray($pendaftaran->orang->nama);
        $kodefak = $kodefakultas[1];
        $hsl = $idhasil[1];

        $program = $tingkat[$strata];
        $ps = $mayor[1].' ('.$inisial[1].')';
        $status = $st[$hsl];

     if($idhasil[1] == 6 && $pilihan[2] != '' && $pilihan[1] != $pilihan[2]){
        $hsl = $idhasil[2];

        $ps = $mayor[2].' ('.$inisial[2].')';
        $status = $st[$hsl];
        $kodefak = $kodefakultas[2];
        }

        $kodebayar = $kd[$kodefak].$kode;
        
        // $dirTemp = Yii::getAlias('@app').'/arsip/Pengumuman Hasil Seleksi PMB.pdf';
    
    $mpdf=new mpdf('','A6-L',12,'Norasi',10,10,15,15,9,9,'L');

    if($status == 'BIASA' || $status == 'PERCOBAAN' ){
        
            $mpdf->WriteHTML($this->renderPartial('_kartu',array('nopendaftaran'=>$nopendaftaran, 'nama'=>$nama, 'kodebayar'=>$kodebayar,'strata'=>$program,'ps'=>$ps,'kodefak'=>$kodefak)));
            // $mpdf->SetHTMLFooter('<div style="text-align: right; font-family: Arial, Helvetica,
            //     sans-serif; font-weight: bold;font-size: 40pt; padding: 0.2em; "><span>'.$kodefak.' </span>  <barcode code="'.$nopendaftaran.'" size="0.7" type="QR" error="M" class="barcode" /></div>');
        
    }  

        $mpdf->Output('kartu_'.$nopendaftaran.'.pdf', 'D');

    }

    /**
     * Signs user up.
     *
     * @param $jenismasuk
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionRegister($jenismasuk = null)
    {

        if ($jenismasuk == null) {
            $paket = PaketPendaftaran::findActive('reguler');

            return $this->redirect(['/site/register', 'jenismasuk' => $paket]);
        }

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    UserLoginLog::setLogin(Yii::$app->user->id);
                    Yii::$app->session->setFlash('success', Lang::t("Terimakasih. Anda telah selesai melakukan 
                    pendaftaran akun di sistem kami. Selanjutnya silahkan cek email anda(termasuk di folder spam), 
                    untuk mendapatkan nomor pendaftaran dan informasi proses pendaftaran selanjutnya.",
                        'Thank you very much. You have finished registering the account on our system. 
                        Please check your email to get the registration number and further information about the admission process. 
                        (Please also check the “spam” folder if you can\'t find it in “Inbox” folder of your email).'));
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetUsernamePassword($token)
    {
        try {
            $model = new RestUser($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetAkun()) {
            if (Yii::$app->getUser()->login($model->_user)) {
                UserLoginLog::setLogin(Yii::$app->user->id);
                Yii::$app->session->setFlash('success', "Terimakasih. Akun anda telah di verifikasi kembali 
                        silahkan lakukan verifikasi PIN.");
                return $this->goHome();
            }
        }

        return $this->render('resetAkun', [
            'model' => $model,
        ]);
    }

    public function actionListAkun()
    {
        ini_set('max_execution_time', 3000);
        $log = '';
        $model = new ListResetUser();
        $listEmail = [];
        foreach ($model->getSimpleData() as $simpleDatum) {
            $lisNopen = ListResetUser::getListNopen($simpleDatum['email']);
            if (!isset($listEmail[$simpleDatum['email']])) {
                $orang = Orang::findOne($simpleDatum['id']);
                $user = $model->setUser($simpleDatum['id'], $simpleDatum['email']);
                echo "send email to $simpleDatum[email] : ";
                if ($user != null)
                    echo ($model->sendEmail($orang, $user, join(',',$lisNopen)) ? "berhasil" : "gagal");
                else
                    echo 'tidak ada user';
                echo '<br>';
                $listEmail[$simpleDatum['email']] = 1;
            } else
                $listEmail[$simpleDatum['email']] ++;


            $log .= $simpleDatum['id'] . '-' . $simpleDatum['nama'] . '-' . $simpleDatum['email'] . '--'. join(',',$lisNopen). ";";
        }
        print_r($model->getSimpleData());
        $log .= "hasil sen email ke (" . join(',', $listEmail) . ")\n";
        LogFile::write('log',$log);
        die;

    }

    public function actionResetAkun($id)
    {
        ini_set('max_execution_time', 3000);
        $log = '';
        $model = new ListResetUser();
        $listEmail = [];
        foreach ($model->getOneData($id) as $simpleDatum) {
            $lisNopen = ListResetUser::getListNopen($simpleDatum['email']);
            if (!isset($listEmail[$simpleDatum['email']])) {
                $orang = Orang::findOne($simpleDatum['id']);
                $user = $model->setUser($simpleDatum['id'], $simpleDatum['email']);
                echo "send email to $simpleDatum[email] : ";
                if ($user != null)
                    echo ($model->sendEmail($orang, $user, join(',',$lisNopen)) ? "berhasil" : "gagal");
                else
                    echo 'tidak ada user';
                echo '<br>';
                $listEmail[$simpleDatum['email']] = 1;
            } else
                $listEmail[$simpleDatum['email']] ++;


            $log .= $simpleDatum['id'] . '-' . $simpleDatum['nama'] . '-' . $simpleDatum['email'] . '--'. join(',',$lisNopen). ";";
        }
        print_r($model->getOneData($id));
        $log .= "hasil sen email ke (" . join(',', $listEmail) . ")\n";
        LogFile::write('log',$log);
        die;

    }

//    /**
//     * Verifikasi Akun.
//     *
//     * @param string $token
//     * @return mixed
//     * @throws BadRequestHttpException
//     */
//    public function actionVerifikasiAkun($token)
//    {
//        try {
//            $model = new VerifikasiAkunForm($token);
//        } catch (InvalidParamException $e) {
//            throw new BadRequestHttpException($e->getMessage());
//        }
//
//        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            Yii::$app->session->setFlash('success', 'Akun Berhasil diverifikasi.');
//            if ($user = $model->signup()) {
//                if (Yii::$app->getUser()->login($user)) {
//                    return $this->goHome();
//                }
//            }
//        }
//
//        return $this->render('verifikasiAkun', [
//            'model' => $model,
//        ]);
//    }

    public function actionTest()
    {
        /** @var Pendaftaran[] $pendaftaran */
        $pendaftaran = Pendaftaran::find()->where(['paketPendaftaran_id' => 1])->all();
        $i=1;
        foreach ($pendaftaran as $item) {
            if ($item->pinVerifikasi->status == 1 && $item->setujuSyarat != 1) {
                echo $item->orang->users[0]->email . " $i<br>";
                $i++;
            }
        }
    }

    public function actionBroadcast()
    {
        $model = new Broadcast();
        if ($model->load(Yii::$app->request->post()) && $model->submit()) {

            return $this->refresh();
        }
        return $this->render('broadcast', [
            'model' => $model,
        ]);
    }

}
