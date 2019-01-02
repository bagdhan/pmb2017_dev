<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modelsDB\DesaKelurahan */

$this->title = Yii::t('app', 'Create Desa Kelurahan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Desa Kelurahans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="desa-kelurahan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
