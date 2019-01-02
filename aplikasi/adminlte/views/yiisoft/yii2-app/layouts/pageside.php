<?php
/**
 * Created by
 * User: wisard17
 * Date: 11/10/2016
 * Time: 2:26 PM
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$model = new \app\models\LoginForm();
if (Yii::$app->user->isGuest) {
?>

<?php }?>