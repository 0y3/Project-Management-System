/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

const table = '#fxTable';
const table_approve = '#approvedFXTable';
const table_reject = '#rejectFXTable';
let search_start_date, search_end_date;

// Class definition
const FxDatatables = function () {
    // Shared variables
    let dt;

    //Private functions
    // const initDatatable = function () {
    //     $('#spinner').show();
    //     dt = $(table).DataTable({
    //         ajax: {
    //             headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    //             url: "/fx/get_trans",
    //             type: 'post',
    //             // dataType: "json",
    //             // dataSrc: 'data'
    //             // data: {
    //             //     approver_type:'awaiting',is_supervisor:true,
    //             // },
    //             data: function (d) {
    //                 d.approver_type = 'awaiting';
    //                 d.is_supervisor = true;
    //                 d.search_by_trade_startdate = search_start_date;
    //                 d.search_by_trade_enddate = search_end_date;
    //             },
    //             complete: function (data) {
    //                 // console.log('cooo',data,data.responseJSON.recordsTotal);
    //                 $('#pendingFXTrade_badge').text(data.responseJSON.recordsTotal);
    //                 $('#spinner').hide();
    //             },
    //         },
    //         processing: true,
    //         serverSide: true,
    //         pageLength: 15,
    //         autoWidth: false,
    //         dom: '<"datatable-header"fBl><"datatable-scroll"t><"datatable-footer"ip>',
    //         destroy: true,
    //         scrollCollapse: true,
    //         language: {
    //             search: '<span>Filter:</span> _INPUT_',
    //             lengthMenu: '<span>Show:</span> _MENU_',
    //             paginate: { 'first': 'First', 'last': 'Last', 'next': '→', 'previous': '←' }
    //         },
    //         buttons: {
    //             dom: {
    //                 button: {
    //                     className: 'btn btn-light'
    //                 }
    //             },
    //             buttons: [
    //                 // 'copyHtml5',
    //                 'excelHtml5',
    //                 'csvHtml5',
    //                 'pdfHtml5'
    //             ]
    //         },
    //         lengthMenu: [[30, 60, 120, 500, -1], [20, 60, 120, 500, 'All']],
    //         columns: [
    //             // { data: 'member_number' },
    //             { data: null, orderable: false, },
    //             { data: 'trade_date' },
    //             { data: 'counterparty_name' },
    //             { data: 'counterparty_account_number' },
    //             { data: 'bank_name' },
    //             { data: 'bvn' },
    //             { data: 'trade_type_name' },
    //             { data: 'market_type_name' },
    //             { data: 'currency_code' },
    //             { data: 'FX_value' },
    //             { data: 'FX_rate' },
    //             { data: 'purpose' },
    //             { data: 'counterparty_type_name' },
    //             { data: 'form_no' },
    //             { data: 'transaction_ref' },
    //             { data: 'fund_source_name' },
    //             { data: 'fund_application_name' },
    //             { data: 'reasonForReject' },
    //         ],
    //         columnDefs: [
    //             {
    //                 targets: 0,
    //                 searchable: false,
    //                 render: function (data, type, row, meta) {
    //                     if (type === 'display') {
    //                         data = `<div class="checkbox"><input type="checkbox" class="dt-checkboxes" name="approvalChecked[]" value="${row['id']}"><label></label></div>`;
    //                     }

    //                     return data;
    //                 },
    //                 checkboxes: {
    //                     selectRow: true,
    //                     selectAllRender: `<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>`
    //                 }
    //             },
    //             {
    //                 targets: 1,
    //                 searchable: false,
    //                 render: function (data, type, row) {
    //                     return `${moment(data).format("MMM Do, YYYY")}
    //                     <br> ${row.uploaded_files.length ?
    //                             `<div class="dropdown">   
    //                      <a href="#" class="text-primary list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-chevron-down"></i> uploaded files</a>
    //                     <div class="dropdown-menu dropdown-menu-right">
    //                         ${filesDropDownPopulate(row.uploaded_files)}
    //                     </div>
    //                 </div>`: ''
    //                         }`;
    //                 }
    //             },
    //             {
    //                 targets: 9,
    //                 searchable: false,
    //                 render: function (data, type, row) {
    //                     if (parseInt(data) < 0) {
    //                         let removedMinus = Math.abs(parseInt(data))
    //                         return `(${numeral(removedMinus).format('0,0.00')})`;
    //                     }
    //                     return `${numeral(data).format('0,0.00')}`;//moment(data).format("Do MMM, YYYY");
    //                 }
    //             },

    //             {
    //                 targets: 10,
    //                 searchable: false,
    //                 render: function (data, type, row) {
    //                     if (parseInt(data) < 0) {
    //                         let removedMinus = Math.abs(parseInt(data))
    //                         return `(${numeral(removedMinus).format('0,0.00')})`;
    //                     }
    //                     return `${numeral(data).format('0,0.00')}`;
    //                 }
    //             },
    //             {
    //                 targets: -1,
    //                 searchable: false,
    //                 render: function (data, type, row) {
    //                     return data ? '<button type="button" onClick="showReason(\'' + data + '\')" class="btn btn-primary p-0 px-1 m-0 btn-sm">view</button>' : '-'
    //                 }
    //             },
    //         ],
    //         createdRow: function (row, data, dataIndex) {
    //             if (data['is_fully_approved'] == 0) {
    //                 // console.log(data['debit_gl_id'],row);
    //                 $(row).addClass('info')
    //             }
    //         },
    //     });


    // }

    // Private functions
    // const initApproveDatatable = function () {
    //     dt = $(table_approve).DataTable({
    //         ajax: {
    //             headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    //             url: "/fx/get_trans",
    //             type: 'post',
    //             // dataType: "json",
    //             // dataSrc: 'data'
    //             data: {
    //                 approver_type: 'supervisor_approved', is_supervisor: true,
    //             },
    //             data: function (d) {
    //                 d.approver_type = 'supervisor_approved';
    //                 d.is_supervisor = true;
    //                 d.search_by_trade_startdate = search_start_date;
    //                 d.search_by_trade_enddate = search_end_date;
    //             },
    //             complete: function (data) {
    //                 // console.log('cooo',data,data.responseJSON.recordsTotal);
    //                 $('#approveFXTrade_badge').text(data.responseJSON.recordsTotal);
    //                 $('#spinner').hide();
    //             },
    //         },
    //         processing: true,
    //         serverSide: true,
    //         pageLength: 15,
    //         autoWidth: false,
    //         dom: '<"datatable-header"fBl><"datatable-scroll"t><"datatable-footer"ip>',
    //         destroy: true,
    //         scrollCollapse: true,
    //         language: {
    //             search: '<span>Filter:</span> _INPUT_',
    //             lengthMenu: '<span>Show:</span> _MENU_',
    //             paginate: { 'first': 'First', 'last': 'Last', 'next': '→', 'previous': '←' }
    //         },
    //         buttons: {
    //             dom: {
    //                 button: {
    //                     className: 'btn btn-light'
    //                 }
    //             },
    //             buttons: [
    //                 // 'copyHtml5',
    //                 'excelHtml5',
    //                 'csvHtml5',
    //                 'pdfHtml5'
    //             ]
    //         },
    //         lengthMenu: [[30, 60, 120, 500, -1], [20, 60, 120, 500, 'All']],
    //         columns: [
    //             { data: 'trade_date' },
    //             { data: 'counterparty_name' },
    //             { data: 'counterparty_account_number' },
    //             { data: 'bank_name' },
    //             { data: 'bvn' },
    //             { data: 'trade_type_name' },
    //             { data: 'market_type_name' },
    //             { data: 'currency_code' },
    //             { data: 'FX_value' },
    //             { data: 'FX_rate' },
    //             { data: 'purpose' },
    //             { data: 'counterparty_type_name' },
    //             { data: 'form_no' },
    //             { data: 'transaction_ref' },
    //             { data: 'fund_source_name' },
    //             { data: 'fund_application_name' },
    //         ],
    //         columnDefs: [
    //             {
    //                 targets: 0,
    //                 searchable: false,
    //                 render: function (data, type, row) {
    //                     return `${moment(data).format("MMM Do, YYYY")}
    //                     <br> ${row.uploaded_files.length ?
    //                             `<div class="dropdown">   
    //                      <a href="#" class="text-primary list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-chevron-down"></i> uploaded files</a>
    //                     <div class="dropdown-menu dropdown-menu-right">
    //                         ${filesDropDownPopulate(row.uploaded_files)}
    //                     </div>
    //                 </div>`: ''
    //                         }`;
    //                 }
    //             },
    //             {
    //                 targets: 8,
    //                 searchable: false,
    //                 render: function (data, type, row) {
    //                     return `${numeral(data).format('0,0.00')}`;//moment(data).format("Do MMM, YYYY");
    //                 }
    //             },

    //             {
    //                 targets: 9,
    //                 searchable: false,
    //                 render: function (data, type, row) {
    //                     return `${numeral(data).format('0,0.00')}`;
    //                 }
    //             }
    //         ],
    //         createdRow: function (row, data, dataIndex) {
    //             if (data['is_fully_approved'] == 0) {
    //                 // console.log(data['debit_gl_id'],row);
    //                 $(row).addClass('info')
    //             }
    //         },
    //     });
    // }

    // Private functions
    const initRejectDatatable = function () {
        dt = $(table_reject).DataTable({
            ajax: {
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: "/fx/get_trans",
                type: 'post',
                // dataType: "json",
                // dataSrc: 'data'
                // data: {
                //     approver_type:'all_rejected_FS',is_supervisor:true,
                // },
                data: function (d) {
                    d.approver_type = "all_rejected_FS";
                    d.is_supervisor = true;
                    d.search_by_trade_startdate = search_start_date;
                    d.search_by_trade_enddate = search_end_date;
                },
                complete: function (data) {
                    // console.log('cooo',data,data.responseJSON.recordsTotal);
                    $('#rejectFXTrade_badge').text(data.responseJSON.recordsTotal);
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
                paginate: { 'first': 'First', 'last': 'Last', 'next': '→', 'previous': '←' }
            },
            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-light'
                    }
                },
                buttons: [
                    // 'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            },
            lengthMenu: [[30, 60, 120, 500, -1], [20, 60, 120, 500, 'All']],
            columns: [
                { data: 'trade_date' },
                { data: 'counterparty_name' },
                { data: 'counterparty_account_number' },
                { data: 'bank_name' },
                { data: 'bvn' },
                { data: 'trade_type_name' },
                { data: 'market_type_name' },
                { data: 'currency_code' },
                { data: 'FX_value' },
                { data: 'FX_rate' },
                { data: 'purpose' },
                { data: 'counterparty_type_name' },
                { data: 'form_no' },
                { data: 'transaction_ref' },
                { data: 'fund_source_name' },
                { data: 'fund_application_name' },
                { data: 'reasonForReject' },
            ],
            columnDefs: [
                {
                    targets: 0,
                    searchable: false,
                    render: function (data, type, row) {
                        return ` ${moment(data).format("MMM Do, YYYY")} 
                        <br> ${row.uploaded_files.length ?
                                `<div class="dropdown">   
                         <a href="#" class="text-primary list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-chevron-down"></i> uploaded files</a>
                        <div class="dropdown-menu dropdown-menu-right">
                            ${filesDropDownPopulate(row.uploaded_files)}
                        </div>
                    </div>`: ''
                            }`;
                    }
                },
                {
                    targets: 1,
                    searchable: false,
                    render: function (data, type, row) {
                        return `${data} `;
                    }
                },
                {
                    targets: 8,
                    searchable: false,
                    render: function (data, type, row) {
                        return `${numeral(data).format('0,0.00')}`;//moment(data).format("Do MMM, YYYY");
                    }
                },

                {
                    targets: 9,
                    searchable: false,
                    render: function (data, type, row) {
                        if (parseInt(data) < 0) {
                            let removedMinus = Math.abs(parseInt(data))
                            return `(${numeral(removedMinus).format('0,0.00')})`;
                        }
                        return `${numeral(data).format('0,0.00')}`;
                    }
                },

                {
                    targets: -1,
                    searchable: false,
                    render: function (data, type, row) {
                        return data ? '<button type="button" onClick="showReason(\'' + data + '\')" class="btn btn-primary p-0 px-1 m-0 btn-sm">view</button>' : '-'
                    }
                },
            ],
            createdRow: function (row, data, dataIndex) {
                if (data['is_fully_approved'] == 0) {
                    // console.log(data['debit_gl_id'],row);
                    $(row).addClass('info')
                }
            },
        });
    }

    // Public methods
    return {
        init: function () {
            initDatatable();
            initApproveDatatable();
            initRejectDatatable();
        }
    }
}();


