<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modelsDB\Propinsi */

$this->title = $model->kode;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Propinsis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="propinsi-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->kode], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->kode], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'kode',
            'namaID',
            'namaEN',
        ],
    ]) ?>

</div>
