<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modelsDB\JenisKontak */

$this->title = Yii::t('app', 'Create Jenis Kontak');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jenis Kontaks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-kontak-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
