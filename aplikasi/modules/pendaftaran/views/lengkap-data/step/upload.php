<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/5/2017
 * Time: 10:23 PM
 */


/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm; */
/* @var $upload \app\modules\pendaftaran\models\lengkapdata\UploadBerkas */

use app\components\Lang;

$detailPost = \app\models\Post::findOne(['id' => 11]);

if ($detailPost != null)
    echo Lang::t($detailPost->content, $detailPost->content_en);

foreach ($upload->attributes as $key => $value) {
    $show = [];
    if ($upload->$key != null)
        $show = [
            'template' => "{label}<div class='col-lg-8'>
                <div class='row'>
                    <div class='col-lg-6'>
                        {input}{error}        
                    </div>
                    <div class='col-lg-6'>
                        <a target='_blank' href='" . $upload->urlberkas . '/' . $upload->$key . "' type='button' class='btn btn-info'> <i class='fa'></i> lihat</a>
                                                
                    </div>
                </div>
                </div>",
            'labelOptions' => ['class' => 'col-lg-4 control-label'],
        ];


    echo "<div class='row'>" . $form->field($upload, $key, $show)->fileInput() . "</div>";
}


?>