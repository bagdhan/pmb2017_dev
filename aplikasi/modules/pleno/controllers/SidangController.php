<?php

namespace app\modules\pleno\controllers;

use Yii;
use app\modelsDB\UserHasFakultas;
use app\modelsDB\UserHasDepartemen;
use app\modelsDB\UserHasProgramStudi;
use app\models\Excel;
use app\modules\pleno\models\Sidang;
use app\modules\pleno\models\SidangSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SidangController implements the CRUD actions for Sidang model.
 */
class SidangController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Sidang models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SidangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSidangid()
    {
    
        $data_sidang = Sidang::find()->orderBy('paketPendaftaran_id ASC')->all();
        foreach($data_sidang as $value){
            $sidang[$value->paketPendaftaran->uniqueUrl][$value->jenisSidang_id] = $value->id;
        }

        $tahun        = $_POST['sm']['tahun'];
        $tahap        = $_POST['sm']['tahap'];
        $jenisSidang  = $_POST['sm']['jenisSidang'];
        
        $uniqueUrl = $tahap.'_'.$tahun;
        $sidang_id = $sidang[$uniqueUrl][$jenisSidang];
        if(isset($sidang_id)){
            return $sidang_id;
        }else{
            return false;
        }
            
        
    }

    public function actionCreatebahanpleno($fak='',$tahap='')
    {
        $tahun = substr($tahap, 7);
        $periode = substr($tahap, 5, 1);
        if ($fak!=''){
            $fak = explode(',',$fak);
            $allfak = join("_",$fak).'_';
        }
        else{
            $allfak='';
            $fak = [];
        }
        $Exl = new Excel;
        $dirTemp = Yii::getAlias('@app').'/arsip/pleno/';
        if($periode == '1'){
            $filename = 'Bahan_Pleno_I_S2_dan_S3_Tahap_I_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }elseif($periode == '2'){
            $filename = 'Bahan_Pleno_I_S2_dan_S3_Tahap_II_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }elseif($periode == "s"){
            $filename = 'Bahan_Pleno_I_S2_dan_S3_Kelas_Khusus_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }else{
            $filename = 'Bahan_Pleno_I_S2_dan_S3_Kelas_by_Research_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }
        
        if (!file_exists($dirTemp)) {
            mkdir($dirTemp, 0777, true);
        }
        $objExl = $Exl->getExcelBahanPleno($fak,$tahun,$periode,$tahap);           
        $Exl->save($objExl,$dirTemp.$filename);
        $hasil =[
            'link'=>Excel::downloadUrl($dirTemp.$filename),
        ];
        if (Yii::$app->request->isAjax) 
            echo json_encode($hasil);
        else
            return json_encode($hasil);
    }

    public function actionCreatebahanseleksi($fak='',$tahap='')
    {
        $tahun = substr($tahap, 7);
        $periode = substr($tahap, 5, 1);
        if ($fak!=''){
            $fak = explode(',',$fak);
            $allfak = join("_",$fak).'_';
        }
        else{
            $allfak='';
            $fak = [];
        }
        $Exl = new Excel;
        $dirTemp = Yii::getAlias('@app').'/arsip/pleno/';
        if($periode == '1'){
            $filename = 'Bahan_Seleksi_S2_dan_S3_Tahap_I_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }elseif($periode == '2'){
            $filename = 'Bahan_Seleksi_S2_dan_S3_Tahap_II_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }elseif($periode == "s"){
            $filename = 'Bahan_Seleksi_I_S2_dan_S3_Kelas_Khusus_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }else{
            $filename = 'Bahan_Seleksi_I_S2_dan_S3_Kelas_by_Research_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }
        
        if (!file_exists($dirTemp)) {
            mkdir($dirTemp, 0777, true);
        }
        $objExl = $Exl->getExcelBahanSeleksi($fak,$tahun,$periode,$tahap);           
        $Exl->save($objExl,$dirTemp.$filename);
        $hasil =[
            'link'=>Excel::downloadUrl($dirTemp.$filename),
        ];
        if (Yii::$app->request->isAjax) 
            echo json_encode($hasil);
        else
            return json_encode($hasil);
    }

    public function actionCreatebahanpleno2($fak='',$tahap='')
    {
        $tahun = substr($tahap, 7);
        $periode = substr($tahap, 5, 1);
        if ($fak!=''){
            $fak = explode(',',$fak);
            $allfak = join("_",$fak).'_';
        }
        else{
            $allfak='';
            $fak = [];
        }
        $Exl = new Excel;
        $dirTemp = Yii::getAlias('@app').'/arsip/pleno/';
        if($periode == '1'){
            $filename = 'Bahan_Pleno_II_S2_dan_S3_Tahap_I_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }elseif($periode == '2'){
            $filename = 'Bahan_Pleno_II_S2_dan_S3_Tahap_II_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }elseif($periode == "s"){
            $filename = 'Bahan_Pleno_II_S2_dan_S3_Kelas_Khusus_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }else{
            $filename = 'Bahan_Pleno_II_S2_dan_S3_Kelas_by_Research_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }
        
        if (!file_exists($dirTemp)) {
            mkdir($dirTemp, 0777, true);
        }
        $objExl = $Exl->getExcelBahanPleno2($fak,$tahun,$periode,$tahap);           
        $Exl->save($objExl,$dirTemp.$filename);
        $hasil =[
            'link'=>Excel::downloadUrl($dirTemp.$filename),
        ];
        if (Yii::$app->request->isAjax) 
            echo json_encode($hasil);
        else
            return json_encode($hasil);
    }

    public function actionCreateberitaacarapleno($fak='', $jab='', $tahap='')
    {
        $tahun = substr($tahap, 7);
        $periode = substr($tahap, 5, 1);
        if ($fak!=''){
            $fak = explode(',',$fak);
            $allfak = join("_",$fak).'_';
        }
        else{
            $allfak='';
            $fak = [];
        }
        $Exl = new Excel;
        $dirTemp = Yii::getAlias('@app').'/arsip/pleno/';
        if($periode == '1'){
            $filename = 'Berita_acara_Pleno_I_S2_dan_S3_Tahap_I_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }elseif($periode == '2'){
            $filename = 'Berita_acara_Pleno_I_S2_dan_S3_Tahap_II_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }elseif($periode == "s"){
            $filename = 'Berita_acara_Pleno_I_S2_dan_S3_Kelas_Khusus_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }else{
            $filename = 'Berita_acara_Pleno_I_S2_dan_S3_Kelas_by_Research_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }
        if (!file_exists($dirTemp)) {
            mkdir($dirTemp, 0777, true);
        }
        $objExl = $Exl->getExcelBAPleno($fak,$jab,$tahun,$periode,$tahap);           
        $Exl->save($objExl,$dirTemp.$filename);
        $hasil =[
            'link'=>Excel::downloadUrl($dirTemp.$filename),
        ];
        if (Yii::$app->request->isAjax) 
            echo json_encode($hasil);
        else
            return json_encode($hasil);
    }

    public function actionCreateberitaacarapleno2($fak='', $jab='', $tahap='')
    {
        $tahun = substr($tahap, 7);
        $periode = substr($tahap, 5, 1);
        if ($fak!=''){
            $fak = explode(',',$fak);
            $allfak = join("_",$fak).'_';
        }
        else{
            $allfak='';
            $fak = [];
        }
        $Exl = new Excel;
        $dirTemp = Yii::getAlias('@app').'/arsip/pleno/';
        if($periode == '1'){
            $filename = 'Berita_acara_Pleno_II_S2_dan_S3_Tahap_I_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }elseif($periode == '2'){
            $filename = 'Berita_acara_Pleno_II_S2_dan_S3_Tahap_II_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }elseif($periode == "s"){
            $filename = 'Berita_acara_Pleno_II_S2_dan_S3_Kelas_Khusus_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }else{
            $filename = 'Berita_acara_Pleno_II_S2_dan_S3_Kelas_by_Research_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }
        if (!file_exists($dirTemp)) {
            mkdir($dirTemp, 0777, true);
        }
        $objExl = $Exl->getExcelBAPleno2($fak,$jab,$tahun,$periode,$tahap);           
        $Exl->save($objExl,$dirTemp.$filename);
        $hasil =[
            'link'=>Excel::downloadUrl($dirTemp.$filename),
        ];
        if (Yii::$app->request->isAjax) 
            echo json_encode($hasil);
        else
            return json_encode($hasil);
    }

    public function actionCreateberitaacaraseleksi($fak='', $jab='', $tahap='')
    {
                $accessRule = Yii::$app->user->identity->accessRole_id;
              if($accessRule == 4){
                  $ch = UserHasFakultas::findOne(['user_id' => Yii::$app->user->id]);
                  $fak = $ch->fakultas->kode;
              }elseif($accessRule == 5){
                  $ch = UserHasDepartemen::findOne(['user_id' => Yii::$app->user->id]);
                  $fak = $ch->departemen->fakultas->kode;
              }elseif($accessRule == 6){
                  $ch = UserHasProgramStudi::findOne(['user_id' => Yii::$app->user->id]);
                  $fak = $ch->programStudi->departemen->fakultas->kode;  
              }

        $tahun = substr($tahap, 7);
        $periode = substr($tahap, 5, 1);
        if ($fak!=''){
            $fak = explode(',',$fak);
            $allfak = join("_",$fak).'_';
        }
        else{
            $allfak='';
            $fak = [];
        }
        $Exl = new Excel;
        $dirTemp = Yii::getAlias('@app').'/arsip/pleno/';
        if($periode == '1'){
            $filename = 'Berita_acara_Seleksi_S2_dan_S3_Tahap_I_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }elseif($periode == '2'){
            $filename = 'Berita_acara_Seleksi_S2_dan_S3_Tahap_II_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }elseif($periode == "s"){
            $filename = 'Berita_acara_Seleksi_S2_dan_S3_Kelas_Khusus_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }else{
            $filename = 'Berita_acara_Seleksi_S2_dan_S3_Kelas_by_Research_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }
        if (!file_exists($dirTemp)) {
            mkdir($dirTemp, 0777, true);
        }
        $objExl = $Exl->getExcelBASeleksi($fak,$jab,$tahun,$periode,$tahap);           
        $Exl->save($objExl,$dirTemp.$filename);
        $hasil =[
            'link'=>Excel::downloadUrl($dirTemp.$filename),
        ];
        if (Yii::$app->request->isAjax) 
            echo json_encode($hasil);
        else
            return json_encode($hasil);
    }

    public function actionCreatesurathasil($fak='', $jenis='',$tahap='')
    {
        $tahun = substr($tahap, 7);
        $periode = substr($tahap, 5, 1);
        if ($fak!=''){
            $fak = explode(',',$fak);
            $allfak = join("_",$fak).'_';
        }
        else{
            $allfak='';
            $fak = [];
        }

        if($jenis == 1){
            $surat = 'Panggilan';
        }else{
            $surat = 'Penolakan';
        }
        // $word = new \app\models\Word;
        $Exl = new Excel;
        $dirTemp = Yii::getAlias('@app').'/arsip/surat/';
        if($periode == '1'){
            $filename = 'Bahan_surat_'.$surat.'_Tahap_I_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }elseif($periode == '2'){
            $filename = 'Bahan_surat_'.$surat.'_Tahap_II_'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }elseif($periode == "s"){
            $filename = 'Bahan_surat_'.$surat.'_Kelas_Khusus'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }else{
            $filename = 'Bahan_surat_'.$surat.'_Kelas_by_Research'.$tahun.'_'.$allfak.date('YmdHis').'.xlsx';
        }
        
        if (!file_exists($dirTemp)) {
            mkdir($dirTemp, 0777, true);
        }
        $objExl = $Exl->getExcelObjBahanSuratT1($fak,$jenis,$tahap,$periode);           
        $Exl->save($objExl,$dirTemp.$filename);
        $hasil =[
            // 'link'=>$word->createSurathasil($dirTemp.$filename,$fak,$jenis),
            'link'=>Excel::downloadUrl($dirTemp.$filename),
        ];
        if (Yii::$app->request->isAjax) 
            echo json_encode($hasil);
        else
            return json_encode($hasil);
    }

    public function actionCreatesurathasilall($tahap='')
    {
        $tahun = substr($tahap, 7);
        $periode = substr($tahap, 5, 1);
        // $tahun = '2016';
        
            $surat = 'Panggilan_&_Verifikasi';
      
        // $word = new \app\models\Word;
        $Exl = new Excel;
        $dirTemp = Yii::getAlias('@app').'/arsip/surat/';

            $filename = 'Bahan_surat_'.$surat.'_'.$tahun.date('YmdHis').'.xlsx';
        
        
        if (!file_exists($dirTemp)) {
            mkdir($dirTemp, 0777, true);
        }
        $objExl = $Exl->getExcelObjBahanSuratAll($tahap,$tahun,$periode);           
        $Exl->save($objExl,$dirTemp.$filename);
        $hasil =[
            // 'link'=>$word->createSurathasil($dirTemp.$filename,$fak,$jenis),
            'link'=>Excel::downloadUrl($dirTemp.$filename),
        ];
        if (Yii::$app->request->isAjax) 
            echo json_encode($hasil);
        else
            return json_encode($hasil);
    }

    /**
     * Displays a single Sidang model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single Sidang model.
     * @param integer $id
     * @return mixed
     */
    public function actionHasil($id)
    {
        $url = $this->findModel($id)->paketPendaftaran->uniqueUrl;
        $tahap = $this->findModel($id)->paketPendaftaran->title;
        // $tahun = substr($tahap, 0, 4);
        $periode = substr($tahap, 4, 5);
        

                $accessRule = Yii::$app->user->identity->accessRole_id;
                switch ($accessRule) {
                    case 4 :
                        $ch = UserHasFakultas::findOne(['user_id' => Yii::$app->user->id]);
                        $filter = 'substring(kode,1,1)';
                        $kode = $ch->fakultas->kode;
                        $sql = [$filter=>$kode];
                        break;
                    case 5 :
                        $ch = UserHasDepartemen::findOne(['user_id' => Yii::$app->user->id]);
                        $filter = 'substring(kode,1,2)';
                        $kode = $ch->departemen->kode;
                        $sql = [$filter=>$kode];
                        break;
                    case 6 :
                        $user = UserHasProgramStudi::find()->where(['user_id' => Yii::$app->user->id])->all();

                        $ch = UserHasProgramStudi::findOne(['user_id' => Yii::$app->user->id]);
                        $filter = 'substring(kode,1,2)';
                        $filter2= 'substring(kode,4,1)'; 
                        $kode = substr($ch->programStudi->kode,0,2);
                        $kode2 = substr($ch->programStudi->kode,3,1);
                        if(count($user)>1){
                            $sql = [$filter=>$kode,$filter2=>$kode2];
                        }else{
                            $filter = 'kode';
                            $kode = $ch->programStudi->kode; 
                            $sql = [$filter=>$kode];
                        }
                        break;
                    default :
                        $sql = '';
                }
                $jenisSidang = $this->findModel($id)->jenisSidang_id;
                if($jenisSidang != 2){ 
                    
                    $dataTable = Sidang::getDataTable($sql, $accessRule, $url);
                    // $dataChart = $this->getDataChart1($dataTable);

                    return $this->render('hasil', [
                        // 'kodefak' => isset($kodefak) ? $kodefak : '',
                        // 'kodedep' => isset($kodedep) ? $kodedep : '',
                        // 'kodeprodi' => isset($kodeprdi) ? $kodeprdi : '',
                        // 'dataChart' => $dataChart,
                        'dataTable' => $dataTable,
                        'tahap' => $tahap,
                        'id'=> $id,
                        'model' => $this->findModel($id),
                    ]);
                }else{
                    $dataTable = Sidang::getDataTable1($sql, $accessRule, $url);
                    $dataTable2 = Sidang::getDataTable2($sql, $accessRule, $url, 2);
                    $dataTable2s3 = Sidang::getDataTable2($sql, $accessRule, $url, 3);
                    // $dataChart = $this->getDataChart1($dataTable);

                    return $this->render('hasil', [
                        // 'kodefak' => isset($kodefak) ? $kodefak : '',
                        // 'kodedep' => isset($kodedep) ? $kodedep : '',
                        // 'kodeprodi' => isset($kodeprdi) ? $kodeprdi : '',
                        // 'dataChart' => $dataChart,
                        'dataTable' => $dataTable,
                        'dataTable2' => $dataTable2,
                        'dataTable2s3' => $dataTable2s3,
                        'tahap' => $tahap,
                        'id'=> $id,
                        'model' => $this->findModel($id),
                    ]);
                }

        // return $this->render('hasil', [
        //     'model' => $this->findModel($id),
        // ]);
    }

    /**
     * Creates a new Sidang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sidang();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Sidang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Sidang model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sidang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sidang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sidang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
