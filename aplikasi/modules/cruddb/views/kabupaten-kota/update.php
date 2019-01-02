<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modelsDB\KabupatenKota */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Kabupaten Kota',
]) . $model->kode;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kabupaten Kotas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kode, 'url' => ['view', 'id' => $model->kode]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="kabupaten-kota-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
