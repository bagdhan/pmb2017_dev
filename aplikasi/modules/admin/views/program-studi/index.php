<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\SearchProgramStudi */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Program Studi');
$this->params['breadcrumbs'][] = $this->title;
$this->params['sidebar'] = 0;
?>
<div class="program-studi-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Program Studi'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'kode',
            'nama',
//            'nama_en',
//            'aktif',
//             'strata',
            [
                'class' => '\yii\grid\DataColumn',
                'attribute' => 'strata',
                'value' => function ($item) {
                    return 'S'.$item->strata;
                }
            ],
             'inisial',
            // 'kode_nasional',
            // 'sk_pendirian:ntext',
            // 'mandat:ntext',
            // 'visi_misi:ntext',
            // 'departemen_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
