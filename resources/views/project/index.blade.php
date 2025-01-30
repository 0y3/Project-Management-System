@extends('layouts.mainlayout')

@section('title', 'Project')

@push('styles')
@endpush

@section('content')

<!-- Page header -->
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4 class="font-weight-semibold"><i class="icon-arrow-left52 mr-2"></i> Project</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content pt-0">
    <!-- Basic datatable -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Projects</h5>
            <div class="header-elements">
                <span class="badge bg-danger-400 badge-pill" id="project_badge"></span>
                <div class="ml-3">
                    <a href="#" class="btn bg-success" id="addProject"><i class="icon-add mr-2"></i> Add Project</a>
                </div>
            </div>
        </div>

        <table class="table projectTable">
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Tasks</th>
                    <th>Project Manager</th>
                    <th>Team Members</th>
                    <th style="width: 30%;">Description</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- /basic datatable -->

    <!-- Basic modal -->
    <div id="projectModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <form id="projectForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Project</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Project Name<span class="text-danger">*</span></label>
                                    <input id="name" type="text" name="name" class="form-control" placeholder="Project Name" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>State Date<span class="text-danger">*</span></label>
                                    <div class="input-group start_date_div">
										<span class="input-group-prepend">
											<span class="input-group-text"><i class="icon-calendar3"></i></span>
										</span>
                                        <input type="text" name="start_date" id="start_date" class="datepicker form-control" required="required">
									</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date<span class="text-danger">*</span></label>
                                    <div class="input-group end_date_div">
										<span class="input-group-prepend">
											<span class="input-group-text"><i class="icon-calendar3"></i></span>
										</span>
                                        <input type="text" name="end_date" id="end_date" class="datepicker form-control" required="required">
									</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description<span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" class="form-control summernote" placeholder="Description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="project_id" id="project_id">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="button" id="submitprojectForm" class="btn bg-primary projectBtn">Create User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /basic modal -->
</div>
<!-- /content area -->
@endsection

@push('scripts')
{{-- <script src="{{asset('global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('global_assets/js/plugins/forms/selects/select2.min.js')}}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
<script type="module" src="{{asset('js/components/project.js') }}"></script>

<script>

    // Default initialization
    $('.select').select2({
        placeholder: 'Click to load data',
        minimumResultsForSearch: Infinity
    });

    //Select with search
    $('.select-search').select2();

    // resolved to attach select2 to modal-parent
    $('.select-search').select2({
        dropdownParent: $('#projectModal')
    });

    $(function(){
        // $('.summernote').summernote();

        $("#start_date").datepicker({
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            autoclose: true,
            // startDate: 'today',//new Date(), // = today
        }).on("changeDate", function (e) {
            $("#end_date").datepicker('setDate', null);
            let newDate = `${e.date.getFullYear()}-${e.date.getMonth() + 1}-${e.date.getDate()}`;
            $("#end_date").datepicker('setStartDate',newDate);
        });

        $("#end_date").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
    });
    // var setStartDate = new Date();
    // setStartDate.setDate(setStartDate.getDate() - 30);
    // var start_date = setStartDate;

    // var end_date = new Date();
    // document.addEventListener('DOMContentLoaded', function() {
    //     $(".datepicker").datepicker({
    //         dateFormat: "yy-mm-dd",
    //         yearRange: `2017:${(new Date()).getFullYear()}`,
    //         maxDate: new Date()
    //     });

    //     // set date picker default dates
    //     $("#start_date").datepicker("setDate", start_date);
    //     $("#end_date").datepicker("setDate", end_date);

    // });
</script>
@endpush
