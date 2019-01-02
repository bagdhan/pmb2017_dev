<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\cruddb\models\SearchOrang */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orangs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orang-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Orang'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nama',
            'KTP',
            'tempatLahir',
            'tanggalLahir',
            // 'jenisKelamin',
            // 'statusPerkawinan_id',
            // 'NPWP',
            // 'waktuBuat',
            // 'waktuUbah',
            // 'negara_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
