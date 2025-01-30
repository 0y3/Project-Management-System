@extends('layouts.mainlayout')

@section('title', 'home')

@push('styles')
@endpush

@section('content')

<!-- Page header -->
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4 class="font-weight-semibold"><i class="icon-arrow-left52 mr-2"></i> Permission Setup</h4>
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
            <h5 class="card-title">Create Permission</h5>
        </div>

        <form id="permissionForm" class="form-validate">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Route Name</label>
                            <select name="route" class="form-control select2 select-search" id="route" data-fouc>
                                <option value="">Select Route</option>
                                @foreach($routes as $route)
                                <option value="{{ $route }}">{{ $route }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Permission Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter permission name" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" id="description" placeholder="Enter description" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-primary submitPermissionForm">Submit <i class="icon-paperplane ml-2"></i></button>
                    <div class="updateButtonSection d-none">
                        <input type="hidden" id="permission_id">
                        <button type="button" class="btn btn-danger resetPermissionForm">Reset <i class="icon-paperplane ml-2"></i></button>
                        <button type="button" class="btn btn-primary updatePermissionForm">Update <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Permission Settings</h5>
        </div>

        <table class="table permissionTable">
            <thead>
                <tr>
                    <th>Permission</th>
                    <th>Route</th>
                    <th>Description</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- /basic datatable -->
</div>
<!-- /content area -->
@endsection

@push('scripts')
<script src="{{asset('global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
<script type="module" src="{{asset('js/components/permission.js') }}"></script>

<script>

    $('#parent_id').select2({
        placeholder: 'Select a parent...',
        allowClear: true
    });

    $('#route').select2({
        placeholder: 'Select route...',
        allowClear: true
    });
</script>
@endpush
