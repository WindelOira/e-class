<?php

namespace App\Http\Controllers\API;

use App\Student;
use App\Meta;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status, $filters = null)
    {
        $filters = $filters ? explode(',', $filters) : false;
        $where = [];
        
        if( $filters ) :
            foreach( $filters as $filter ) :
                $filter = explode('=', $filter);

                $where[] = [ $filter[0], '=', $filter[1] ];
            endforeach;
        endif;

        if( 'published' == $status ) :
            $students = Student::whereNull('deleted_at')
                    ->where($where)
                    ->get();
        else :
            $students = Student::onlyTrashed()
            ->where($where)
                    ->get();
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
        $student = Student::create($request->except('metas'));

        if( 0 < count($request->input('metas')) ) :
            foreach( $request->input('metas') as $key => $value ) :
                $meta = Meta::create([
                    'key'       => $key,
                    'value'     => $value
                ]);

                $meta->students()->syncWithoutDetaching([$student->id]);
            endforeach;
        endif;

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
                'strand_id'         => $student->strand_id,
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
        $student->update($request->except('metas'));

        if( 0 < count($request->input('metas')) ) :
            if( 0 < count($student->metas) ) :
                foreach( $student->metas as $meta ) :
                    if( array_key_exists($meta->key, $request->input('metas')) ) :
                        $meta->update([
                            'value'     => $request->input('metas.'. $meta->key)
                        ]);
                    else :
                        $meta = Meta::create([
                            'key'       => $key,
                            'value'     => $value
                        ]);

                        $meta->students()->syncWithoutDetaching([$id]);
                    endif;
                endforeach;
            else :
                foreach( $request->input('metas') as $key => $value ) :
                    $meta = Meta::create([
                        'key'       => $key,
                        'value'     => $value
                    ]);

                    $meta->students()->syncWithoutDetaching([$id]);
                endforeach;
            endif;
        endif;

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
