<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class PermissionController extends Controller
{

    public function __construct(){
        $this->middleware('permission:View Permission',['only' => ['index']]);
        $this->middleware('permission:Create Permission',['only' => ['create','store']]);
        $this->middleware('permission:Edit Permission',['only' => ['edit','update']]);
        $this->middleware('permission:Delete Permission',['only' => ['destroy']]);
    }

    public function index()
    {
        $routeCollection = Route::getRoutes();
        $routeArray = [];
        foreach ($routeCollection as $route) {
            if ($route->getName() !== null) {
                $routeArray[] = $route->getName();
            }
        }
        $routes = $routeArray;

        $permissions = Permission::get();
        return view('__admins.permission', array_merge(compact('routes', 'permissions'), sidebarMenuList()));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'route' => 'required',
            'description' => 'required',
        ]);
        Permission::create(array_merge($validated, ['guard_name' => 'web']));
        $data = response()->json(['status' => 'success', 'message' => 'Permission Added successfully']);

        return $data;
    }

    public function getData(Permission $permission)
    {
        $data = $permission->select('id', 'name', 'route','description');
        return datatables()->eloquent($data)
            ->addColumn('action', function($data){
                return '
                    <button type="button" id="'.$data->id.'" class="btn btn-primary btn-sm editPermission"><i class="icon-pencil mr-1"></i>Edit</button>
                    &nbsp;<button type="button" id="'.$data->id.'" class="btn btn-danger btn-sm deletePermission"><i class="icon-trash mr-1"></i>Delete</button>
                ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function edit($id)
    {
        $getPermission = Permission::find($id);
        return response()->json($getPermission);
    }

    public function update(Request $request, Permission $permission)
    {

        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name,'.$permission->id,
            'route' => 'required',
            'description' => 'required',
        ]);
        $permission->update($validated);
        return response()->json(['status' => 'success', 'message' => 'Permission updated']);
    }

    public function destroy($id)
    {
        $permission = Permission::find($id);
        $deletePermission = $permission->delete();
        if (!$deletePermission) return  response()->json(['status' => 'error', 'message' => 'Permission was not deleted successfully']);
        return response()->json(['status' => 'success', 'message' => 'Permission was deleted successfully']);
    }
}
