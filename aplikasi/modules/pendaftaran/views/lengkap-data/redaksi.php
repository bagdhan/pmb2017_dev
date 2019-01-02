<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/9/2017
 * Time: 11:32 AM
 */


/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm; */
/* @var $model \app\modules\pendaftaran\models\FormLengkapData */

$this->title = Yii::t('app', 'Proses Lengkap Data');
$this->params['breadcrumbs'][] = $this->title;

$model = \app\modules\pendaftaran\models\FormLengkapData::findStep(1);

$status = $model::$statusForm;

$s = '';
foreach ($model::$step as $item) {
    $numForm = 'form'.$item['id'];
    if ($status->$numForm == 1) {
        $class = 'info';
        $url = \yii\helpers\Html::a("<i class='fa fa-play'></i>",['/pendaftaran/lengkap-data', 'step' => $item['id']],
            ['class'=>'btn btn-sm btn-info', 'style'=>'width: 40px;', ]);

    } elseif ($status->$numForm == 2){
        $class = 'success';
        $url = \yii\helpers\Html::a("<i class='fa fa-play'></i>",['/pendaftaran/lengkap-data', 'step' => $item['id']],
            ['class'=>'btn btn-sm btn-info', 'style'=>'width: 40px;', ]);
    } else {
        $class = 'warning';
        $url = '';
    }
    $s .= "<div class='oaerror $class'>
            <strong>$item[id]</strong> - $item[name]
            <div class='pull-right'>$url</div>
        </div>";
}

?>


<div class="row">
    <br><br>
    <div class="error-notice">
        <?= $s?>
    </div>
</div>