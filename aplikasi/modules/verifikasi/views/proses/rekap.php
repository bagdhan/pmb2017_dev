<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 8/25/2016
 * Time: 5:05 AM
 *
 * @var $this yii\web\View
 * @var $table
 */

\app\adminlte\assets\plugin\DataTableAsset::register($this);

$this->params['sidebar'] = 0;

echo $table;

$jsScript = <<< JS
    var tabelhtml = $('#tabellog'); 

        tabelhtml.find('tfoot th').each(function () {
                var title = $(this).text();
                if  (title == 'Strata') {
                    $(this).html('<select name="status" class="form-control"> ' +
                        '<option value="">Pilih Semua</option> ' +
                        '<option value="S2"><span class="label label-success">S2 - Magister</span> </option> ' +
                        '<option value="S3"><span class="label label-warning">S3 - Doktor</span> </option></select>');
                } else if (title != '' && title != 'No' && title != 'Aksi') {
                    $(this).html('<input type="text" placeholder=" ' + title + '" />');
                } else {
                    $(this).html('');
                }
            });
    
    var table = tabelhtml.DataTable();
        table.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
                $('select', this.footer()).on('keyup change', function () {
                    if (that.search() !== this.value) {
                        that
                            .search( this.value , true, false )
                            .draw();
                    }
                });
            });
JS;


$this->registerJs($jsScript, \yii\web\View::POS_READY, 'log');