<?php 
use app\adminlte\assets\plugin\DataTableAsset;
use app\models\DoAktif;
use yii\helpers\Html;
DataTableAsset::register($this);
?>
<br/>
<div class="row">
	<div class="col-lg-12">
		<h4> Keterangan: </h4>
	</div>
	<div class="col-lg-12">
			<div class="col-md-3"><span class="glyphicon glyphicon-alert" style="color:red;"></span> : Belum Pilih Mayor dan IPK Kurang </div>
			<div class="col-md-3"><span class="glyphicon glyphicon-trash" style="color:red;"></span> : Indikasi DO &nbsp;&nbsp;&nbsp;
			<span class="glyphicon glyphicon-asterisk" style="color:red;"></span> : Belum Pilih Mayor </div>
			<div class="col-md-6">
								   <span class="glyphicon glyphicon-book" style="color:red;"></span> 
				&nbsp;&nbsp;&nbsp; <span class="glyphicon glyphicon-check" style="color:red;"></span> : IPK dan TPA Kurang 
				&nbsp;&nbsp;&nbsp; <span class="glyphicon glyphicon-book" style="color:red;"></span> 
				&nbsp;&nbsp;&nbsp; <span class="glyphicon glyphicon-check" style="color:green;"></span> : IPK Kurang dan TPA Cukup  
				&nbsp;&nbsp;&nbsp; <span class="glyphicon glyphicon-check" style="color:green;"></span> : Lengkap
			</div>


		
	<br/>
	<br/>			
	</div>

    <div class="col-lg-12">
    <div class="table-responsive">
        <table style="width:100%;" class="table" id="tabeldata">
	        <thead>
		        <tr>
		            <th>No</th>
		            <th>No. Pend</th>
		            <th>Nama</th>
		            <th>Strata</th>
		           <!--  <th>Data Lengkap</th> -->
		            <th>Fak</th>
		            <th>PS 1</th>
		            <th>DD</th>
		            <th>Berkas</th>
		            <th>Pleno 1</th>
		            <th>Tahap</th>
		            <th>Status</th>
		            <th>Formulir A</th>
		        
		        </tr>
	        </thead>
	        <tbody>
	        <?php
	            $i=1;
		       foreach ($data as $d){  
		       		//data lengkap
		       	    if($d['setujuSyarat']) $setuju = 'Lengkap'; else $setuju = 'Belum';
		       	    if($d->paketPendaftaran->id){
		       	    	$periode = substr($d->paketPendaftaran->title,5); 
		       		}

		       	    //pleno tahap
		       	    if($d['verifikasiPMB']==0) $pleno = 'Belum Pilih'; elseif($d['verifikasiPMB'] ==1 && @$d->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title == 'Tahap 1') $pleno = 'Tahap 1'; elseif($d['verifikasiPMB'] ==1 && @$d->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title == 'Tahap 2') $pleno = 'Tahap 2'; elseif($d['verifikasiPMB'] ==1 && @$d->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title == 'Kelas Khusus') $pleno = 'Kelas Khusus'; elseif($d['verifikasiPMB'] ==1 && @$d->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title == 'Kelas by Research') $pleno = 'Kelas by Research'; else $pleno = 'Tidak Memenuhi';
		       	    // berkas lengkap

		       	    $cek_berkas = $d->verifberkas();
		       	    if($cek_berkas) $berkas = '<a style="visibility:hidden;">1</a>Lengkap'; else $berkas = '<a style="visibility:hidden;">0</a>Belum Lengkap';
		       	    $flag_do = DoAktif::getDataDoAktif($d->orang->nama, $d->orang->tempatLahir, $d->orang->tanggalLahir, 0); 
		       	    $flag_aktif = DoAktif::getDataDoAktif($d->orang->nama, $d->orang->tempatLahir, $d->orang->tanggalLahir, 1);
		       	 
		       	    $countDo = count($flag_do);
		       	    $countAktif = count($flag_aktif); 
		       	    if($countDo >= 1 || $countAktif >= 1 ) {
	                    $span='<span class="glyphicon glyphicon-trash" style="color:red;"> <a style="visibility:hidden;">5</a></span>'; 
	                }else{
	                	$span='';
	                }
	               
	                $strata = "S2";
	                $strata2 = "2";
	                $cek = $d->prodike(1); 
	                $prodi1 = null;
	                $kodefak = '';
	                if($cek){
	                $cek = $d->prodike(1)->programStudi; 
	                $strata = "S".$cek->strata;
	                $strata2 = $cek->strata;
	                $prodi1 = $cek->inisial;
	                $kodefak = substr($cek->kode, 0, 1);
	            	}
	                $ipksebelumnya = $d->ipksebelumnya($strata2)['ipk'];

	                if((!isset($prodi1) && $strata == 'S2' && $ipksebelumnya < '2.5') || (!isset($prodi1) && $strata == 'S3' && $ipksebelumnya < '3.25')){
	                    $ipk  = false;
	 					if($span ==''){
	 						$value = '<a style="visibility:hidden;">1</a>';
	 					}else{
	 						$value='';
	 					}
	                    $span.='<span class="glyphicon glyphicon-alert" style="color:red;"> '.$value.'</span>';
	                }elseif((!isset($prodi1) && $strata == 'S2' && $ipksebelumnya >= '2.5') || (!isset($prodi1) && $strata == 'S3' && $ipksebelumnya >= '3.25')){
	                    $ipk  = true;
	   					if($span ==''){
	 						$value = '<a style="visibility:hidden;">2</a>';
	 					}else{
	 						$value='';
	 					}
	                    $span.='<span class="glyphicon glyphicon-asterisk" style="color:red;">'.$value.'</span>';
	                }elseif((isset($prodi1) && $strata == 'S2' && $ipksebelumnya >= '2.5') || (isset($prodi1) && $strata == 'S3' && $ipksebelumnya >= '3.25')){
	                    $ipk  = true;
	                    if($span ==''){
	 						$value = '<a style="visibility:hidden;">4</a>';
	 					}else{
	 						$value='';
	 					}
	                    $span.='<span class="glyphicon glyphicon-check" style="color:green;"> '.$value.'</span>';
	                }elseif((isset($prodi1) && $strata == 'S2' && $ipksebelumnya < '2.5') || (isset($prodi1) && $strata == 'S3' && $ipksebelumnya < '3.25')){
	                    $ipk  = false;
	                    if($span ==''){
	 						$value = '<a style="visibility:hidden;">3</a>';
	 					}else{
	 						$value='';
	 					}
	                    $span.='<span class="glyphicon glyphicon-book" style="color:red;">'.$value.'</span>';
	                }

	                $tpa_score = $d->tpa_score();

	                if(($ipk == false && $strata == 'S2' && $tpa_score >= '450') || ($ipk == false && $strata == 'S3' && $tpa_score >= '475')){
	                    $span.='<span class="glyphicon glyphicon-check" style="color:green;"></span>';
	                }elseif(($ipk == false && $strata == 'S2' && $tpa_score < '450') || ($ipk == false && $strata == 'S3' && $tpa_score < '475')){
	                    $span.='<span class="glyphicon glyphicon-check" style="color:red;"></span>';
	                }
	                
	               if($d['setujuSyarat']){
	               		$disabled = ''; 
	               		$value = '<span class="glyphicon glyphicon-print" ><a style="visibility:hidden;">1</a></span>'; 
	               	}else {
	               		$disabled = 'disabled';
	               		$value = '<span class="glyphicon glyphicon-print" ><a style="visibility:hidden;">0</a></span>'; 	
	               	}
	               	
	               		$dd = 0; // belum di analisis
	               	
		           echo "<tr><td>$i</td>";
	               echo "<td name='".$d['noPendaftaran']."'>".$d['noPendaftaran']."</td>";
	               echo "<td name='".$d['noPendaftaran']."'>".upfistarray($d->orang->nama)."</td>";
	               echo "<td name='".$d['noPendaftaran']."'>".$strata."</td>";
	               echo "<td name='".$d['noPendaftaran']."'>".$kodefak."</td>";
	               echo "<td name='".$d['noPendaftaran']."'>".$prodi1."</td>";
	               echo "<td name='".$d['noPendaftaran']."'>".$dd."</td>";
	               echo "<td name='".$d['noPendaftaran']."'>".$berkas."</td>";
	               echo "<td name='".$d['noPendaftaran']."'>".$pleno."</td>";
	               echo "<td name='".$d['noPendaftaran']."'>".$periode."</td>";
	               echo "<td name='".$d['noPendaftaran']."' align='center'>$span</td>";
	               echo "<td>";
	               echo "<button name='".$d['noPendaftaran']."' class='btn btn-sm btn-success' onclick='cetak_forma(this.name);' ".$disabled.">".$value."Cetak</button>";
	               echo "</td></tr>";
	               $i++;
		       }
	        ?>
	        </tbody>
	        <tfoot>
		        <tr>
		            <th>No</th>
		            <th>No Pendaftaran</th>
		            <th>Nama</th>
		            <th>Strata</th>
		            <!-- <th>Data Lengkap</th> -->
		            <th>Fak</th>
		            <th>PS 1</th>
		            <th>DD</th>
		            <th>Berkas</th>
		            <th>Pleno 1</th>
		            <th>Tahap</th>
		            <th>Status</th>
		            <th>Formulir A</th>
		        
		        </tr>
	        </tfoot>
        </table>
      </div>
    </div>
