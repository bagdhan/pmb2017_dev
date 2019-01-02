<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/20/2017
 * Time: 12:30 PM
 */

namespace app\widgets;


class Captcha extends \yii\captcha\Captcha
{
    public $captchaAction = '/site/captcha';
}