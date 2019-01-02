<?php

$this->title = Yii::t('app', 'Alur Pendaftaran');
$this->params['sidebar'] = 0;
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@app/adminlte/dist/');
?>
<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Prosedur Pendaftaran dan Pembayaran Pendaftaran</h3>
  </div>
  <div class="box-body">
<p><img src="<?= $directoryAsset ?>/img/alurpmbsps.jpg" alt="alursps" border="1" height="700" weigth="500"/></p>
</div>
</div>
