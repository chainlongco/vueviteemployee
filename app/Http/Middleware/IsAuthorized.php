<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class isAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('user')) {
            // Admin and Manager have been verified in isAuthorizedUser of UserController.php
            /*$user = Session::get('user');
            $roles = DB::table('roles')
                ->select('name')
                ->join('role_users', 'role_id', '=', 'roles.id')
                ->where('role_users.user_id', $user->id)
                ->get();
            foreach ($roles as $role) {
                if (($role->name == "Admin") || ($role->name == "Manager")){
                    return $next($request);
                }
            }
            return redirect('users/restricted');*/
            return $next($request);
        } else {
            return redirect('users/restricted');
        }
    }
}
