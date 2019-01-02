<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\components\Lang;

$this->title = Lang::id()? $post->title : $post->title_en;
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@app/adminlte/dist/');

$this->params['sidebar'] = 0;

?>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Lang::id()? $post->subtitle : $post->subtitle_en;?></h3>
    </div>
    <div class="box-body">

        <p><strong>
                <?= Lang::t('Sebelum anda melakukan pendaftaran <em>online</em>, baca secara keseluruhan tahapan dan syarat yang harus 
      dipenuhi di bawah ini dengan cermat!', 'Please read the procedures and required documents carefully before registration')?>
                </strong></p>
        <?= Lang::id()? $post->content : $post->content_en;?>
    </div><!-- /.box-body -->
</div><!-- /.box -->