<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMailable;
use Illuminate\Support\Str;
use App\Models\PasswordReset;

class GenericController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function countUser()
    {
        return DB::table('users')->count();
    }
    public function countSubject()
    {
        return DB::table('subjects')->count();
    }
    public function getSubjects(Request $request)
    {

        $this->validate($request, ['course' => 'required', 'year' => 'required']);
        return Subject::select('id')->where([['year_id',  $request->year], ['course_id',  $request->course]])->pluck('id');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexYear()
    {
        return DB::table('years')->latest()->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexDay()
    {
        return DB::table('days')->latest()->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSubject()
    {
        return DB::table('subjects')
            ->select('id', 'name')
            ->whereNotIn('id', DB::table('preferred_subjects')
                ->select('subject_id')->where('user_id', '=', Auth::id())->pluck('subject_id')->toArray())
            ->get();
    }

    public function index(Request $request)
    {
        //Check if you're logged in 
        if (!Auth::check() && $request->path() != 'login') {
            return redirect('/login');
        }
        if (!Auth::check() && $request->path() == 'login') {
            return view('welcome');
        }
        //You're already logged in .. checking if you're admin
        // if(Gate::denies('login-admin')){ 
        //     return redirect('/login');
        // }
        $user = Auth::user();
        if ($request->path() == 'login') {
            return redirect('/');
        }
        return $this->checkForPermission($user, $request);
    }
    public function checkForPermission($user, $request)
    {
        $permission = json_decode($user->role->permission);
        $hasPermission = false;
        if (!$permission) {
            return view('welcome');
        }

        foreach ($permission as $p) {
            if ($p->name == $request->path()) {
                if ($p->read) {
                    $hasPermission = true;
                }
            }
        }
        if ($hasPermission) {
            return view('welcome');
        }

        return view('welcome');
        return view('notfound');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
    public function sendToken(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!isset($user->id)) {
            return response()->json(['error' => 'Email does not exist..'], 401);
        }
        $token = Str::random(28);
        Mail::to($user)->send(new ResetPasswordMailable($token));

        $passwordReset = new PasswordReset();
        $passwordReset->email = $user->email;
        $passwordReset->token = $token;
        $passwordReset->save();
    }

    public function validateToken(Request $request)
    {
        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if(!isset($passwordReset->email)){
            return response()->json(['error' => 'Invalid token..'], 401);
        }

        $user = User::where('email', $passwordReset->email)->first();
        return response()->json($user,200);
    }

    public function resetPassword(Request $request)
    {
        $user = User::find($request->user_id);
        $passwordReset = PasswordReset::where('email', $user->email)->first();
        $passwordReset->delete();

        $user->password = bcrypt($request->password);
        $user->save();
    }
}
