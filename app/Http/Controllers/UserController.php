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

    public function listUsersWithRoles() {
        $users = DB::select('select oo.*, EXISTS (SELECT * FROM users aa left join `role_users` on aa.id = role_users.user_id where role_users.role_id=1 and aa.id=oo.id) as admin, EXISTS (SELECT * FROM users aa left join `role_users` on aa.id = role_users.user_id where role_users.role_id=2 and aa.id=oo.id) as manager, EXISTS (SELECT * FROM users aa left join `role_users` on aa.id = role_users.user_id where role_users.role_id=3 and aa.id=oo.id) as employee from users oo');
        return $users;
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

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                'name' => 'required|unique:users|max:20',
                'email' => 'required|unique:users|email',
                'password'=>'required'
            ]);
        
        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            if ($user->save()){
                return response()->json(['status'=>1, 'msg'=>'New User has been successfully registered. Please contact Administrator to assign this new user for access control rights like Order History, Customer Information and User Information.']);
            }
        }
        return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
        //return redirect(route('auth.login'))->with('success', 'Model added successfully');
        //return back()->with('success_message', 'Custom Text Updated!');
    }

    public function userDelete($id)
    {
        $user = User::find($id);
        if($user) {
            $message = 'The roles of ' .$user->name .' have been deleted successfully.';
            $user->roles()->detach();
            if ($user->delete()){
                return response()->json(['status'=>1, 'msg'=>$message]);
            }
        }
        return response()->json(['status'=>0, 'msg'=>'Deletion failed.']);
    }

    public function userEdit(Request $request)
    {
        $adminRole = DB::table('roles')->select('id')->where('name', 'Admin')->first();
        $managerRole = DB::table('roles')->select('id')->where('name', 'Manager')->first();
        $employeeRole = DB::table('roles')->select('id')->where('name', 'Employee')->first();
        $user = User::find($request->id);
        if ($user) {
            $message = 'The roles of ' .$user->name .' have been updated successfully.';
            $user->roles()->detach();
            if ($request->admin == 'true') {
                $user->roles()->attach($adminRole);
            }
            if ($request->manager == 'true') {
                $user->roles()->attach($managerRole);
            }
            if ($request->employee == 'true') {
                $user->roles()->attach($employeeRole);
            }
            //return response()->json(['msg'=>'The roles of ' .$user->name .' have been updated successfully.']);
            return response()->json(['status'=>1, 'msg'=>$message]);
        }
        return response()->json(['status'=>0, 'msg'=>'Update failed.']);
        //return response()->json(['status'=>0, 'msg'=>$request->id]);
    }
}
