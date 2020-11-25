<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function getRoles(){
        return Role::latest()->distinct()->get();
    }

    public function index(){
        $users = DB::table('users')
            ->select('users.*','roles.name', 'roles.id as id_role')
            ->distinct('roles.name')
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->get();
        return $users;
    }

    public function update(Request $request)
    {
        // validate request
       $this->validate($request, [
            'full_name' => 'required',
            'email' => "bail|required|email|unique:users,email,$request->id",
            'password' => 'min:6',
            'name' => 'required'
       ]);
        $role = Role::select('id')->where('name', $request->name)->pluck('id')->first();

        $data = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'role_id' => $role
        ];
        if ($request->password) {
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }
        $user = User::where('id', $request->id)->update($data);
        return $user;
    }
    public function destroy(Request $request)
    {
        // validate request
        $this->validate($request, [
            'id' => 'required',
        ]);
        return User::where('id', $request->id)->delete();
    }
}
