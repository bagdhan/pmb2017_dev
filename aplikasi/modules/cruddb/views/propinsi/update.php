<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modelsDB\Propinsi */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Propinsi',
]) . $model->kode;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Propinsis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kode, 'url' => ['view', 'id' => $model->kode]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="propinsi-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
