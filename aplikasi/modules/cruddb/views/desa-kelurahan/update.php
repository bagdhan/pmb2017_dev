<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modelsDB\DesaKelurahan */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Desa Kelurahan',
]) . $model->kode;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Desa Kelurahans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kode, 'url' => ['view', 'id' => $model->kode]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="desa-kelurahan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
