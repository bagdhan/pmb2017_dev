<?php
/**
 * Created by
 * User: Wisard17
 * Date: 24/02/2018
 * Time: 09.30 PM
 */

namespace app\modules\admin\models\paketPendaftaran;


use yii\helpers\Html;
use Yii;
use yii\helpers\Url;

/**
 * Class PaketPendaftaran
 * @package app\modules\admin\models\paketPendaftaran
 *
 * @property string $action
 * @property int $numProdi
 */
class PaketPendaftaran extends \app\models\PaketPendaftaran
{


    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function renderTable()
    {
        $out = Html::tag('thead', Html::tag('tr',
            Html::tag('th', 'No')
            . Html::tag('th', 'Title')
            . Html::tag('th', 'Tahun')
            . Html::tag('th', 'Start')
            . Html::tag('th', 'End')
            . Html::tag('th', 'Jumlah PS')
            . Html::tag('th', '')
            . '<tbody>'
        ));

        $q = self::find();
        $q->orderBy('dateStart DESC');
        $f = Yii::$app->formatter;
        foreach ($q->all() as $i => $record) {
            $out .= Html::tag('tr',
                Html::tag('td', ($i + 1))
                . Html::tag('td', $record->title)
                . Html::tag('td', $record->tahun)
                . Html::tag('td', $f->asDate($record->dateStart))
                . Html::tag('td', $f->asDate($record->dateEnd))
                . Html::tag('td', $record->numProdi)
                . Html::tag('td', $record->action)
                , ['data-id' => $record->id]
            );
        }

        return Html::tag('div', Html::tag('table', $out . '</tbody>', ['class' =>
            'table table-bordered table-striped table-condensed table-hover'
        ]), ['class' => 'table-responsive']);
    }

    /**
     * @return int
     */
    public function getNumProdi()
    {
        return sizeof($this->paketPendaftaranHasManajemenJalurMasuks);
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return Html::tag('div',
            Html::tag('a', 'Action <span class="caret"></span>', [
                'class' => "btn btn-default dropdown-toggle", 'data-toggle' => "dropdown", 'aria-expanded' => "false"
            ]) . Html::tag('ul',
                Html::tag('li', Html::tag('a', '<i class="fa fa-caret-square-o-right"></i> view', ['href' => Url::toRoute(['/admin/paket-pendaftaran/view', 'id' => $this->id])])) .
                Html::tag('li', Html::tag('a', '<i class="fa fa-edit"></i> Edit', ['href' => Url::toRoute(['/admin/paket-pendaftaran/edit', 'id' => $this->id])])) .
                Html::tag('li', Html::tag('a', '<i class="fa fa-times"></i> Delete',
                    ['href' => Url::toRoute(['/admin/paket-pendaftaran/delete', 'id' => $this->id]),
                        'data-method' => 'post',
                        'data-confirm' => 'Are you absolutely sure ? You will lose all the information about this user with this action.',
                    ]))

                , ['class' => "dropdown-menu"]), ['class' => "btn-group"]);
    }
}