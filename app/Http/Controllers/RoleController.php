<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\Role as ModelsRole;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Http;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('permission:View Role',['only' => ['index']]);
        $this->middleware('permission:Create Role',['only' => ['create','store']]);
        $this->middleware('permission:Edit Role',['only' => ['edit','update']]);
        $this->middleware('permission:Delete Role',['only' => ['destroy']]);
    }
    public function index()
    {
        return view('__admins.role_setup', sidebarMenuList());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|unique:roles,name']);
        Role::create(array_merge($validated, ['guard_name' => 'web']));
        return response()->json(['status' => 'success', 'message' => 'Role created']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::where('id', $id)->first();
        return response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate(['name' => 'required|unique:roles,name,'.$role->id,]);
        $role->update($validated);
        return response()->json(['status' => 'success', 'message' => 'Role updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role, ModelsRole $modelsRole)
    {
        $role->users()->detach();
        $modelsRole->menus()->detach();
        $deleteRole = $role->delete();
        if (!$deleteRole) return  response()->json(['status' => 'error', 'message' => 'Role was not deleted successfully']);
        return response()->json(['status' => 'success', 'message' => 'Role was deleted successfully']);
    }

    public function getRoles()
    {
        $data = ModelsRole::with('menus:id,name')->select('name', 'id');

        return datatables()->eloquent($data)
            ->addColumn('menus', function($data){
                if ($data->menus && count($data->menus) > 0) {
                    return implode(", ", array_column($data->menus->toArray(), "name"));
                }
                return '';
            })
            ->addColumn('action', function($data){
                return '
                    <button type="button" id="'.$data->id.'" class="btn btn-primary btn-sm editRole"><i class="icon-pencil mr-1"></i>Edit</button>
                    &nbsp;<button type="button" id="'.$data->id.'" class="btn btn-infOo btn-sm" data-id="'.$data->id.'" data-name="'.$data->name.'" data-toggle="modal" data-target="#rolePermissionModal"><i class="icon-cog3 icon-2xX text-blue-400 opacity-75 mr-1"></i>Permissions</button>
                    ';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->toJson();
    }

    public function addPermissionToRole($role_id){
        $role = Role::findorFail($role_id);
        $role_permission = DB::table('role_has_permissions')->where('role_id',$role_id)->pluck('permission_id');
        $permissions = Permission::orderBy('name')->get();
        return response()->json(['role' => $role, 'permissions' => $permissions,'role_permission' => $role_permission,]);

    }

    public function storePermissionToRole(Request $request, $role_id){
        $validated = $request->validate(['permissions' => 'required']);
        $role = Role::findorFail($role_id);
        $role->syncPermissions($request->permissions);
        return response()->json(['status' => 'success', 'message' => 'Permissions Added to Role']);

    }
}
