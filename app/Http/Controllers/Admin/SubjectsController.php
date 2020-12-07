<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Subject::latest()->paginate($request->total);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'lec' => 'required',
            'lab' => 'required',
            'type' => 'required',
            'total_time' => 'required'
        ]);

        return Subject::create([
            'name' => $request->name,
            'descriptrion' => $request->description,
            'lec' => $request->lec,
            'lab' => $request->lab,
            'type' => $request->type,
            'total_time' => $request->total_time
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'lec' => 'required',
            'lab' => 'required',
            'type' => 'required',
            'total_time' => 'required'
        ]);

        
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'lec' => $request->lec,
            'lab' => $request->lab,
            'type' => $request->type,
            'total_time' => $request->total_time
        ];

        return Subject::where('id', $request->id)->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // validate request
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Subject::where('id', $request->id)->delete();
    }
}
