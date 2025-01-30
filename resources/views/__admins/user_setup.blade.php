@extends('layouts.mainlayout')

@section('title', 'User Setup')

@push('styles')
@endpush

@section('content')

<!-- Page header -->
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4 class="font-weight-semibold"><i class="icon-arrow-left52 mr-2"></i> Users Setup</h4>
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
            <h5 class="card-title"></h5>
            <div class="header-elements">
                <div class="ml-3">
                    <a href="#" class="btn bg-success" id="addUser"><i class="icon-add mr-2"></i> Add User</a>
                </div>
            </div>
        </div>

        <table class="table userSetupTable">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- /basic datatable -->

    <!-- Basic modal -->
    <div id="userSetupModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <form id="userSetupForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create User</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter Full Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Roles <span class="text-danger">*</span></label>
                                    <select name="roles[]" multiple="multiple" class="form-control select2 select " id="roles" data-fouc required>
                                        @foreach($roles as $role)
                                        <option value="{{ $role['name'] }}">{{ $role['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="user_id" id="user_id">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="button" id="submitUserSetupForm" class="btn bg-primary userSetupBtn">Create User</button>
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
<script type="module" src="{{asset('js/components/user_setup.js') }}"></script>

<script  >

    // Default initialization
    $('.select').select2({
        placeholder: 'Click to load data',
        minimumResultsForSearch: Infinity
    });

    //Select with search
    $('.select-search').select2();

    // resolved to attach select2 to modal-parent
    $('.select-search').select2({
        dropdownParent: $('#userSetupModal')
    });
</script>
@endpush
