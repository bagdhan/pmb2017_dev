<?php

namespace app\components;
use yii\web\UrlManager;
use Yii;

class ZUrlManager extends UrlManager
{
    public function createUrl($params)
    {
        $params = (array) $params;
        // print_r($params);die();
        if (!isset($params['language'])) {
            if (Yii::$app->session->has('language'))
                Yii::$app->language = Yii::$app->session->get('language');
            else if(isset(Yii::$app->request->cookies['language']))
                Yii::$app->language = Yii::$app->request->cookies['language']->value;

            Yii::$app->language = in_array(Yii::$app->language, ['id', 'en']) ? Yii::$app->language : 'en';

            $params['language']=Yii::$app->language;

        }
        return parent::createUrl($params);
    }
}