// On document ready
jQuery(document).ready(function () {
    FxDatatables.init();



    $('#pendingFXTrade').on('click', '#pendingApproveFXTradeTableForm', function (e) {
        e.preventDefault();
        $('#spinner').show();
        $(this).addClass('disabled');


        let formName = $('#pendingApproveRejectFXTradeTableForm');
        let url = formName.attr('action');
        let formData = new FormData(formName[0]);
        formData.append('decision', 'approve');

        axios.post(url, formData)
            .then(res => {
                // console.log(res);console.log(res.status,res.statusText);
                $('#spinner').show();
                $(table).DataTable().ajax.reload();
                $(table_approve).DataTable().ajax.reload();
                $(table_reject).DataTable().ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Successfull...',
                    text: 'FX Trade posted Successfully',
                });
                // $("#fxTable").DataTable().ajax.reload();
            })
            .catch(error => {
                console.log(error);
            });
        $(this).removeClass('disabled');
        $('#spinner').hide();
    });

    $('#pendingFXTrade').on('click', '#pendingRejectFXTradeTableForm', function (e) {
        e.preventDefault();
        $('#spinner').show();
        $(this).addClass('disabled');


        let formName = $('#pendingApproveRejectFXTradeTableForm');
        let url = formName.attr('action');
        let formData = new FormData(formName[0]);
        formData.append('decision', 'reject');

        axios.post(url, formData)
            .then(res => {
                // console.log(res);console.log(res.status,res.statusText);
                $('#spinner').show();
                $(table).DataTable().ajax.reload();
                $(table_approve).DataTable().ajax.reload();
                $(table_reject).DataTable().ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Successfull...',
                    text: 'FX Trade posted Successfully',
                });
                // $("#fxTable").DataTable().ajax.reload();
            })
            .catch(error => {
                console.log(error);
            });
        $(this).removeClass('disabled');
        $('#spinner').hide();
    });


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
            $(table).DataTable().ajax.reload();
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


    $('.approvedDaterangeFXTrade').daterangepicker(
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
            $('.approvedDaterangeFXTrade span').html(start.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + end.format('MMMM D, YYYY') + ' &nbsp; <b class="list-icons-item" data-action="collapse"></b>');
            search_start_date = start.format('YYYY-MM-DD');
            search_end_date = end.format('YYYY-MM-DD');
            $('#spinner').show();
            $(table_approve).DataTable().ajax.reload();
        }
    );

    // Display date format
    $('.approvedDaterangeFXTrade span').html(moment().subtract(5, 'years').format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + moment().format('MMMM D, YYYY') + ' &nbsp; <b class="list-icons-item" data-action="collapse"></b>');

    //cancle button
    $('.approvedDaterangeFXTrade').on('cancel.daterangepicker', function (ev, picker) {
        //do something, like clearing an input
        $('.approvedDaterangeFXTrade span').html(moment().subtract(5, 'years').format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + moment().format('MMMM D, YYYY') + ' &nbsp; <b class="list-icons-item" data-action="collapse"></b>');
        search_start_date = search_end_date = null;
        $('#spinner').show()
        $(table_approve).DataTable().ajax.reload();
    });



    $('.rejectDaterangeFXTrade').daterangepicker(
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
            $('.rejectDaterangeFXTrade span').html(start.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + end.format('MMMM D, YYYY') + ' &nbsp; <b class="list-icons-item" data-action="collapse"></b>');
            search_start_date = start.format('YYYY-MM-DD');
            search_end_date = end.format('YYYY-MM-DD');
            $('#spinner').show();
            $(table_reject).DataTable().ajax.reload();
        }
    );

    // Display date format
    $('.rejectDaterangeFXTrade span').html(moment().subtract(5, 'years').format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + moment().format('MMMM D, YYYY') + ' &nbsp; <b class="list-icons-item" data-action="collapse"></b>');

    //cancle button
    $('.rejectDaterangeFXTrade').on('cancel.daterangepicker', function (ev, picker) {
        //do something, like clearing an input
        $('.rejectDaterangeFXTrade span').html(moment().subtract(5, 'years').format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + moment().format('MMMM D, YYYY') + ' &nbsp; <b class="list-icons-item" data-action="collapse"></b>');
        search_start_date = search_end_date = null;
        $('#spinner').show();
        $(table_reject).DataTable().ajax.reload();
    });


});
