<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/5/2017
 * Time: 11:42 PM
 */

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm; */
/* @var $model \app\modules\pendaftaran\models\FormLengkapData */

use app\components\Lang;

$detailPost = \app\models\Post::findOne(['id' => 12]);

if ($detailPost != null) {
    $t = Lang::t($detailPost->title, $detailPost->title_en);
    $c = Lang::t($detailPost->content, $detailPost->content_en);

    echo '<h3>'.$t.'</h3>';
    echo str_replace('{TA}', (date('Y') . '/' . (date('Y') + 1)), $c);
}

?>


<?= $form->field($model , 'setuju')->checkbox()->label(false) ?>