<br/>
<div class="col-lg-3">
	<div class="form-group">
		<label>Pilih Fakultas</label>
		<select id="selectfak" multiple class="form-control">
			<option value="A">FAPERTA</option>
			<option value="B">FKH</option>
			<option value="C">FPIK</option>
			<option value="D">FAPET</option>
			<option value="E">FAHUTAN</option>
			<option value="F">FATETA</option>
			<option value="G">FMIPA</option>
			<option value="H">FEM</option>
			<option value="I">FEMA</option>
			<option value="P">SPS</option>
			<option value="">Pilih Semua</option>
			<option value="Belum">Belum Pilih</option>
		</select>
	</div>
	<div class="form-group">
		<label>Pilih Tahap Penerimaan</label>
		<select id="selecttahap" class="form-control">
			<option value="1">Periode 1</option>
			<option value="2">Periode 2</option>
			<option value="3">Kelas Khusus</option>
			<option value="4">Kelas by Research</option>
			<option value="5">Menunda</option>
		</select>
	</div>
	<input type="text" id="tahun" value="<?= isset($_GET['tahun'])? $_GET['tahun'] : date('Y');?>" style="display: none;">
	<?= Html::a('Export Backup Data ',['#'],['class'=>'btn btn-primary',
		'data-fak'=>'', 
		'data-action'=>'backup',
		'data-url'=>\yii\helpers\Url::to([
			"/admin/verifikasi/createbackup"]),
			]) ?>
			<br/>
			<div id="hasil"></div>
		</div>
</div>