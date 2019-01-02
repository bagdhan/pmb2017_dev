<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\GenNrpSearch2 */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Manajemen NRP');
$this->params['breadcrumbs'][] = $this->title;
$this->params['sidebar'] = 0;
?>
<div class="gen-nrp-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Generate NRP'), ['/admin/generatornrp'], ['class' => 'btn btn-success','target'=>'_blank']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

       //     'id',
            'noPendaftaran',
            'name',
            'nrp',
            'kodeProdi',
            'tahunMasuk',
            'noUrut',
            //'kode_khusus',

             'kodeMasuk',
            // 'locknrp',
            // 'date_create',
            // 'date_update',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
