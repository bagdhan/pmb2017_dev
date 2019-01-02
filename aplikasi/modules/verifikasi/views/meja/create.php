<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\verifikasi\models\Tableverifikasi */

$this->title = Yii::t('app', 'Tamah Meja');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Meja Verifikasi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tableverifikasi-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
