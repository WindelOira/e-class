<?php

namespace App\Http\Controllers\API;

use App\Section;
use App\Strand;
use App\Level;
use App\User;
use App\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {
        if( 'published' == $status ) :
            $sections = Section::whereNull('deleted_at')->get();
        else :
            $sections = Section::onlyTrashed()->get();
        endif;
        
        return response()->json([
            'response'      => $sections
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
        $section = Section::create($request->except('students'));
        
        if( 0 < count($request->input('students')) ) :
            foreach( $request->input('students') as $student ) :
                $student = Student::find($student);
                $student->update([ 'section_id' => $section->id ]);
            endforeach;
        endif;

        return response()->json([
            'response'      => $section
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
        $section = Section::onlyTrashed()->findOrFail($id);
        $section->restore();

        return response()->json([
            'response'      => $section
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param   $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $section = Section::findOrFail($id);

        return response()->json([
            'response'      => [
                'id'                => $section->id,
                'academic_year_id'  => $section->academic_year_id->id,
                'strand_id'         => $section->strand_id->id,
                'level_id'          => $section->level_id->id,
                'user_id'           => $section->user_id->id,
                'name'              => $section->name,
                'students'          => Arr::pluck($section->students, 'id'),
                'grading_sheets'    => $section->grading_sheets
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
        $section = Section::findOrFail($id);
        $section->update($request->except('students'));

        if( 0 < count($section->students) ) : // Disassociate students to section
            foreach( $section->students as $student ) :
                if( !in_array($student->id, $request->input('students')) ) :
                    $student = Student::find($student->id);
                    
                    $student->section()->dissociate()->save();
                endif;
            endforeach;
        endif;

        if( 0 < count($request->input('students')) ) : // Associate students to section
            foreach( $request->input('students') as $student ) :
                $student = Student::find($student);

                $student->section()->associate($section)->save();
            endforeach;
        endif;

        return response()->json([
            'response'      => $section
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
        $section = Section::findOrFail($id);
        if( $section->trashed() ) :
            $section->forceDelete();
        else :
            $section->delete();
        endif;

        return response()->json([
            'response'      => $section
        ], 200);
    }

    /**
     * Get grading sheets.
     * 
     * @param   $id
     * @return  \Illuminate\Http\Response
     */
    public function grading_sheets($id) {
        $section = Section::withTrashed()->findOrFail($id);

        return response()->json([
            'response'      => $section->grading_sheets
        ], 200);
    }

    /**
     * Get sections by academic year.
     * 
     * @param   $id
     * @return  \Illuminate\Http\Response
     */
    public function get_by_academic_year($id) {
        $sections = Section::where('academic_year_id', $id)->get();

        return response()->json([
            'response'      => $sections
        ], 200);
    }
}
