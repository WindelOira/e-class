<?php

namespace App\Http\Controllers\API;

use App\Attendance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param   $status
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {
        if( 'published' == $status ) :
            $attendances = Attendance::whereNull('deleted_at')->get();
        else :
            $attendances = Attendance::onlyTrashed()->get();
        endif;

        return response()->json([
            'response'      => $attendances
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attendance = Attendance::create([
            'date_time'     => $request->input('date_time')
        ]);
        $attendance->students()->attach($request->input('present'));

        return response()->json([
            'response'      => $attendance
        ], 200);
    }

    /**
     * Restore the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $attendance = Attendance::onlyTrashed()->findOrFail($id);
        $attendance->restore();

        return response()->json([
            'response'      => $attendance
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->date = date('Y-m-d', strtotime($attendance->date_time));
        $attendance->time = date('H:i:s', strtotime($attendance->date_time));
        $attendance->present = Arr::pluck($attendance->students, 'id');

        return response()->json([
            'response'      => $attendance
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->date_time = $request->input('date_time');
        $attendance->students()->sync($request->input('present'));
        $attendance->save();

        return response()->json([
            'response'      => $attendance
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attendance = Attendance::withTrashed()->findOrFail($id);
        if( $attendance->trashed() ) :
            $attendance->forceDelete();
            $attendance->history()->forceDelete();
        else :
            $attendance->delete();
        endif;

        return response()->json([
            'response'      => $attendance
        ], 200);
    }
}
