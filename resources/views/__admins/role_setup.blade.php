@extends('layouts.mainlayout')

@section('title', 'home')

@push('styles')
@endpush

@section('content')

<!-- Page header -->
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4 class="font-weight-semibold"><i class="icon-arrow-left52 mr-2"></i> Create Roles</h4>
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
            <h5 class="card-title">Role Setup</h5>
        </div>

        <form id="roleForm" class="form-validate">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 offset-2">
                        <div class="form-group">
                            <label>Role Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter role name" required>
                        </div>
                    </div>
                    <div class="col-md-3" style="margin-top:27px;">
                        <button type="button" class="btn btn-success submitRoleForm"><i class="icon-add mr-1"> Create</i></button>
                        <div class="updateButtonSection d-none">
                            <input type="hidden" id="role_id">
                            <button type="button" class="btn btn-danger btn-sm resetRoleForm"> <i class="icon-reset mr-1"></i> Reset</button>
                            <button type="button" class="btn btn-primary btn-sm updateRoleForm"><i class="icon-loop mr-1"></i> Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Roles</h5>
        </div>

        <table class="table roleTable">
            <thead>
                <tr>
                    <th width="10">S/N</th>
                    <th width="30">Role</th>
                    <th width="50">Menus</th>
                    <th width="10">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- /basic datatable -->
</div>
<!-- /content area -->

<!-- Basic modal -->
<div id="rolePermissionModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="rolePermissionModalForm" class="modal-body">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-semibold">Update Role Permissions</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        {{-- <div class="col-md-12">
                            <div class="form-group">

                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" checked>
                                        Checked default
                                    </label>
                                </div>

                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input">
                                        Unchecked default
                                    </label>
                                </div>

                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" checked>
                                        Checked default
                                    </label>
                                </div>

                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input">
                                        Unchecked default
                                    </label>
                                </div>

                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" checked>
                                        Checked default
                                    </label>
                                </div>

                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input">
                                        Unchecked default
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" checked>
                                        Checked default
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" name="role_id" id="role_id">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" id="submitRolePermissionForm" class="btn bg-primary rolePermissionBtn">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- /basic modal -->
@endsection

@push('scripts')
<script src="{{asset('global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script type="module" src="{{asset('js/components/role_setup.js') }}"></script>
<script  >

</script>
@endpush
