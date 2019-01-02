<?php
/**
 * Created by
 * User: Wisard17
 * Date: 28/01/2018
 * Time: 05.36 PM
 */

namespace app\modules\admin\models\confirmasiPin;


use Yii;
use app\adminlte\assets\plugin\DataTableAsset;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

/**
 * Class DataTable
 * @package app\modules\admin\models\confirmasiPin
 */
class DataTable extends Widget
{

    /**
     * @var array the HTML attributes for the container tag of the list view.
     * The "tag" element specifies the tag name of the container element and defaults to "div".
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    public $columns;

    /** @var Model */
    public $model;

    public $request;

    public $ajaxUrl;


    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->columns === null) {
            throw new InvalidConfigException('The "columns" property must be set.');
        }

        if ($this->model === null) {
            throw new InvalidConfigException('The "model" property must be set.');
        }

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        $assetTable = DataTableAsset::register($this->view);
        echo $this->loadTable();
        $this->runJs();
    }

    /**
     * @return string
     */
    public function loadTable()
    {
        $content = '';

        return Html::tag('table', "",
            ['class' => "table table-responsive", 'width' => "100%", 'id' => $this->options['id']]);
    }


    /**
     * @return array
     */
    protected function loadColumnHeader()
    {
        $out = [];
        foreach ($this->columns as $column) {
            $showD = $column == '' ? 'details-control' : '';
            $out[] = [
                'title' => $this->model->getAttributeLabel($column),
                'data' => "$column",
                'className' => $showD,
            ];
        }

        return $out;
    }

    /**
     * @return string
     */
    protected function loadColumnFilter()
    {
        $out = '<tr>';
        foreach ($this->columns as $column) {
            if (!in_array($column, [
                'actions',
                'followup',
                'status',
            ])) {
                $out .= "<th class='hasinput' rowspan='1' colspan='1'><input type='text' class='' placeholder=' Filter " . $this->model->getAttributeLabel($column) . "' /></th>";
            } else {
                $out .= "<th class='hasinput' rowspan='1' colspan='1'></th>";
            }
        }

        return $out . '</tr>';
    }

    /**
     * All JS
     */
    protected function runJs()
    {

        $csrf = Yii::$app->request->getCsrfToken();

        $loadingHtml = '<div align="center"><i class="fa fa-spinner fa-pulse fa-4x fa-fw margin-bottom"></i></div>';

        $toRote = [
            'index',
            '_csrf' => $csrf,
        ];

        if (isset($_GET['tahun']))
            $toRote['tahun'] = $_GET['tahun'];

        $ajaxUrl = Url::toRoute($toRote);

        $idTable = $this->options['id'];

        $columns = Json::encode($this->loadColumnHeader());

        $detailUrl = Url::to(['detail']);

        $appuveUrl = Url::to(['set-approve']);
        $appuveUrl = explode('$',$appuveUrl)[0];

        $order = isset($this->options['order']) ? $this->options['order'] : "[0, 'desc']";

        $jsScript = <<< JS
var renderdata = (function () {
    // var dataSet = ;
    var domElement = $('#$idTable');
    var detailurl = "$detailUrl";

    var table = domElement.DataTable({

        scrollX: true,
        rowId: "noPendaftaran",
        processing: true,
        serverSide: true,
        ajax: "$ajaxUrl",
        columns: $columns,
        order: [$order],
        createdRow: function (row, data, index) {
            if (data.classRow !== '')
                $(row).addClass(data.classRow);
        },
        initComplete: function () {

        }

    });

    domElement.delegate('[data-action=approve]', 'click', function (e) {
        var elm = $(e.target);
        var td = elm.closest('td');
        var nopen = elm.attr('data-id');
        var state = elm.attr('data-state');
        $.ajax({
            type: 'POST',
            url: '$appuveUrl',
            dataType: 'json',
            data: {
                'nop': nopen,
                'state': state,
                "_csrf": "$csrf"
            },
            beforeSend: function () {
                blockLoading(td);
            },
            success: function (data) {
                table.cell(td).data(data.button_action);
                td.unblock();
            },
            error: function () {

                td.unblock();
            }
        });
    });

    domElement.on('click', 'tbody .details-control', function () {

        var tr = $(this).closest('tr');
        tr.toggleClass('selected');

        var row = table.row(tr);

        var orderNo = row.data().Order_No;


        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {

            if (row.child() === undefined || row.child().find('div').length < 2) {
                row.child('$loadingHtml');
                $.get(detailurl, {
                    "orderno": orderNo,
                    "_csrf": '$csrf'

                }, function (dataresponse, status, xhr) {
                    if (status === "success")
                        row.child(dataresponse);
                    if (status === "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);
                });
            }
            row.child.show();

            tr.addClass('shown');
        }
    });

    return {
        "test": function () {
            alert('daa');
        },
        table: table
    };
})();        

JS;


        $this->view->registerJs($jsScript, View::POS_READY, 'runfor_' . $this->options['id']);
    }

}