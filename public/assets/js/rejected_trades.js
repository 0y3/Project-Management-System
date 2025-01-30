const tableReport = '#reportFXTable';
let search_start_date, search_end_date;

// Class definition
const FxDatatables = function () {
    // Shared variables
    let dt;
    var _token = $('meta[name="csrf-token"]').attr('content');

    // Private functions
    const initDatatable = function () {
        $('#spinner').show();
        dt = $(tableReport).DataTable({
            initComplete: function () {
                this.api().columns().every(function () {
                    let column = this;
                    // console.log('column.search(): ', column.search());
                    // console.log('this.footer: ', this.footer());

                    $('input', this.footer()).on('change keyup clear', function () {
                        // $('input', 'thead.thead tr th').on('keyup change clear', function (){
                        console.log('column.search(): ', column.search());
                        console.log('this.value: ', this.value);

                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                    });
                });
            },
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: `/report/all_cbn_rejected_trades`,
                type: 'post',
                data: function (d) {
                    d.trade_start_date = search_start_date;
                    d.trade_end_date = search_end_date;
                },
                complete: function (data) {
                    // console.log('qreceived here:', data);
                    // $('#approveFXTrade_badge').text(data.responseJSON.recordsTotal);
                    $('#spinner').hide();
                },
            },
            processing: true,
            serverSide: true,
            pageLength: 15,
            autoWidth: false,
            dom: '<"datatable-header"fBl><"datatable-scroll"t><"datatable-footer"ip>',
            destroy: true,
            scrollCollapse: true,
            language: {
                search: '<span>Filter:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: {
                    'first': 'First',
                    'last': 'Last',
                    'next': '→',
                    'previous': '←'
                }
            },
            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-light'
                    }
                },
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Excel',
                    filename: function () {
                        var d = new Date();
                        var n = d.getTime();
                        return 'report_FXTrades' + n;
                    },
                    title: `Report FX TRADE TABLE - ${new Date().getDate()}/${new Date().getMonth() + 1}/${new Date().getFullYear()}`
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV',
                    filename: function () {
                        var d = new Date();
                        var n = d.getTime();
                        return 'report_FXTrades' + n;
                    },
                },
                ]
            },
            lengthMenu: [
                [15, 60, 120, 500, -1],
                [15, 60, 120, 500, 'All']
            ],
            columns: [
                {
                    data: 'AuditId'
                },
                {
                    data: 'bank_name'
                },
                {
                    data: 'currency_code'
                },
                {
                    data: 'id'
                },
                {
                    data: 'system_ref'
                },
                {
                    data: 'transaction_ref'
                },
                {
                    data: 'counterparty_name'
                },
                {
                    data: 'counterparty_type_name'
                },
                {
                    data: 'counterparty_account_number'
                },
                {
                    data: 'bvn'
                },
                {
                    data: 'trade_date'
                },
                {
                    data: 'settlement_date'
                },
                {
                    data: 'trade_type_name'
                },
                {
                    data: 'market_type_name'
                },

                {
                    data: 'FX_value'
                },
                {
                    data: 'FX_rate'
                },
                {
                    data: 'Cross_rate'
                },
                {
                    data: 'naira_equivalent'
                },
                {
                    data: 'usd_equivalent'
                },
                {
                    data: 'purpose'
                },
                {
                    data: 'form_no'
                },
                {
                    data: 'fund_source_name'
                },
                {
                    data: 'fund_application_name'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'AuditDate'
                },
            ],
            columnDefs: [{
                targets: [10, 11, -1, -2],
                searchable: false,
                render: function (data, type, row) {
                    return ` ${!data ? '-' : moment(data).format("MMM Do, YYYY")}`;
                }
            },

            {
                targets: [14, 15, 16, 17, 18],
                searchable: true,
                render: function (data, type, row) {
                    return `${data ? numeral(data).format('0,0.00') : '-'}`;
                }
            },

            {
                targets: [3],
                searchable: true,
                render: function (data, type, row) {
                    return `${data}
                             <br>${racStatus(row.AuditAction)} `;
                }
            },

                //
            ],
        });

        $('#reportFXTable tfoot tr').insertAfter($('#reportFXTable thead tr'))
        // Setup - add a text input to each footer cell
        $(tableReport + ' tr.tr-column-search th').each(function () {
            var title = $(this).text();
            if (title && title != 'ACTION') {
                $(this).html('<input type="text" class="form-control form-control-sm column-search-box" placeholder="Search ' + title + '" />');
            }
        });
    }

    // Public methods
    return {
        init: function () {
            initDatatable();
        }
    }
}();


// On document ready
jQuery(document).ready(function () {
    FxDatatables.init();

    $('.daterangeFXTrade').daterangepicker(
        {
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            minDate: '01/01/2020',
            maxDate: moment(),
            // dateLimit: { days: 60 },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            // showDropdowns: true,
            opens: 'left',
            applyClass: 'btn-sm btn-primary',
            cancelClass: 'btn-sm btn-light'
        },
        function (start, end) {
            $('.daterangeFXTrade span').html(start.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + end.format('MMMM D, YYYY') + ' &nbsp; <b class="list-icons-item" data-action="collapse"></b>');
            search_start_date = start.format('YYYY-MM-DD');
            search_end_date = end.format('YYYY-MM-DD');
            $('#spinner').show();
            $(tableReport).DataTable().ajax.reload();
        }
    );

    // Display date format
    $('.daterangeFXTrade span').html(moment().subtract(5, 'years').format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + moment().format('MMMM D, YYYY') + ' &nbsp; <b class="list-icons-item" data-action="collapse"></b>');

    //cancle button
    $('.daterangeFXTrade').on('cancel.daterangepicker', function (ev, picker) {
        //do something, like clearing an input
        $('.daterangeFXTrade span').html(moment().subtract(5, 'years').format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + moment().format('MMMM D, YYYY') + ' &nbsp; <b class="list-icons-item" data-action="collapse"></b>');
        search_start_date = search_end_date = null;
        $('#spinner').show();
        $(table).DataTable().ajax.reload();
    });
});
