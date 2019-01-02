<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\cruddb\models\SearchNegara */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Negaras');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="negara-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Negara'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nama',
            'inisial',
            'kode',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
