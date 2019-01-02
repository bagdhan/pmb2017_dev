<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 3/13/2017
 * Time: 8:31 PM
 */

namespace app\models;

use Yii;
use yii\base\Model;

class LogFile extends Model
{

    /**
     * @param string $filename
     * @param string $content
     * @return bool
     */
    public static function write($filename, $content)
    {
        $file = Yii::getAlias('@arsipdir') . '/../' . $filename . '.txt';
        $current = file_exists($file) ? file_get_contents($file) : '';
        $current .= "$content\n";
        file_put_contents($file, $current);
        return true;
    }
}