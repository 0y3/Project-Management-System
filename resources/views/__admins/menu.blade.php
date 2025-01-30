@extends('layouts.mainlayout')

@section('title', 'home')

@push('styles')
@endpush

@section('content')

<!-- Page header -->
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4 class="font-weight-semibold"><i class="icon-arrow-left52 mr-2"></i> Menus Setup</h4>
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
            <h5 class="card-title">Create menu</h5>
        </div>

        <form id="menuForm" class="form-validate">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Menu Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter menu name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Menu Group</label>
                            <select name="parent_id" class="form-control select2 select-search" id="parent_id" data-fouc>
                                <option value="">Select Menu Group</option>
                                @foreach($menus as $menu)
                                <option value="{{ $menu['id'] }}">{{ $menu['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
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
                            <label>Description</label>
                            <input type="text" name="description" class="form-control" id="description" placeholder="Enter description" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Roles</label>
                            <select name="roles[]" multiple="multiple" class="form-control select2 select" id="roles" data-fouc required>
                                @foreach($roles as $role)
                                <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-primary submitMenuForm">Submit <i class="icon-paperplane ml-2"></i></button>
                    <div class="updateButtonSection d-none">
                        <input type="hidden" id="menu_id">
                        <button type="button" class="btn btn-danger resetMenuForm">Reset <i class="icon-paperplane ml-2"></i></button>
                        <button type="button" class="btn btn-primary updateMenuForm">Update <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Menu Settings</h5>
        </div>

        <table class="table menuTable">
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Route</th>
                    <th>Roles</th>
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
<script type="module" src="{{asset('js/components/menu.js') }}"></script>

<script  >
    // Default initialization
    $('#roles').select2();

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