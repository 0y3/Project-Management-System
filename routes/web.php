<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserSetupController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AuthenticationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', function () {
        return view('__users.authentications.login');
    })->name('login')->middleware('guest');



    Route::get('register', function () {
        return view('__users.authentications.signup');
    });

    Route::get('forgot-password', function () {
        return view('__users.authentications.forgot_password');
    });


    Route::post('recover-password', [AuthenticationController::class, 'recoverPassword']);
    Route::get('change-current-password', [AuthenticationController::class, 'changeCurrentPassword']);
    Route::post('new-password', [AuthenticationController::class, 'newPassword']);
    // Post Login
    Route::post('login', [AuthenticationController::class, 'login'])->name('post-login');
});
Route::get('/logout', [AuthenticationController::class, 'logout']);

Route::group(['middleware' => 'auth'], function () {

    Route::get('change-current-password', [AuthenticationController::class, 'changeCurrentPassword'])->name('index');
    Route::get('user/profile', function () {
        return view('__users.authentications.profile', sidebarMenuList());
    });

    Route::group(['prefix' => 'admin'], function () {

        // Menu
        Route::get('/menus/all-data', [MenuController::class, 'getMenuData']);
        Route::get('/menus/list', [MenuController::class, 'sideMenuList']);
        Route::resource('menus', MenuController::class);

        //user Setup
        Route::get('/user-setup/all-data', [UserSetupController::class, 'getUserData']);
        Route::post('/user-setup/{id}/resend-email-notification', [UserSetupController::class, 'resendUserActivationEmailNotification']);
        Route::resource('user-setup', UserSetupController::class);

        // Role
        Route::get('/role/all-data', [RoleController::class, 'getRoles']);
        Route::get('/role/permission/{id}', [RoleController::class, 'addPermissionToRole']);
        Route::post('/role/permission/{id}', [RoleController::class, 'storePermissionToRole']);
        Route::resource('role', RoleController::class);

        //Permission
        Route::get('/permission/all-data', [PermissionController::class, 'getData']);
        Route::resource('permission', PermissionController::class);
    });

    //Project Setup
    Route::get('project/all-data', [ProjectController::class, 'getProjectData'])->name('project.all-data');
    Route::get('project/task/all-data', [ProjectController::class, 'getProjectTaskData'])->name('project.task.all-data');
    Route::get('project/${id}/task', [ProjectController::class, 'projectTaskIndex'])->name('project.task');
    Route::post('project/task', [ProjectController::class, 'storeProjectTask'])->name('project.task.store');
    Route::post('project/task/complete/{id}', [ProjectController::class, 'completeProjectTask'])->name('project.task.complete');
    Route::get('project/task/edit/{id}', [ProjectController::class, 'editProjectTask'])->name('project.task.edit');
    Route::PATCH('project/task/{id}', [ProjectController::class, 'updateProjectTask'])->name('project.task.update');
    Route::DELETE('project/task/delete/{id}', [ProjectController::class, 'destroyProjectTask'])->name('project.task.destroy');
    Route::resource('project', ProjectController::class);

    // Task
    Route::get('/task/all-data', [TaskController::class, 'getProjectTaskData']);
    Route::get('/task', [TaskController::class,'index'])->name('task.index');
    Route::get('/taskcount', [TaskController::class,'taskCount'])->name('taskcount');


});

