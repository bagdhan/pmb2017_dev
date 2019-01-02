<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\verifikasi\models\Tableverifikasi */

$this->title = Yii::t('app', 'Ubah data Meja: ', [
    'modelClass' => 'Tableverifikasi',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Meja Verifikasi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tableverifikasi-update">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
