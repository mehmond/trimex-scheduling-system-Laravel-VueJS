<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    public function index()
    {
        return Room::join('campuses', 'rooms.campus_id', '=', 'campuses.id')
            ->select('rooms.*', 'campuses.name AS campus_name')
            ->latest()
            ->get();
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'campus_name' => 'required',
            'type' => 'required'
        ]);
        $location = Campus::all()->where('name', $request->campus_name)->pluck('id')->first();
        $room = Room::create([
            'name' => $request->name,
            'campus_id' => $location,
            'type' => $request->type,
        ]);
        $data = [
            'id' => $room->id,
            'name' => $request->name,
            'campus_name' => $request->campus_name,
            'type' => $request->type,
        ];
        return $data;
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'campus_name' => 'required',
            'type' => 'required'
        ]);
        $location = Campus::all()->where('name', $request->campus_name)->pluck('id')->first();

        $data = [
            'name' => $request->name,
            'campus_id' => $location,
            'type' => $request->type
        ];

        $update = Room::where('id', $request->id)->update($data);
        return $update;
    }

    public function destroy(Request $request)
    {
        // validate request
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Room::where('id', $request->id)->delete();
    }
}
