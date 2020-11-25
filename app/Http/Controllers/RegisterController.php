<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'required|min:6'
        ]);
        $password = bcrypt($request->password);
        $role = Role::all()->where('name', 'User')->pluck('id')->first();
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => $password,
            'role_id' => $role
        ]);
        return $user;
        if($request->path() == 'login'){
            return redirect('/');
        }
        return view('/');
    }
}
