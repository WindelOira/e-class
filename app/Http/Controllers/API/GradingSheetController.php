<?php

namespace App\Http\Controllers\API;

use App\Grade;
use App\GradingSheet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GradingSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {
        if( 'published' == $status ) :
            $sheets = GradingSheet::whereNull('deleted_at')->get();
        else :
            $sheets = GradingSheet::onlyTrashed()->get();
        endif;

        return response()->json([
            'response'      => $sheets
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
        $data = $request->all();
        $sheet = GradingSheet::create([
            'hps'               => json_encode($data['hps']),
            'level_id'          => $data['level_id'],
            'section_id'        => $data['section_id'],
            'semester'          => $data['semester'],
            'subject_id'        => $data['subject_id'],
            'subject_track_id'  => $data['subject_track_id'],
            'user_id'           => $data['user_id']
        ]);
        $sheet->grades()->saveMany(array_map(function($grade) {
            $grade['performance_task'] = json_encode($grade['performance_task']);
            $grade['quarterly_assessment'] = json_encode($grade['quarterly_assessment']);
            $grade['written_work'] = json_encode($grade['written_work']);
            $grade['student_id'] = $grade['student_id']['id'];

            return new Grade($grade);
        }, $request->input('grades')));

        return response()->json([
            'response'      => $sheet
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
        $sheet = GradingSheet::onlyTrashed()->findOrFail($id);
        $sheet->restore();

        return response()->json([
            'response'      => $sheet
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
        $sheet = GradingSheet::findOrFail($id);

        return response()->json([
            'response'      => [
                'id'                => $sheet->id,
                'user_id'           => $sheet->user_id->id,
                'level_id'          => $sheet->level_id->id,
                'section_id'        => $sheet->section_id->id,
                'subject_id'        => $sheet->subject_id->id,
                'subject_track_id'  => $sheet->subject_track_id->id,
                'semester'          => $sheet->semester,
                'hps'               => $sheet->hps,
                'grades'            => $sheet->grades,
                'approvals'         => $sheet->grading_sheet_approvals,
                'sheet'             => $sheet,
            ]
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
        $data = $request->all();
        $sheet = GradingSheet::findOrFail($id);
        $sheet->update([
            'hps'               => json_encode($data['hps']),
            'level_id'          => $data['level_id'],
            'section_id'        => $data['section_id'],
            'semester'          => $data['semester'],
            'subject_id'        => $data['subject_id'],
            'subject_track_id'  => $data['subject_track_id'],
            'user_id'           => $data['user_id']
        ]);

        foreach( $request->input('grades') as $grd ) :
            $grd['student_id'] = $grd['student_id']['id'];
            $grade = Grade::find($grd['id']);

            $grade->update($grd);
        endforeach;

        return response()->json([
            'response'      => $sheet
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
        $sheet = GradingSheet::withTrashed()->findOrFail($id);
        if( $sheet->trashed() ) :
            $sheet->forceDelete();
            // $sheet->history()->forceDelete();
        else :
            $sheet->delete();
        endif;

        return response()->json([
            'response'      => $sheet
        ], 200);
    }
}
