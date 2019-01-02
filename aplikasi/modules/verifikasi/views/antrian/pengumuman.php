<?php 
use app\assets\DataTableAsset;
use app\models\Pleno2;
use app\models\NrpGenerator;
use yii\helpers\Html;
?>

<div class="row">

<br/>
    <div class="col-lg-12">
        <table style="width:100%;" class="table" id="tabeldata">
	        <thead>
		        <tr>
		            <th>No. Antrian</th>
		            <th>No. Pend</th>
		            <th>Nama</th>
		            <th>Strata</th>
		            <th>Fak</th>
		            <th>Program Studi</th>
		           
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
		       	$nopendaftaran = $dataRow['noPendaftaran'];
		       	$nrp = $gen->getNrp($nopendaftaran);
        		$kode = substr($nrp,1);
        		$mayor1 = $dataRow['mayor1']; 
        		$mayor2 = $dataRow['mayor2']; 
		       	$program = $dataRow['strata']; 
		       	$ps = $dataRow['prodi1'].' ('.$dataRow['kprodi1'].')';
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
		        $hsl = Pleno2::findOne([
		                    'noPendaftaran' => $nopendaftaran,
		                    'psPilihan' => $mayor2,
		                ]);
		        if (!empty ($hsl)) {
		                $idhasil=$hsl->idHasilPleno;
		            }

		        $ps = $dataRow['prodi2'].' ('.$dataRow['kprodi2'].')';
		        $kodefak = substr($dataRow['mayor2'], 0,1);
		        }
		        $kodebayar = $kd[$kodefak].$kode;

		        
	               
		           echo "<tr>";
	               echo "<td name='".$nopendaftaran."'>".$dataRow['noantrian']."</td>";
	               echo "<td name='".$nopendaftaran."'>".$nopendaftaran."</td>";
	               echo "<td name='".$nopendaftaran."'>".$dataRow['nama']."</td>";
	               echo "<td name='".$nopendaftaran."'>".$program."</td>";
	               echo "<td name='".$nopendaftaran."'>".$kodefak."</td>";
	               echo "<td name='".$nopendaftaran."'>".$ps."</td></tr>";
	               $i++;
		       }
	        ?>
	        </tbody>
	        <!-- <tfoot>
		        <tr>
		            <th>No</th>
		            <th>No Pendaftaran</th>
		            <th>Nama</th>
		            <th>Strata</th>
		            <th>Fak</th>
		            <th>Program Studi</th>
		        
		        </tr>
	        </tfoot> -->
        </table>
        
    </div>

</div>