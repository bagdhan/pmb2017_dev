<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modelsDB\Orang */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Orang',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orangs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="orang-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
