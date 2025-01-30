<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\MenuFormRequest;

class MenuController extends Controller
{
    public function __construct(){
        $this->middleware('permission:View Menu',['only' => ['index']]);
        $this->middleware('permission:Create Menu',['only' => ['create','store']]);
        $this->middleware('permission:Edit Menu',['only' => ['edit','update']]);
        $this->middleware('permission:Delete Menu',['only' => ['destroy']]);
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

        $menus = Menu::select('id', 'name')->where('parent_id', 0)->get();
        $roles = Role::select('id', 'name')->get();
        return view('__admins.menu', array_merge(compact('routes', 'menus', 'roles'), sidebarMenuList()));
    }

    public function store(MenuFormRequest $request)
    {
        try {
            DB::beginTransaction();

            $menu = Menu::create($request->validated());

            if ($menu) {
                $menu_roles = DB::table('menu_role')->where('menu_id', $menu->id)->pluck('role_id');

                if(count($request->roles) > 0)
                    $menu->roles()->attach(array_diff($request->roles, $menu_roles->toArray()));

                $data = response()->json(['status' => 'success', 'message' => 'Menu Added successfully']);
            } else {
                $data = response()->json(['status' => 'error', 'message' => 'Menu not created, make sure all fields are filled'], 409);
            }

            DB::commit();
        } catch (Exception $err) {
            DB::rollback();
            $data = response()->json(
                [
                    'status' => 'error',
                    'message' => 'Menu not created, please try again.',
                    'stack' => $err
                ], 500);
        }

        return $data;
    }

    public function getMenuData(Menu $menu)
    {
        $data = $menu->with('roles')
        ->select('id', 'name', 'route', 'description', 'slug');
        return datatables()->eloquent($data)
            ->addColumn('roles', function($data){
                if ($data->roles && count($data->roles) > 0) {
                    return implode(", ", array_column($data->roles->toArray(), "name"));
                }
                return '';
            })
            ->addColumn('action', function($data){
                return '
                    <button type="button" id="'.$data->id.'" class="btn btn-primary btn-sm editMenu"><i class="icon-pencil mr-1"></i>Edit</button>
                    &nbsp;<button type="button" id="'.$data->id.'" class="btn btn-danger btn-sm deleteMenu"><i class="icon-trash mr-1"></i>Delete</button>
                ';
            })
            ->rawColumns(['roles', 'action'])
            ->toJson();
    }

    public function edit($id)
    {
        $getMenu = Menu::with('roles:id,name','parent:id')->where('id', $id)->first();
        return response()->json($getMenu);
    }

    public function update(MenuFormRequest $request, $menu)
    {
        try {
            DB::beginTransaction();

            // $menu = tap(Menu::where('id', $menu))
            // ->update($request->validated())
            // ->first();
            $menu = Menu::where('id', $menu)->first();
            $menu->update($request->validated());

            if ($menu) {
                $menu->roles()->detach();
                $menu->roles()->attach($request->roles);

                $data = response()->json(['status' => 'success', 'message' => 'Menu updated successfully']);
            } else {
                $data = response()->json(['status' => 'error', 'message' => 'Menu not updated, make sure all fields are filled'], 409);
            }

            DB::commit();
        } catch (Exception $err) {
            DB::rollback();
            $data = response()->json([
                'status' => 'error',
                'message' => 'Menu not updated, please try again.',
                'stack' => $err->getMessage()
            ], 500);
        }

        return $data;
    }

    public function destroy($menu)
    {
        $menuInc = Menu::where('id', $menu)->first();
        $menuInc->roles()->detach();
        $deleteMenu = $menuInc->delete();
        if (!$deleteMenu) return  response()->json(['status' => 'error', 'message' => 'Menu was not deleted successfully']);
        return response()->json(['status' => 'success', 'message' => 'Menu was deleted successfully']);
    }

    public function sideMenuList()
    {
        $menus = Menu::with([
                'roles:id,name',
                'children:id,name,route,slug,parent_id',
                'children.roles:id,name'
            ])
            ->select('id', 'name', 'route', 'slug', 'parent_id')
            ->where('parent_id', 0)
            ->get();

        return response()->json(compact('menus'));
    }
}
