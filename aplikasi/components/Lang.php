<?php

/**
 * Description of Lang
 *
 * @author abrari
 */
namespace app\components;

use Yii;
use yii\base\Component;

class Lang extends Component
{

     /**
     * Check if current language is indonesian
     * @return boolean
     */
    public static function id() {
        $cookies = Yii::$app->response->cookies;
        $languageNew = Yii::$app->request->get('language');
        if($languageNew)
        {
            
                Yii::$app->language = $languageNew;
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'language',
                    'value' => $languageNew
                ]));
            
        }
        elseif($cookies->has('language'))
        {
            Yii::$app->language = $cookies->getValue('language');
        }
        return (Yii::$app->language == 'id');
    }

    /**
     *
     * @param string $id Text in indonesian
     * @param string $en Text in english
     * @return string
     */
    public static function t($id, $en) {
        return self::id() ? $id : $en;
    }

    public static function get() {
        return self::id() ? 'id' : 'en';
    }

}