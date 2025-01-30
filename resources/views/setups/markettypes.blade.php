@extends('layouts.mainlayout')

@section('title', 'markettypes')

@push('styles')
@endpush

@section('content')

<!-- Page header -->
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">

        <div class="page-title d-flex">
            <h4 class="font-weight-semibold"><i class="icon-arrow-left52 mr-2"></i> Market Types Setup</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        <!-- <div class="header-elements d-none py-0 mb-3 mb-md-0">
            <div class="breadcrumb">
                <a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                <span class="breadcrumb-item active">Market types</span>
            </div>
        </div> -->

    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content pt-0">
    <div class="row">
        <div class="col-xl-12">

            <div class="card">
                <div class="card-header header-elements-sm-inline">
                    <h6 class="card-title">Market Types</h6>
                    <div class="header-elements">
                        <form id="newData_form" class="row">
                            <div class="">
                                <input id="fieldValue" type="text" class="form-control" placeholder="enter new market type">
                            </div>
                            <button type="submit" class="btn btn-primary ml-3 "> <i class="icon icon-plus2"></i> Add
                                New</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="sourceTable" class="table sourceTable">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>

        </div>
    </div>


    <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="fxTradeModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h6>Edit</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit_form" class="row">
                        <div class="col-12">
                            <textarea class="form-control" id="editField" rows="3"></textarea>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary w-100 "> Save</button>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>




</div>
<!-- /content area -->
@endsection

@push('scripts')
{{--
<script src="{{asset('global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script> --}}
{{--
<script src="{{asset('global_assets/js/plugins/forms/selects/select2.min.js')}}"></script> --}}
{{--
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css"
    rel="stylesheet" />
<script type="text/javascript"
    src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script> --}}




<script >
    var table = "";
    let thisData = [];
    let idToEdit = '';

    $(document).ready(function() {
        getData()

    });


    function getData() {
        $('#spinner').show();
        $.ajax({
            method: 'GET',
            dataType: "json",
            url: '/setup/get_markettypes',
            success: function(data, status, xhr) {
                thisData = data.data;

                let objToShow = [];
                thisData.forEach((element, index) => {
                    objToShow.push([
                        (index + 1),
                        element.name,
                        `<button onclick="editRecord(${element.id})" class="btn btn-link text-primary m-0 p-0">
                        <i class="icon icon-pencil"></i> edit
                        </button>`,
                        `<button onclick="deleteRecord(${element.id})" class="btn btn-link text-danger m-0 p-0">
                        <i class="icon icon-x"></i> delete
                        </button>`
                    ])
                });

                if (table !== "")
                    table.destroy();
                table = $('#sourceTable').DataTable({
                    data: objToShow,
                    dom: '<"datatable-header"fBl><"datatable-scroll"t><"datatable-footer"ip>',
                    scrollCollapse: true,
                    columnDefs: [{
                        targets: [2, 3],
                        orderable: false
                    }],
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
                    }

                })
                $('#spinner').hide();
            },
            error: function(data) {
                console.log('Error:', data);
                $('#spinner').hide();
            }
        });
    }


    $("#newData_form").submit(function(event) {
        event.preventDefault();
        let fieldValue = $("#fieldValue").val();
        if (fieldValue.length == 0) {
            return false;
        }
        $.ajax({
            type: 'post',
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                name: fieldValue
            },
            url: `/setup/store_markettype`,
            success: function(data, status, xhr) {
                getData()
                Swal.fire({
                    toast: true,
                    text: 'Record Added',
                    showConfirmButton: false,
                    position: 'top-end',
                    timer: 1500,
                    timerProgressBar: false,
                    icon: 'success'

                })
                $("#fieldValue").val('')

            },
            error: function(data) {
                console.log('Error:', data);
            }
        })

    });




    function editRecord(id) {
        let finder = thisData.find(x => x.id == id);
        idToEdit = id;
        $('#editField').val(finder.name)
        $('#editModal').modal('show')
    }

    $("#edit_form").submit(function(event) {
        event.preventDefault();
        let editField = $("#editField").val();
        if (editField.length == 0) {
            return false;
        }
        $.ajax({
            type: 'post',
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                name: editField
            },
            url: `/setup/update_markettype/${idToEdit}`,
            success: function(data, status, xhr) {
                if (data == '-1') {
                    Swal.fire({
                        toast: true,
                        text: 'Already Exists',
                        showConfirmButton: false,
                        position: 'top-end',
                        timer: 1500,
                        timerProgressBar: false,
                        icon: 'warning'

                    })
                    return false;
                }
                getData()
                Swal.fire({
                    toast: true,
                    text: 'Record Updated',
                    showConfirmButton: false,
                    position: 'top-end',
                    timer: 1500,
                    timerProgressBar: false,
                    icon: 'success'

                })
                $("#editField").val('')
                $('#editModal').modal('hide')

            },
            error: function(data) {
                console.log('Error:', data);
                $('#editModal').modal('hide')
            }
        })

    });



    function deleteRecord(id) {
        Swal.fire({
            title: 'Do you want to Delete?',
            text: 'This action is irreversible',
            showCancelButton: true,
            confirmButtonText: `Confirm Delete`,
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: 'GET',
                    dataType: "json",
                    url: `/setup/delete_markettype/${id}`,
                    success: function(data, status, xhr) {
                        getData()
                        Swal.fire({
                            toast: true,
                            text: 'Record Deleted',
                            showConfirmButton: false,
                            position: 'top-right',
                            timer: 1500,
                            timerProgressBar: false,
                            icon: 'success'

                        })
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                })
            }
        })
    }
</script>

@endpush