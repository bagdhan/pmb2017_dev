<?php 
use app\assets\DataTableAsset;
use app\models\Pleno2;
use app\modules\verifikasi\models\Verifikasi;
use yii\helpers\Json;
use app\models\NrpGenerator;
use yii\helpers\Html;
?>

<div class="row">
<div class="col-lg-3">

		<div class="form-group">


			<?= Html::a('Export Rekap Log Verifikasi ',['#'],['class'=>'btn btn-primary',
				'data-fak'=>'', 
				'data-action'=>'absen',
				'data-url'=>\yii\helpers\Url::to([
					"/verifikasi/rekap/export"]),
					]) ?>
				</div>
				
				<div id="hasil"></div>
			</div>

<br/>
    <div class="col-lg-12">
        <table style="width:100%;" class="table" id="tabeldata">
	        <thead>
		        <tr>
		            <th>No.Antrian</th>
		            <th>Tahap</th>
		            <th>No. Pend</th>
		            <th>Nama</th>
		            <th>Prodi</th>
		            <th>M0</th>
		            <th>M1</th>
		            <th>M2</th>
		            <th>M3</th>
		            <th>M4</th>
		            <th>M5</th>
		            <th>MK</th>
		           
		        </tr>
		      
	        </thead>
	        <tbody>
	        <?php
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

	        	$gen = new NrpGenerator();
	            $i=1;
		       foreach ($data as $r => $dataRow){  
		       	$nopendaftaran = $dataRow['nopendaftaran'];
		       	$nrp = $gen->getNrp($nopendaftaran);
        		$kode = substr($nrp,1);
        		$mayor1 = $dataRow['mayor1']; 
        		$mayor2 = $dataRow['mayor2']; 
		       	$program = $dataRow['strata']; 
		       	$ps = $dataRow['kprodi1'];
		       	$kodemayor = $mayor1;
		       	$kodefak = substr($dataRow['mayor1'], 0,1);

		       	 // pilihan 1 
		        $idhasil=0;
		        $hsl = Pleno2::findOne([
		                    'noPendaftaran' => $nopendaftaran,
		                    'psPilihan' => $mayor1,
		                ]);
		        if (!empty ($hsl)) {
		                $idhasil=$hsl->idHasilPleno;
		            }

		     if($idhasil == 3 && $mayor2 != '' && $mayor1 != $mayor2){
		        $idhasil=0;
		        $meja = array();
		        $hsl = Pleno2::findOne([
		                    'noPendaftaran' => $nopendaftaran,
		                    'psPilihan' => $mayor2,
		                ]);
		        if (!empty ($hsl)) {
		                $idhasil=$hsl->idHasilPleno;
		            }

		        $ps = $dataRow['kprodi2'];
		        $kodemayor = $mayor2;
		        $kodefak = substr($dataRow['mayor2'], 0,1);
		        }
		     
		        $daftarVerifikasi = Verifikasi::findOne(['noPendaftaran' => $nopendaftaran]);
		        $meja = array();
		        $noantrian = '';
				   $tahap = '0';
		        $meja[0] = '';$meja[1] = '';$meja[2] = '';$meja[3] = '';$meja[4] = '';$meja[5] = '';$meja[6] = '';
		        if($daftarVerifikasi){

	              	
		        	$noantrian = $daftarVerifikasi->noantrian;
		        	$tahap = $daftarVerifikasi->tahap;
	                $log = Json::decode($daftarVerifikasi->log);
	                $jum = sizeof($log);
	                
	                $posisi = 0;
	                for($a = 1; $a <= $jum ;$a++) {
	                	$posisi = substr($log[$a]['posisi'],1);

	                	if($posisi == '0'){
	                		$meja[0] = $log[$a]['dateMasuk'];
		                }else if($posisi == '1'){
		                	$meja[1] = $log[$a]['dateMasuk'];
		                }else if($posisi == '2'){
		                	$meja[2] = $log[$a]['dateMasuk'];
		                }else if($posisi == '3'){
		                	$meja[3] = $log[$a]['dateMasuk'];
		                }else if($posisi == '4'){
		                	$meja[4] = $log[$a]['dateMasuk'];
		                }else if($posisi == '5'){
		                	$meja[5] = $log[$a]['dateMasuk'];
		                }else if($posisi == 'K'){
		                	$meja[6] = $log[$a]['dateMasuk'];
		                }
		            }
            	}

		           echo "<tr>";
		           echo "<td name='".$nopendaftaran."'>".$noantrian."</td>";
		           echo "<td name='".$nopendaftaran."'>".$tahap."</td>";
	               echo "<td name='".$nopendaftaran."'>".$nopendaftaran."</td>";
	               echo "<td name='".$nopendaftaran."'>".$dataRow['nama']."</td>";
	               echo "<td name='".$nopendaftaran."' title='".$ps."'>".$kodemayor."</td>";
	               echo "<td name='".$nopendaftaran."'>".$meja[0]."</td>";
	               echo "<td name='".$nopendaftaran."'>".$meja[1]."</td>";
	               echo "<td name='".$nopendaftaran."'>".$meja[2]."</td>";
	               echo "<td name='".$nopendaftaran."'>".$meja[3]."</td>";
	               echo "<td name='".$nopendaftaran."'>".$meja[4]."</td>";
	               echo "<td name='".$nopendaftaran."'>".$meja[5]."</td>";
	               echo "<td name='".$nopendaftaran."'>".$meja[6]."</td></tr>";
	               $i++;
	           	
		       }
	        ?>
	        </tbody>
	        <tfoot>
		        <tr>
		            <th>No. Antrian</th>
		            <th>Tahap</th>
		            <th>No Pend</th>
		            <th>Nama</th>
		            <th>Prodi</th>
		            <th>No</th>
		            <th>No</th>
		            <th>No</th>
		            <th>No</th>
		            <th>No</th>
		            <th>No</th>
		            <th>No</th>
		        
		        </tr>
	        </tfoot>
        </table>
        
    </div>

</div>