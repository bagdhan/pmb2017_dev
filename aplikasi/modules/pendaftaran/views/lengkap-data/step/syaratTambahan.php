<?php
/**
 * Created by Sublime Text.
 * User: doni
 * Date: 5/2/2017
 * Time: 9:50 
 */

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm; */
/* @var $syaratTambahan \app\modules\pendaftaran\models\lengkapdata\SyaratTambahan */

use app\components\Lang;

$txt = Lang::t('Isi dengan skor {tst} berupa angka tanpa menggunakan titik atau koma.' ,
    '');

$orang = Yii::$app->user->identity->orang != null ? Yii::$app->user->identity->orang : new \app\models\Orang();

$showEn = $orang->negara_id > 1 ? false : true;
?>

<div class="row panel">
	<div class="post clearfix">
		<div class="form-group">
			<div class="col-md-6">
				<?= $form->field($syaratTambahan, 'toefl')->input('text', ['value'=>@$dataSyarat['toefl']['score']])
                    ->hint(str_replace('{tst}', 'TOEFL', $txt)) ?>
			</div>
			<div class="col-md-6 pull-right">
				<?= $form->field($syaratTambahan, 'date_toefl')->input('text', ['value'=>@$dataSyarat['toefl']['date'],'class'=>'form-control dateinput'])?><br/>
				<?= $form->field($syaratTambahan, 'org_toefl')->input('text', ['value'=>@$dataSyarat['toefl']['org']])?>
			</div>

		</div>
	</div>
    <?php if ($showEn) {?>
	<div class="post clearfix">
		<div class="form-group">
			<div class="col-md-6">
				<?= $form->field($syaratTambahan, 'tpa')->input('text', ['value'=>@$dataSyarat['tpa']['score']])
                    ->hint(str_replace('{tst}', 'TPA', $txt)) ?>
			</div>
			<div class="col-md-6 pull-right">
				<?= $form->field($syaratTambahan, 'date_tpa')->input('text', ['value'=>@$dataSyarat['tpa']['date'],'class'=>'form-control dateinput']) ?>
				<?= $form->field($syaratTambahan, 'org_tpa')->input('text', ['value'=>@$dataSyarat['tpa']['org']])?><br/>
			</div>
		</div>
	</div>
    <?php }?>
</div>

