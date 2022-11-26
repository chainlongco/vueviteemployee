<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Validator;

class UserController extends Controller
{
    public function __invoke() {
        return view('users.index');
    }

    public function restricted() {
        //return view('users.restricted');
        return redirct('/users/restricted');
    }

    public function index() {
        $user = DB::table('users');
        return $user;
    }

    public function logout() {
        Session::forget('user');
        return redirect('/users/login');
    }

    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'    
            ],
        );
        if ($validator->passes()) {
            $user = DB::table('users')->where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                if ($this->isAuthorizedUser($user)) {
                    Session::put('user', $user);
                    return response()->json(['status'=>2, 'user'=>$user]);
                } else {
                    return response()->json(['status'=>3, 'msg'=>'This user is not allowed to access employee information']);
                }
            } else {
                return response()->json(['status'=>1, 'msg'=>'Email and Password not matched!']);
            }
        } else {
            return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
        }
    }

    private function isAuthorizedUser($user) {
        $authorizedUser = false;
        $roles = DB::table('roles')
            ->select('roles.name')
            ->join('role_users', 'role_id', '=', 'roles.id')
            ->where('role_users.user_id', $user->id)
            ->get();
        foreach ($roles as $role) {
            if (($role->name == "Admin") || ($role->name == "Manager")){
                $authorizedUser = true;
            }
        }
        return $authorizedUser;
    }

    public function retrieveUser($id) {
        $user = DB::table('users')->where('id', $id)->first();
        return $user;
    }
}
