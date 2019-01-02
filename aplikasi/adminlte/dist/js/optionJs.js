/**
 * Created by wisard17 on 11/18/2016.
 */
$('.datepicker').pickadate({
    selectTimes: true,
    formatSubmit: 'yyyy-mm-dd',
    selectYears: true
});


$('.select2').select2({
    placeholder: 'Pilih'
});

if ($.fn.dataTable !== undefined) {
    $.extend($.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fTl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        }
    });
}


function blockLoading(blockElemen) {
    $(blockElemen).block({
        message: '<i class="fa fa-2x fa-spin fa-spinner"></i>',
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.8,
            cursor: 'wait'
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: 'none'
        }
    });
}

function unblockLoading(blockElemen) {
    $(blockElemen).unblock();
}