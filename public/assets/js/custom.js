/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

const table = '#projecttable';
// const table_processing = '#processingFXTable';
// const table_approve = '#approvedFXTable';
// const table_reject = '#rejectFXTable';
let search_start_date, search_end_date;

// Class definition
const Datatables = function () {
    // Shared variables
    let dt;
    var _token = $('meta[name="csrf-token"]').attr('content');

    // Private functions
    const initDatatable = function () {
        $('#spinner').show();
        dt = $(table).DataTable({
            ajax: {
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: "/project/all-data",
                type: 'get',
                // data: {_token},
                // dataType: "json",
                // dataSrc: 'data'
                // data: {
                //     approver_type:'pending',is_creator:true,
                // },
                // data: function (d) {
                //     d.approver_type = 'pending';
                //     d.is_creator = true;
                //     d.search_by_trade_startdate = search_start_date;
                //     d.search_by_trade_enddate = search_end_date;
                // },
                complete: function (data) {
                    console.log('cooo', data, data.responseJSON.recordsTotal);
                    $('#project_badge').text(data.responseJSON.recordsTotal);
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
            lengthMenu: [[15, 60, 120, 500, -1], [15, 60, 120, 500, 'All']],
            columns: [
                // { data: 'member_number' },
                { data: null, orderable: false, },
                {data: 'bank_name' },
                {data: 'created_at'},
                { data: null }
            ],
            createdRow: function (row, data, dataIndex) {
                if (data['final_approver_id'] == null || data['approver_id1'] == null) {
                    $(row).addClass('table-warning');
                }

            },
        });

    }

    // Private functions
    const initProcessDatatable = function () {
        dt = $(table_processing).DataTable({
            ajax: {
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: "/fx/get_trans",
                type: 'post',
                // dataType: "json",
                // dataSrc: 'data'
                // data: {
                //     approver_type:'all_awaiting',is_creator:true,
                // },
                data: function (d) {
                    d.approver_type = 'all_awaiting';
                    d.is_creator = true;
                    d.search_by_trade_startdate = search_start_date;
                    d.search_by_trade_enddate = search_end_date;
                },
                complete: function (data) {
                    // console.log('cooo',data,data.responseJSON.recordsTotal);
                    $('#processingFXTrade_badge').text(data.responseJSON.recordsTotal);
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
            lengthMenu: [[15, 60, 120, 500, -1], [15, 60, 120, 500, 'All']],
            columns: [
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
            ],
            columnDefs: [
                {
                    targets: [2],
                    searchable: false,
                    render: function (data, type, row) {
                        return `${data}
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
                    targets: [9, 10, -1],
                    searchable: false,
                    render: function (data, type, row) {
                        return ` ${!data ? '-' : moment(data).format("MMM Do, YYYY")}`;
                    }
                },
                {
                    targets: [13, 14, 15, 16, 17],
                    searchable: false,
                    render: function (data, type, row) {
                        // if (parseInt(data) < 0) {
                        //     let removedMinus = Math.abs(parseInt(data))
                        //     return `(${numeral(removedMinus).format('0,0.00')})`;
                        // }
                        return `${!data ? '-' : numeral(data).format('0,0.00')}`;//moment(data).format("Do MMM, YYYY");
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

    // Private functions
    const initApproveDatatable = function () {
        dt = $(table_approve).DataTable({
            ajax: {
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: "/fx/get_trans",
                type: 'post',
                // dataType: "json",
                // dataSrc: 'data'
                // data: {
                //     is_fully_approved:true,bank:true,//is_creator:true,
                // },
                data: function (d) {
                    d.is_fully_approved = true;
                    d.bank = true;
                    d.search_by_trade_startdate = search_start_date;
                    d.search_by_trade_enddate = search_end_date;
                },
                complete: function (data) {
                    // console.log('cooo',data,data.responseJSON.recordsTotal);
                    $('#approveFXTrade_badge').text(data.responseJSON.recordsTotal);
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
            lengthMenu: [[15, 60, 120, 500, -1], [15, 60, 120, 500, 'All']],
            columns: [
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
                    data: null
                },

            ],
            columnDefs: [
                // {
                //     targets: [2],
                //     searchable: false,
                //     render: function (data, type, row) {
                //         return `${moment(data).format("MMM Do, YYYY")}`;
                //     }
                // },
                {
                    targets: [9, 10, -2],
                    searchable: false,
                    render: function (data, type, row) {
                        return ` ${!data ? '-' : moment(data).format("MMM Do, YYYY")}`;
                    }
                },
                {
                    targets: [13, 14, 15, 16, 17],
                    searchable: false,
                    render: function (data, type, row) {
                        // if (parseInt(data) < 0) {
                        //     let removedMinus = Math.abs(parseInt(data))
                        //     return `(${numeral(removedMinus).format('0,0.00')})`;
                        // }
                        return `${!data ? '-' : numeral(data).format('0,0.00')}`;//moment(data).format("Do MMM, YYYY");
                    }
                },

                {
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                        <div class="list-icons">
                            <div class="dropdown">
                                <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="javascript:void(0);" class="dropdown-item" data-toggle="modal" data-target="#newProjectModal" data-clicked="editApproved" data-id="${row['id']}" data-editor_id="${row['editor_id']}"
                                    data-counterparty_name="${row['counterparty_name']}" data-counterparty_account_number="${row['counterparty_account_number']}"
                                    data-bvn="${row['bvn']}" data-trade_type_id="${row['trade_type_id']}" data-market_type_id="${row['market_type_id']}"
                                    data-currency_id="${row['currency_id']}" data-fx_value="${data['FX_value']}" data-fx_rate="${data['FX_rate']}" data-trade_date="${row['trade_date']}" data-purpose="${row['purpose']}"
                                    data-counterparty_type_id="${row['counterparty_type_id']}"
                                    data-form_no="${row['form_no']}"
                                    data-fund_source_id="${row['fund_source_id']}"
                                    data-fund_application_id="${row['fund_application_id']}">

                                    <i class="icon-pencil"></i> Edit</a>
                                    <a onClick="markAsDeleted('${row.id}')" class="dropdown-item text-danger"><i class="icon-x"></i> Delete</a>
                                </div>
                            </div>
                        </div>
                        `;
                    },
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
                //     approver_type:'all_rejected_FS',is_creator:true,
                // },
                data: function (d) {
                    d.approver_type = "all_rejected_FS";
                    d.is_creator = true;
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
            lengthMenu: [[15, 60, 120, 500, -1], [15, 60, 120, 500, 'All']],
            columns: [
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
                // { data: 'reasonForReject' },
                { data: null },
            ],
            columnDefs: [
                // {
                //     targets: 8,
                //     searchable: false,
                //     render: function(data, type, row) {
                //         return  `${numeral(data).format('0,0.00')}`;//moment(data).format("Do MMM, YYYY");
                //     }
                // },

                {
                    targets: [2],
                    searchable: false,
                    render: function (data, type, row) {
                        return `${data}
                        <br> ${row.uploaded_files.length ?
                                `<div class="dropdown">
                                 <a href="#" class="text-primary list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-chevron-down"></i> uploaded files</a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    ${filesDropDownPopulate(row.uploaded_files)}
                                </div>
                            </div>`
                                : ''
                            }`;
                    }
                },
                {
                    targets: [9, 10, -2],
                    searchable: false,
                    render: function (data, type, row) {
                        return ` ${!data ? '-' : moment(data).format("MMM Do, YYYY")}`;
                    }
                },

                {
                    targets: [13, 14, 15, 16, 17],
                    searchable: false,
                    render: function (data, type, row) {
                        return `${!data ? '-' : numeral(data).format('0,0.00')}`;//moment(data).format("Do MMM, YYYY");
                    }
                },

                // {
                //     targets: 17,
                //     searchable: false,
                //     render: function (data, type, row) {
                //         return data ? '<button type="button" onClick="showReason(\'' + data + '\')" class="btn btn-primary p-0 px-1 m-0 btn-sm">view</button>' : '-'
                //     }
                // },

                {
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                        <div class="list-icons">
                            <div class="dropdown">
                                <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="javascript:void(0);" class="dropdown-item" data-toggle="modal" data-target="#newProjectModal" data-clicked="editRejectBtn" data-id="${row['id']}" data-editor_id="${row['editor_id']}"
                                    data-counterparty_name="${row['counterparty_name']}" data-counterparty_account_number="${row['counterparty_account_number']}"
                                    data-bvn="${row['bvn']}" data-trade_type_id="${row['trade_type_id']}" data-market_type_id="${row['market_type_id']}"
                                    data-currency_id="${row['currency_id']}" data-fx_value="${data['FX_value']}" data-fx_rate="${data['FX_rate']}" data-trade_date="${row['trade_date']}" data-purpose="${row['purpose']}"
                                    data-counterparty_type_id="${row['counterparty_type_id']}"
                                    data-form_no="${row['form_no']}"
                                    data-fund_source_id="${row['fund_source_id']}"
                                    data-fund_application_id="${row['fund_application_id']}">

                                    <i class="icon-pencil"></i> Edit</a>
                                    <a onClick="showReason('${row.reasonForReject}')" class="dropdown-item"><i class="icon-bookmark"></i> Reason for rejection</a>
                                </div>
                            </div>
                        </div>
                        `;
                    },
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
            // initProcessDatatable();
            // initApproveDatatable();
            // initRejectDatatable();
        }
    }
}();

// On document ready
jQuery(document).ready(function () {
    Datatables.init();

    let validator;

    // Reset form
    $('#reset').on('click', function () {
        validator.resetForm();
    });

    validator = $(".modal_newProjectModal_form").validate({
        ignore: 'input[type=hidden], .select2-input', // ignore hidden fields
        errorClass: 'validation-invalid-label',
        validClass: "validation-valid-label",
        successClass: 'validation-valid-label',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },

        success: function (label) {
            label.addClass("validation-valid-label").text("Success.")
        },
        rules: {
            counterparty_name: "required",
            purpose: "required",
            trade_date: "required",
            counterparty_account_number: {
                digits: true,
                rangelength: [10, 10]
            },
            bvn: {
                required: true,
                digits: true,
                rangelength: [11, 11]
            },
            currency_id: {
                digits: true,
            },
            trade_type_id: {
                digits: true,
            },
            market_type_id: {
                digits: true,
            },
            FX_value: { number: true },
            FX_rate: { number: true, },

        },
    });

    //post newFXTrade form on submit
    $(document).on("submit", "#modal_newProjectModal_form", function (e) {
        e.preventDefault();

        $('#spinner').show();
        $('#modal_newProjectModal_submit').addClass('disabled');
        let url = $(this).attr('action');
        let formData = new FormData($(this)[0]);

        axios.post(url, formData)
            .then(res => {
                // console.log(res);console.log(res.status,res.statusText);
                $('#spinner').show();
                $(table).DataTable().ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Successfull...',
                    text: 'Project Saved Successfully',
                });
                $('#newProjectModal').modal('hide');
            })
            .catch(error => {
                console.log(error);
            });
        $('#modal_newProjectModal_submit').removeClass('disabled');
        $('#spinner').hide();
    });

    //// Edit and process Btn Action
    $(document).on("submit", "#modal_editRejectBtnnewProjectModal_form", function (e) {
        e.preventDefault();
        $('#spinner').show();
        $('#modal_newProjectModal_submit').addClass('disabled');
        var id_ = $('#modal_editRejectBtnnewProjectModal_form #modal_newProjectModal_submit').attr('data-id');
        let url = `${$('meta[name="url"]').attr('content')}/project/update/${id_}`;
        let formData = new FormData($(this)[0]);

        axios.post(url, formData)
            .then(res => {
                // console.log(res);console.log(res.status,res.statusText);
                $('#spinner').show();
                $(table).DataTable().ajax.reload();
                $(table_processing).DataTable().ajax.reload();
                $(table_approve).DataTable().ajax.reload();
                $(table_reject).DataTable().ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Successfull...',
                    text: 'Project Successfully',
                });
                $('#newProjectModal').modal('hide');
            })
            .catch(error => {
                console.log(error);
            });
        $('#modal_newProjectModal_submit').removeClass('disabled');
        $('#spinner').hide();
    });


    // Edit Btn Action
    $(document).on("submit", "#modal_editBtnnewProjectModal_form", function (e) {
        e.preventDefault();
        $('#spinner').show();
        $('#modal_newProjectModal_submit').addClass('disabled');
        var id_ = $('#modal_editBtnnewProjectModal_form #modal_newProjectModal_submit').attr('data-id');
        let url = `${$('meta[name="url"]').attr('content')}/fx/update/${id_}`;
        let formData = new FormData($(this)[0]);
        formData.append('channel', 'web');

        axios.post(url, formData)
            .then(res => {
                // console.log(res);console.log(res.status,res.statusText);
                Swal.fire({
                    icon: 'success',
                    title: 'Successfull...',
                    text: 'FX Trade posted Successfully',
                });
                $('#spinner').show();
                $(table).DataTable().ajax.reload();
                $(table_processing).DataTable().ajax.reload();
                $(table_approve).DataTable().ajax.reload();
                $(table_reject).DataTable().ajax.reload();
                $('#newProjectModal').modal('hide');
            })
            .catch(error => {
                console.log(error);
            });
        $('#modal_newProjectModal_submit').removeClass('disabled');
        $('#spinner').hide();
    });

    $('#newProjectModal').on('hide.bs.modal', function (e) {
        $('.modal_newProjectModal_form')[0].reset();
        // $('.modal_newProjectModal_form #modal_newProjectModal_submit').attr('data-id','');
        validator.resetForm();
    });

    $('#newProjectModal').on('show.bs.modal', function (e) {
        let bn = $(e.relatedTarget) // Button that triggered the modal
        let clickModal = bn.data('clicked');
        let id = bn.data('id') // Extract info from data-* attributes
        var modal = $(this)

        if (clickModal == "editBtn") {
            modal.find('.modal_newProjectModal_form').attr('id', 'modal_editBtnnewProjectModal_form');
            modal.find('#modal_newProjectModal_submit').text('Edit');
        }
        if (clickModal == "newBtn") {

            modal.find('.modal_newProjectModal_form').attr('id', 'modal_newProjectModal_form');
            modal.find('#modal_newProjectModal_submit').text('Save');
        }

        if (clickModal == "editBtn" || clickModal == "editRejectBtn" || clickModal == "editApproved") {
            modal.find('.modal-body input[name="counterparty_name"]').val(bn.data('counterparty_name'));
            modal.find('.modal-body input[name="bvn"]').val(bn.data('bvn'));
            modal.find('.modal-body input[name="counterparty_account_number"]').val(bn.data('counterparty_account_number'));

            // $("#select option[value=3]").attr('selected', 'selected');

            modal.find('.modal-body #market_type_id').select2().val(bn.data('market_type_id')).trigger('change');
            modal.find('.modal-body #trade_type_id').select2().val(bn.data('trade_type_id')).trigger('change');

            modal.find('.modal-body #currency_id').select2().val(bn.data('currency_id')).trigger('change');
            modal.find('.modal-body input[name="FX_value"]').val(bn.data('fx_value'));
            modal.find('.modal-body input[name="FX_rate"]').val(bn.data('fx_rate'));

            let $datee = new Date(bn.data('trade_date')).toISOString().split('T')[0];
            modal.find('.modal-body input[name="trade_date"]').val($datee);
            modal.find('.modal-body textarea[name="purpose"]').val(bn.data('purpose'));
            modal.find('.modal-body #counterparty_type_id').select2().val(bn.data('counterparty_type_id')).trigger('change');
            modal.find('.modal-body #fund_source_id').select2().val(bn.data('fund_source_id')).trigger('change');
            modal.find('.modal-body #fund_application_id').select2().val(bn.data('fund_application_id')).trigger('change');
            modal.find('.modal-body input[name="form_no"]').val(bn.data('form_no'));
            // modal.find('.modal-body input[name="transaction_ref"]').val(bn.data('transaction_ref'));

            modal.find('#modal_newProjectModal_submit').attr('data-id', bn.data('id'));
        }
    })

    $('#pendingFXTrade').on('click', '#pendingFXTradeProcessing', function (e) {
        e.preventDefault();
        $('#spinner').show();
        $(this).addClass('disabled');


        let formName = $('#pendingFXTradeTableForm');
        let url = formName.attr('action');
        let formData = new FormData(formName[0]);
        formData.append('decision', 'processing');

        axios.post(url, formData)
            .then(res => {
                // console.log(res);console.log(res.status,res.statusText);
                $('#spinner').show();
                $(table).DataTable().ajax.reload();
                $(table_processing).DataTable().ajax.reload();
                $(table_approve).DataTable().ajax.reload();
                $(table_reject).DataTable().ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Successfull...',
                    text: 'FX Trade posted Successfully',
                });
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


    $('.processingDaterangeFXTrade').daterangepicker(
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
            $('.processingDaterangeFXTrade span').html(start.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + end.format('MMMM D, YYYY') + ' &nbsp; <b class="list-icons-item" data-action="collapse"></b>');
            search_start_date = start.format('YYYY-MM-DD');
            search_end_date = end.format('YYYY-MM-DD');
            $('#spinner').show();
            $(table_processing).DataTable().ajax.reload();
        }
    );

    // Display date format
    $('.processingDaterangeFXTrade span').html(moment().subtract(5, 'years').format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + moment().format('MMMM D, YYYY') + ' &nbsp; <b class="list-icons-item" data-action="collapse"></b>');

    //cancle button
    $('.processingDaterangeFXTrade').on('cancel.daterangepicker', function (ev, picker) {
        //do something, like clearing an input
        $('.processingDaterangeFXTrade span').html(moment().subtract(5, 'years').format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + moment().format('MMMM D, YYYY') + ' &nbsp; <b class="list-icons-item" data-action="collapse"></b>');
        search_start_date = search_end_date = null;
        $('#spinner').show();
        $(table_processing).DataTable().ajax.reload();
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
