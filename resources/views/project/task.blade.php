@extends('layouts.mainlayout')

@section('title', 'My Task')

@push('styles')
@endpush

@section('content')

<!-- Page header -->
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">

        <div class="page-title d-flex">
            <h4 class="font-weight-semibold"><i class="icon-arrow-left52 mr-2"></i> My Task</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        <!-- <div class="header-elements d-none py-0 mb-3 mb-md-0">
            <div class="breadcrumb">
                <a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                <span class="breadcrumb-item active">Purposes</span>
            </div>
        </div> -->

    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content pt-0">
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-blue-400 has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0 db_total_task">0</h3>
                        <span class="text-uppercase font-size-xs">Total Tasks</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-task icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-success-400 has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-stack-check icon-3x opacity-75"></i>
                    </div>

                    <div class="media-body text-right">
                        <h3 class="mb-0 db_comp_task">0</h3>
                        <span class="text-uppercase font-size-xs">Completed Task</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-danger-400 has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0 db_over_task">0</h3>
                        <span class="text-uppercase font-size-xs">OverDue Task</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-enter6 icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-indigo-400 has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-drawer3 icon-3x opacity-75"></i>
                    </div>
                    <div class="media-body text-right">
                        <h3 class="mb-0 db_inpro_task">0</h3>
                        <span class="text-uppercase font-size-xs">In progress Task</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">

            <div class="card">
                <div class="card-header header-elements-sm-inline">
                    <h6 class="card-title">Tasks</h6>
                </div>
                <div class="card-body">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="sourceTable" class="table taskTable">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Project</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th style="width: 30%;">Description</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>
<!-- /content area -->
@endsection

@push('scripts')
{{-- <script src="{{asset('global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script> --}}
{{-- <script src="{{asset('global_assets/js/plugins/forms/selects/select2.min.js')}}"></script> --}}
{{-- <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script> --}}

<script >
    var table;
    let thisData = [];
    let idToEdit = '';

    $(document).ready(function() {
        TaskDataTable()

    });

    function TaskDataTable() {
        $('#spinner').show();
        table = $('.taskTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `/task/all-data/`,
                type: 'GET',
                complete: function (data) {
                    // console.log('task', data, data.responseJSON.recordsTotal);
                    $('.db_total_task').text(data.responseJSON.recordsTotal);
                    getTaskDetailsCount();
                    $('#spinner').hide();
                },
                "error": function (xhr, error, thrown) {
                }
            },
            autoWidth: false,
            dom: '<"datatable-header"fBl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' },
                processing: '<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>Processing...</div>'
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
            columns: [
                { data: 'name' },
                { data: 'project.name' },
                { data: 'start_date' },
                { data: 'status', name: 'status' },
                { data: 'description', name: 'description' },
                { data: 'action', name: 'action', searchable: false, orderable: false }
            ],
            columnDefs: [
                {
                    targets: [0],
                    render: function (data, type, row) {
                        return `<span class="font-weight-semibold">${data}</span>`;
                    }
                },
                {
                    targets: [1],
                    render: function (data, type, row) {
                        return `<span class="font-weight-semibold">${data}</span>`;
                    }
                },
                {
                    targets: [2],
                    render: function (data, type, row) {
                        return `
                        <div class="d-inline-flex align-items-center">
                            <i class="icon-calendar2 mr-2"></i>
                            ${ moment(data).format("MMM Do, YYYY")} <br>-<br> ${ moment(row.end_date).format("MMM Do, YYYY")}
                        </div>
                        `;
                    }
                },
                {
                    targets: [3],
                    render: function (data, type, row) {
                        let status = data=='completed'?`<span class="badge bg-success-400">${data}</span>`:
                        (row.daysRemain < 1 )?'<span class="badge bg-danger-400">Overdue</span>':
                        `<span class="badge bg-warning-400">${data??''}</span>`;
                        return status;
                    }
                },
                {
                    targets: [4],
                    render: function (data, type, row) {
                        return `<span class="text-muted">${data??''}</span>`;
                    }
                },
            ],
            createdRow: function (row, data, dataIndex) {
                if (data['daysRemain'] < 1  && data['status'] == "in progress" ) {
                    $(row).addClass('table-danger')
                }
            },
        });
    }

    // completed ProjectTask
    $(document).on('click', '.completedProjectTask', function () {
        let id = $(this).attr('id');
        Swal.fire({
            type: 'warning',
            title: "Complete Task Request!",
            html: `Did you want to proceed to complete this Task ?`,
            showCancelButton: true,
            confirmButtonText: `Yes, Completed this Task`,
            cancelButtonColor: '#d33',
            confirmButtonColor: "#4CAF50",
            reverseButtons: true
        }).then((result) => {
            if (result.value == true) {
                $('#spinner').show();

                $.post(`/project/task/complete/${id}`,{_token:$('meta[name="csrf-token"]').attr('content')}, function(data, status) {
                    if(data.status == "success"){
                        $(table).DataTable().ajax.reload();
                        Swal.fire(
                            data.status,
                            data.message,
                            data.status
                        );
                    }

                }).fail(function(e, status, error){
                    // Handle the error
                    console.error("Error fetching data: ", status, error);
                    Swal.fire(
                        e.status,
                        e.message,
                        e.status
                    );

                }).always(function() {
                    $("#spinner").hide();
                });
            }
        });
    });

    function getTaskDetailsCount() {
        $.get(`/taskcount`, function(data, status) {
            if(data.status == "success"){
                $('.db_comp_task').text(data.data.completeTask)
                $('.db_over_task').text(data.data.overdueTask)
                $('.db_inpro_task').text(data.data.inproTask)
                // $('.db_comp_task').text(data.data.completeTasksPer)
            }
        })
    }


</script>

@endpush
