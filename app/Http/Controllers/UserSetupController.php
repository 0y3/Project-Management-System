<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Notifications\SendAccountDetailsNotification;

class UserSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('permission:View User',['only' => ['index']]);
        $this->middleware('permission:Create User',['only' => ['create','store']]);
        $this->middleware('permission:Edit User',['only' => ['edit','update']]);
        $this->middleware('permission:Delete User',['only' => ['destroy']]);
    }
    public function index()
    {
        $roles = Role::select('id', 'name')->get();

        return view('__admins.user_setup', array_merge(compact('roles'), sidebarMenuList()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            // Generate 8 length password
            $strRandom = Str::random(8);
            $hashed_random_password = Hash::make($strRandom);

            $storeUser = $user->create(array_merge(
                $request->validated(),
                ['password' => $hashed_random_password],
            ));

            // Assign Role
            $storeUser->assignRole($request->roles);

            // Send account details to user
            try {
                $storeUser->notify(new SendAccountDetailsNotification($storeUser, $strRandom));
            } catch (\Throwable $th) {
                //throw $th;
            }

            $data = response()->json([
                'status' => 'success',
                'message' => 'User created successfully'
            ], 201);

            DB::commit();
        } catch (Exception $err) {
            DB::rollback();
            $data = response()->json([
                'status' => 'error',
                'message' => 'User not created.',
                'stack' => $err
            ], 500);
        }

        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, User $user)
    {
        $userData = $user->with(['roles:id,name'])
            ->where('id', $id)
            ->first();
        return response()->json(['status' => 'success', 'user' => $userData]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request,$id)
    {
        try {
            DB::beginTransaction();

            $user = User::where('id', $id)->first();
            $user->update($request->validated());

            if ($user) {
                // Assign Role
                if ($request->filled('roles')) {
                    $user->roles()->detach();
                    $user->assignRole($request->roles);
                }
            }

            $data = response()->json([
                'status' => 'success',
                'message' => 'User created successfully'
            ], 201);

            DB::commit();
        } catch (Exception $err) {
            DB::rollback();
            $data = response()->json([
                'status' => 'error',
                'message' => 'User not Updated.',
                'stack' => $err
            ], 500);
        }

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_setup, User $user)
    {
        $userInst = $user->where('id', $user_setup)->first();
        $userInst->roles()->detach();
        $userInst->banks()->detach();
        $deleteUser = $userInst->delete();

        if (!$deleteUser) return  response()->json(['status' => 'error', 'message' => 'User was not deleted successfully']);

        return response()->json(['status' => 'success', 'message' => 'User was deleted successfully']);
    }

    public function getUserData(User $user)
    {
        $data = $user->with(['roles:id,name'])->select('id', 'name', 'email');
// dd($data->get()->toArray());
        return datatables()->eloquent($data)
            ->addColumn('roles', function($data){
                if ($data->roles && count($data->roles) > 0) {
                    return implode(", ", array_column($data->roles->toArray(), "name"));
                }
                return '';
            })
            ->addColumn('action', function ($data) {
                $delete = '';
                if(auth()->user()->id != $data->id) $delete ='<a href="javascript:void(0);" id="' . $data->id . '" class="dropdown-item deleteUser"><i class="icon-trash mr-1"></i>Delete</a>';
                return '
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0);" id="' . $data->id . '" class="dropdown-item editUser"><i class="icon-pencil mr-1"></i>Edit</a>'.
                                $delete
                                .'<div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" id="' . $data->id . '" class="dropdown-item resendEmail" ><i class="icon-mailbox mr-1"></i>Resend Email</a>
                            </div>
                        </div>
                    </div>
                ';
            })
            ->rawColumns(['roles', 'action'])
            ->toJson();
    }




    public function resendUserActivationEmailNotification($id)
    {
        $user = User::find($id);
        // Generate 8 length password
        $strRandom = Str::random(8);
        $hashed_random_password = Hash::make($strRandom);

        //save new password
        $user->password = $hashed_random_password;
        $user->save();

        // Send account details to user
        $user->notify(new SendAccountDetailsNotification($user, $strRandom));

        return response()->json(['status' => 200, 'data' => 'email (' . $user->email . ') sent to ' . $user->name]);
    }

    public function show($id)
    {
        // Implement the show() method here
    }
}
