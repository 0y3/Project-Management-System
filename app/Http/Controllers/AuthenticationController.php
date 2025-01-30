<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Http;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request){

        if (auth()->attempt($request->validated())) {
            $request->session()->regenerate();
            return response()->json([
                'status' => 'success',
                'user' => UserResource::make(auth()->user()),
            ],200);
        }
        else{
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
        }
    }

    public function logout(){
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->to('/');
    }

    public function recoverPassword(Request $request)
    {
        $data = Http::post(config('app.api_url').'forgot-password', $request->all())->json();
        return response()->json($data, $data['status'] == 'error' ? 401 : 200);
    }

    public function changeCurrentPassword()
    {
        $newToken = request()->query('input_data');
        return view('__users.authentications.change_Password', compact('newToken'));
    }

    public function newPassword(Request $request)
    {
        $data = Http::patch(config('app.api_url').'new-password', $request->all())->json();
        return response()->json($data, $data['status'] == 'error' ? 500 : 200);
    }

    public function changePassword(Request $request)
    {
        $data = Http::withToken(session('token'))
        ->patch(config('app.api_url').'change-password', $request->all())->json();
        return response()->json($data, $data['status'] == 'error' ? 500 : 200);
    }

    public function uploadAvatar(Request $request)
    {
        $fileStore = fileUpload($request, 'user_avatar', 'images');
        $request->merge(['avatar' => $fileStore]);

        $data = Http::withToken(session('token'))
        ->patch(config('app.api_url').'upload-avatar', $request->all())->json();

        return response()->json($data, $data['status'] == 'error' ? 500 : 200);
    }

    public function getUserAvatar()
    {
        $data = Http::withToken(session('token'))
            ->get(config('app.api_url').'user/profile-image')->json();

        // update user session
        getAndSetUserSession();

        return response()->json($data, $data['status'] == 'error' ? 401 : 200);
    }

    public function show2faForm()
    {
        $data = Http::withToken(session('token'))
            ->get(config('app.api_url').'two-factor-authentication')->json();

        if ($data['data']['user']['login_security'] !== null) {
            // If 2FA enabled, redirect to verify OTP
            if ($data['data']['user']['login_security']['google2fa_enable'])
                return view('__users.authentications.2fa_verify');
        }

        return view('__users.authentications.2fa_settings', array_merge($data));
    }

    public function generate2faSecret(Request $request)
    {
        $data = Http::withToken(session('token'))
            ->post(config('app.api_url').'generate-secret-key', $request->all())->json();

        return view('__users.authentications.2fa_settings', array_merge($data, sidebarMenuList()));
    }

    public function enableTwoFactor(Request $request)
    {
        $data = Http::withToken(session('token'))
            ->post(config('app.api_url').'enable-two-factor', $request->all())->json();

        if ($data['status'] == 'success') {
            session(['google2fa_active' => true]);
            return redirect()->to('/dashboard');
        }

        return view('__users.authentications.2fa_settings', array_merge($data, sidebarMenuList()));
    }

    public function verifyTwoFactor(Request $request)
    {
        $data = Http::withToken(session('token'))
            ->post(config('app.api_url').'two-factor-verification', $request->all())->json();

        if ($data['status'] == 'success') {
            // Store a piece of data in the session...
            session(['google2fa_active' => true]);
            return redirect()->to('/dashboard');
        }

        return view('__users.authentications.2fa_verify', compact('data'));
    }
}
