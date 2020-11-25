<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\PreferredTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PreferredTimesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PreferredTime::join('days', 'preferred_times.day_id', '=', 'days.id')
        ->join('times', 'preferred_times.time_id', '=', 'times.id')
        ->select('preferred_times.id', 'days.name AS name', 'times.name AS time')
        ->where('user_id', Auth::id())->orderBy('name', 'asc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [ 'time' => 'required', 'day' => 'required' ]);
        $times = $request->time;
        $timeID = [];
        // INSERT SUBJECT
        DB::beginTransaction();
        try {
            foreach ($times as $time) {
                array_push($timeID, ['time_id' => $time, 'user_id' => Auth::id(), 'day_id' => $request->day]);
            }
            PreferredTime::insert($timeID);
            DB::commit();
            return 'done';
        } catch (\Throwable $th) {
            DB::rollback();
            return 'not done';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        return PreferredTime::where('id', $request->id)->delete();
    }
}
