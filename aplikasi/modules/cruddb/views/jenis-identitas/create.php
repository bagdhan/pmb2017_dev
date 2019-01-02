<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modelsDB\JenisIdentitas */

$this->title = Yii::t('app', 'Create Jenis Identitas');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jenis Identitas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-identitas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
