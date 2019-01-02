<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modelsDB\JenisBiaya */

$this->title = Yii::t('app', 'Create Jenis Biaya');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jenis Biayas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-biaya-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
