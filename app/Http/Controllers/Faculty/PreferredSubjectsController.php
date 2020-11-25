<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\PreferredSubject;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PreferredSubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PreferredSubject::join('subjects', 'preferred_subjects.subject_id', '=', 'subjects.id')
        ->select('preferred_subjects.id AS id', 'subjects.name', 'subjects.description', 'subjects.lec', 'subjects.lab')
        ->where('user_id', Auth::id())->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['subject' => 'required']);
        $subjects = $request->subject;
        $subjectID = [];
        // INSERT SUBJECT
        DB::beginTransaction();
        try {
            foreach ($subjects as $subject) {
                array_push($subjectID, ['subject_id' => $subject, 'user_id' => Auth::id()]);
            }
            PreferredSubject::insert($subjectID);
            DB::commit();
            return 'done';
        } catch (\Throwable $th) {
            DB::rollback();
            return 'not done';
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->validate($request, ['id' => 'required']);
        return PreferredSubject::where('id', $request->id)->delete();
    }
}
