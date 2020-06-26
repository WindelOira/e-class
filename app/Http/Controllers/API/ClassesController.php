<?php

namespace App\Http\Controllers\API;

use App\Classes;
use App\Track;
use App\Subject;
use App\Section;
use App\Strand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {
        if( 'published' == $status ) :
            $classes = Classes::whereNull('deleted_at')->get();
        else :
            $classes = Classes::onlyTrashed()->get();
        endif;

        return response()->json([
            'response'      => $classes
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
        $user = Auth::user();
        if( $user && !in_array($user->role, ['administrator', 'subject-teacher']) )
            return response()->json([
                'response'      => 'Unauthorised'
            ], 401);

        $class = Classes::create($request->all());

        return response()->json([
            'response'      => $class
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
        $class = Classes::onlyTrashed()->findOrFail($id);
        $class->restore();

        return response()->json([
            'response'      => $class
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
        $class = Classes::findOrFail($id);

        return response()->json([
            'response'      => [
                'id'                => $class->id,
                'academic_year_id'  => $class->academic_year_id->id,
                'section_id'        => $class->section_id->id,
                'strand_id'         => $class->strand_id->id,
                'subject_id'        => $class->subject_id->id,
                'track_id'          => $class->track_id->id,
                'class_id'          => $class->class_id,
                'hours'             => $class->hours,
                'semester'          => $class->semester
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
        $user = Auth::user();
        if( $user && !in_array($user->role, ['administrator', 'subject-teacher']) )
            return response()->json([
                'response'      => 'Unauthorised'
            ], 401);
            
        // $input = $request->all();
        
        $class = Classes::findOrFail($id);
        $class->update($request->all());

        return response()->json([
            'response'      => ''
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
        $user = Auth::user();
        if( $user && !in_array($user->role, ['administrator', 'subject-teacher']) )
            return response()->json([
                'response'      => 'Unauthorised'
            ], 401);
            
        $class = Classes::withTrashed()->findOrFail($id);
        if( $class->trashed() ) :
            $class->forceDelete();
        else :
            $class->delete();
        endif;

        return response()->json([
            'response'      => $class
        ], 200);
    }
}
