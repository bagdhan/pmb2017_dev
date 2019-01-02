<?php

$this->params['sidebar'] = 0;
use app\components\Lang;
$this->title = Lang::t('Kontak Kami', 'Contact');
?>

<div class="site-contact">

	<div class="box box-default">

		<div class="box-body">
		
			<div class="row">

				<div class="col-lg-7">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.6958546465353!2d106.72396851392298!3d-6.560023465951185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c4b28b340eef%3A0x4b59fecf4a942321!2sSekolah+Pascasarjana+IPB+Graduate+School!5e0!3m2!1sid!2sid!4v1486972542874" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
				</div>
				<div class="col-lg-5">
					<h3><strong><?= Lang::t('Panitia Penerimaan Mahasiswa Baru','Student Admissions Committee')?><br/> 
						<?= Lang::t('Sekolah Pascasarjana IPB','Graduate School of Bogor Agricultural University')?></strong></h3>
					</div>
					<div class="col-lg-5 text-muted">
						<h4> <i class="ion ion-home"></i> <?= Lang::t('Alamat','Address')?></h4>
						<p><?= Lang::t('Jl. Raya Darmaga, Gedung Sekolah Pascasarjana, Lantai 3 Ruang 300, Kampus IPB Darmaga Bogor 16680 - Jawa Barat, Indonesia.',
                                'Jl. Raya Darmaga, Gedung Sekolah Pascasarjana, Lantai 3 Ruang 300, Kampus IPB Darmaga Bogor 16680 - West Java, Indonesia.')?></p>
					</div>
					<div class="col-lg-5 text-muted">
						<h4><i class="fa fa-envelope-o"></i> Email</h4>
						<p> pmbpasca@apps.ipb.ac.id</p>
					</div>
					<div class="col-lg-5 text-muted">
					<h4><i class="fa fa-phone"></i> <?= Lang::t('Telepon','Phone')?></h4>
						<p>+62-251-8628448/8423855</p>
					</div>
					<div class="col-lg-5 text-muted">
						<h4><i class="fa fa-globe"></i><?= Lang::t('Situs Web','Website')?> </h4>
						<p><a href="http://pasca.ipb.ac.id/" target="_blank"> pasca.ipb.ac.id</a></p>
					</div>
				</div>
		</div><!-- /.box-body -->
	</div><!-- /.box -->

</div>



