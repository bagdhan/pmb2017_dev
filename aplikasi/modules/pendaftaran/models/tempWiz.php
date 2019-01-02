<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/24/2017
 * Time: 12:22 PM
 */

namespace app\modules\pendaftaran\models;

use Yii;
use yii\base\Model;
use yii\web\View;

class tempWiz extends Model
{


    public static function renderJS()
    {
        $js = <<< JS
var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var target = $($(this).attr('href')),
                item = $(this);

        if (!item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            item.addClass('btn-primary');
            allWells.hide();
            target.show();
            target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function(){
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            if (!curInputs[i].validity.valid){
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.btn-primary').trigger('click');
JS;
        $view = Yii::$app->getView();
        $view->registerJs($js, View::POS_READY, 'wiztemp');
    }

    public static function renderCSS()
    {
        $css = <<< CSS

.stepwizard-step p {
    margin-top: 25px;
    
}

.stepwizard-row {
    display: table-row;
}

.stepwizard {
    display: table;
    width: 100%;
    position: relative;
}

.stepwizard-step button[disabled] {
    opacity: 1 !important;
    filter: alpha(opacity=100) !important;
}

.stepwizard-row:before {
    top: 45px;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 100%;
    height: 1px;
    background-color: #ccc;
    z-order: 0;

}

.stepwizard-step {
    display: table-cell;
    text-align: center;
    position: relative;
    padding-top: 25px;
}

.stepwizard-step:hover {
    background-color: rgba(210, 214, 222, .06);
}

.stepwizard-step.cuse {
    background-color: rgba(210, 214, 222, .4);
background-image: -webkit-gradient(linear, 0 0, 0 100%, color-stop(.5, rgba(255, 255, 255, .2)), color-stop(.5, transparent), to(transparent));
background-image: -moz-linear-gradient(rgba(255, 255, 255, .2) 50%, transparent 50%, transparent);
background-image: -o-linear-gradient(rgba(255, 255, 255, .2) 50%, transparent 50%, transparent);
background-image: linear-gradient(rgba(255, 255, 255, .2) 50%, transparent 50%, transparent);
border-radius: 10px;
}

.btn-circle {
  width: 40px;
  height: 40px;
  text-align: center;
  padding: 6px 0;
  font-size: 18px;
  line-height: 1.428571429;
  border-radius: 18px;
}
CSS;

        $view = Yii::$app->getView();
        $view->registerCss($css);
    }
}