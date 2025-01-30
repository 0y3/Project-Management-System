@extends('layouts.mainlayout')

@section('title', 'Project Task')

@push('styles')
@endpush

@section('content')

<!-- Page header -->
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        {{-- <div class="page-title">
            <h5><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Breadcrumb</span> - Bottom Condensed Spaced</h5>

            <div class="breadcrumb mt-1">
                <a href="index.html" class="breadcrumb-item py-0">Home</a>
                <a href="components_breadcrumbs.html" class="breadcrumb-item py-0">Components</a>
                <span class="breadcrumb-item py-0 active">Breadcrumbs</span>
            </div>
        </div> --}}
        <div class="page-title">
            <h5>
                <i class="icon-arrow-left52 mr-2"></i>
                <span class="font-weight-semibold">Project Details</span>
            </h5>

                <div class="breadcrumb mt-1">
                    <a href="{{ route('index') }}" class="breadcrumb-item py-0"><i class="icon-home2 mr-2"></i> Home</a>
                    <a href="{{ route('project.index') }}" class="breadcrumb-item py-0">Projects List</a>
                    <span class="breadcrumb-item py-0 active">{{ $project->name }}</span>
                </div>
        </div>


    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content pt-0">
    <!-- Basic datatable -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"> <b>{{ $project->name }}</b></h5>
            <div class="text-muted font-size-sm mb-1">
                {{-- <span class="badge badge-mark border-blue mr-1"></span> --}}
                <i class="icon-calendar2 text-blue mr-1"></i>
                {{ Carbon\Carbon::parse($project->start_date)->format('F d, Y') }} - {{ Carbon\Carbon::parse($project->end_date)->format('F d, Y') }}
            </div>
            @php
                $task = count($project->tasks);
                $task_comp = count($project->taskcomplete);

                $task_per = (100*$task_comp)/$task;

                $task_per = round($task_per) < 1 ? 1 : round($task_per);
                $color = 'bg-danger';
                if($task_per >= 30){$color = 'bg-warning';}
                if($task_per >= 60){$color = 'bg-teal';}
                if($task_per >= 85){$color = 'bg-success';}
            @endphp
            <div class="progress rounded-round" style="height: 1.375rem;">
                <div id="task_per_div" class="progress-bar {{ $color }}" style="width: {{ $task_per}}%">
                    <span class="font-weight-bolder">{{ $task_per}}% Complete</span>
                </div>
            </div>
        </div>

        <div class="card-body">
            <h6 class="font-weight-semibold">Description</h6>
            <blockquote class="blockquote blockquote-bordered py-2 pl-3 mb-0">
                <p class="mb-3">{{ $project->description }}</p>
                <footer class="blockquote-footer">
                    {{ $project->creator->name }}. {{-- <cite title="Source Title">10:39 am</cite> --}}
                </footer>
            </blockquote>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Tasks</h5>
            <div class="header-elements">
                <div class="ml-3">
                    <a href="#" class="btn bg-success" id="addTask"><i class="icon-add mr-2"></i> Add Task</a>
                </div>
            </div>
        </div>

        <table class="table projectTaskTable">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Date</th>
                    <th>Assign To</th>
                    <th>Status</th>
                    <th style="width: 30%;">Description</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- /basic datatable -->

    <!-- Basic modal -->
    <div id="taskModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <form id="projectTaskForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Task</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Task Name<span class="text-danger">*</span></label>
                                    <input id="name" type="text" name="name" class="form-control" placeholder="Task Name" required="required">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Assign To</label>
                                    <select name="assignee_id" class="form-control select2 select" id="assignee_id" required="required">
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
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
                        <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}">
                        <input type="hidden" name="task_id" id="task_id" value="">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="button" id="submitProjectTaskForm" class="btn bg-primary taskBtn">Create Task</button>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
{{-- <script src="{{asset('global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script> --}}
<script src="{{asset('global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
<script type="module" src="{{asset('js/components/task.js') }}"></script>

<script  >
    // Default initialization
    $(function(){

        // $('.summernote').summernote();
        $('#assignee_id').select2({
            placeholder: 'Select a User...',
            allowClear: true
        });

        let project_start_date = '{{ $project->start_date }}';
        let project_end_date = '{{ $project->end_date }}';

        $("#start_date").datepicker({
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            autoclose: true,
            startDate: project_start_date,//new Date(), // = today
            endDate: project_end_date,
        }).on("changeDate", function (e) {
            $("#end_date").datepicker('setDate', null);
            let newDate = `${e.date.getFullYear()}-${e.date.getMonth() + 1}-${e.date.getDate()}`;
            $("#end_date").datepicker('setStartDate',newDate);
        });

        $("#end_date").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            startDate: project_start_date,
            endDate: project_end_date,
        });
    });

</script>
@endpush
