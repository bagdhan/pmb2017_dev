<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modelsDB\JenisBiaya */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Jenis Biaya',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jenis Biayas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="jenis-biaya-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
