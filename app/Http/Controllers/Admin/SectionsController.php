<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Section::latest()->get();
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
            'sem' => 'required',
            'ay' => 'required',
            'subject' => 'required',
            'course' => 'required',
            'year' => 'required',
            'time' => 'required',
            'day' => 'required'
        ]);
        $subjects = $request->subject;
        $times = $request->time;
        $total_time = count($times);
        $subjectID = [];
        foreach ($subjects as $subject){
            $subject = Subject::where('id', $subject)->pluck('id');
            $type = Subject::where('id', $subject)->pluck('type');
            array_push($subjectID, [$subject, $type]);
        }
        

        return $total_time;
        // $this->validate($request, [
        //     'name' => 'required',
        //     'subject' => 'required',
        //     'course' => 'required',
        //     'year' => 'required',
        // ]);
        // $subjects = $request->subject;
        // $subjectID = [];
        // DB::beginTransaction();
        // try {
        //     $section = Section::create([
        //         'name' => $request->name,
        //     ]);
        //     // INSERT SUBJECT
        //     foreach ($subjects as $subject) {
        //         array_push($subjectID, ['subject_id' => $subject, 'section_id' => $section->id, 'year_id' => $request->year, 'course_id' => $request->course]);
        //     }
        //     DB::table('section_subjects')->insert($subjectID);
        //     DB::commit();
        //     $data = ['id'=> $section->id, 'name' => $request->name];
        //     return $data;
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return 'not done';
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return Section::with(['subject', 'course', 'year'])->where('id', $id)->first();
        return DB::table('section_subjects')
        ->join('sections', 'section_subjects.section_id', '=', 'sections.id')
        ->join('subjects', 'section_subjects.subject_id', '=', 'subjects.id')
        ->join('courses', 'section_subjects.course_id', '=', 'courses.id')
        ->join('years', 'section_subjects.year_id', '=', 'years.id')
        ->select('sections.name AS section', 'subjects.description AS description', 'subjects.name AS subject', 
        'subjects.id', 'subjects.lec AS lec', 'subjects.lab AS lab', 'courses.name AS course', 'years.name AS year')
        ->distinct()
        ->where('section_id', $id)
        ->get();
    }



    public function edit($id){
        return Section::with('subject', 'course' , 'year')->where('id', $id)->first();
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
        $this->validate($request, [
            'name' => 'required',
            'subject_id' => 'required',
            'course_id' => 'required',
            'year_id' => 'required',
        ]);
        $subjects = $request->subject_id;
        $subjectID = [];
        DB::beginTransaction();
        try {
             Section::where('id', $id)->update([ 'name' => $request->name ]);
            // INSERT SUBJECT
            foreach ($subjects as $subject) {
                array_push($subjectID, ['subject_id' => $subject, 'section_id' => $id , 'year_id' => $request->year_id, 'course_id' => $request->course_id]);
            }
            DB::table('section_subjects')->where('section_id', $id)->delete();
            DB::table('section_subjects')->insert($subjectID);
            DB::commit();
            return 'done';
        } catch (\Throwable $th) {
            DB::rollback();
            return 'not done';
        }
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
        return Section::where('id', $request->id)->delete();
    }
}
