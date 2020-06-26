<?php

namespace App\Http\Controllers\API;

use App\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {
        if( 'published' == $status ) :
            $students = Student::whereNull('deleted_at')->get();
        else :
            $students = Student::onlyTrashed()->get();
        endif;

        return response()->json([
            'response'  => $students
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
        $input = $request->all();

        $student = Student::create([
            'student_number'    => $request->input('student_number'),
            'name'              => $input['metas']['fname'] .' '. $input['metas']['lname']
        ]);

        return response()->json([
            'response'      => $student
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
        $student = Student::onlyTrashed()->findOrFail($id);
        $student->restore();

        return response()->json([
            'response'      => $student
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::findOrFail($id);

        return response()->json([
            'response'      => [
                'id'                => $student->id,
                'student_number'    => $student->student_number,
                'name'              => $student->name,
                'metas'             => 0 < count($student->metas) ? Arr::pluck($student->metas, 'value', 'key') : [
                    'fname'             => '',
                    'lname'             => '',
                    'gender'            => 'm'
                ]
            ]
        ], 200);
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
        $student = Student::findOrFail($id);
        $student->name = $request->input('name');
        $student->save();

        return response()->json([
            'response'      => $student
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = Student::withTrashed()->findOrFail($id);
        if( $student->trashed() ) :
            $student->forceDelete();
        else :
            $student->delete();
        endif;

        return response()->json([
            'response'      => $student
        ], 200);
    }
}
