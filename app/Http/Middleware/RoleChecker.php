<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$confirmRole)
    {
        // if (!session('token')) return redirect()->route('login');

        // $roles = session('user')['roles'];

        // if (array_intersect($confirmRole, $roles)) {
        //     return $next($request);
        // }

        if (!in_array($request->user()->role->name, $confirmRole)) {
            // return response()->json(['status' => 'warning', 'message' => 'You are not authorized to visit this page'], 401);
            return redirect('/'); // Redirect if the user does not have the required role
        }
        return $next($request);
    }
}
