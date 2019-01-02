<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modelsDB\Kecamatan */

$this->title = Yii::t('app', 'Create Kecamatan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kecamatans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kecamatan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
