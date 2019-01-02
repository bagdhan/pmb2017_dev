<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modelsDB\Kecamatan */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Kecamatan',
]) . $model->kode;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kecamatans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kode, 'url' => ['view', 'id' => $model->kode]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="kecamatan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
