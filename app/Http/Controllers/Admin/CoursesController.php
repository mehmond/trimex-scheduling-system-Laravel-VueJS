<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index(){
        return Course::latest()->get();
    }
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required'
        ]);
        
        return Course::create([
            'name' => $request->name
        ]);
    }
    public function update(Request $request){
        $this->validate($request, [
            'name' => 'required'
        ]);
        
        $data = [
            'name' => $request->name
        ];

        $update = Course::where('id', $request->id)->update($data);
        return $update;
    }

    public function destroy(Request $request)
    {
        // validate request
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Course::where('id', $request->id)->delete();
    }
}
