<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        return Subject::select('id')->where([ ['year_id',  $request->year], ['course_id',  $request->course]])->pluck('id');
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
        ->select('subject_id')->where('user_id', '=' , Auth::id())->pluck('subject_id')->toArray())
        ->get();
    }

    public function index(Request $request){
        //Check if you're logged in 
        if(!Auth::check() && $request->path() != 'login'){
            return redirect('/login');
        }
        if(!Auth::check() && $request->path() == 'login'){
            return view('welcome');
        }
        //You're already logged in .. checking if you're admin
        // if(Gate::denies('login-admin')){ 
        //     return redirect('/login');
        // }
        $user = Auth::user();
        if($request->path() == 'login'){
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

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}
