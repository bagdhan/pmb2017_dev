<?php
/* @var $this yii\web\View */
/* @var $absenForm app\modules\verifikasi\models\KuForm*/

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

?>
<h1>Absen Kuliah Umum</h1>

<?php $form = ActiveForm::begin([
    'action' => ['kuliahumum'],
    'method' => 'get',
]); ?>

<?= $form->field($absenForm, 'inputan')->textInput(['autoFocus' => true]) ?>

<div class="form-group">
    <?= Html::submitButton( Yii::t('app', 'submit') , ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>



<?php
//if (isset($_GET['AbsenForm']['inputan'])) {
    echo "<div class=\"box box-default color-palette-box\">
    <div class=\"box-header with-border\">
        <h3 class=\"box-title\">Detail Mahasiswa</h3>
    </div>
    <div class=\"box-body\">
        ";
    echo $absenForm->getDetail();

    echo "</div>
    </div>";

//}


$inputan = Html::getInputId($absenForm, 'inputan');

$this->registerJs("$('#$inputan').val('');", View::POS_READY, 'clearinput');