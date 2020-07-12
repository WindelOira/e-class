<?php

namespace App\Http\Controllers\API;

use App\AcademicYear;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {
        if( 'published' == $status ) :
            $years = AcademicYear::whereNull('deleted_at')->orderBy('year')->get();
        else :
            $years = AcademicYear::onlyTrashed()->get();
        endif;

        return response()->json([
            'response'      => $years
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
        if( AcademicYear::where('year', $request->input('year'))->first() ) // Check if academic year already exists.
            return response()->json([
                'response'  => 'Academic year already exists.'
            ], 401);

        $year = AcademicYear::create($request->all());

        return response()->json([
            'response'      => $year
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
        $year = AcademicYear::onlyTrashed()->findOrFail($id);
        $year->restore();

        return response()->json([
            'response'      => $year
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
        $year = AcademicYear::findOrFail($id);
        // $year->year = 2019;

        return response()->json([
            'response'      => $year
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
        if( AcademicYear::where('year', $request->input('year'))->first() ) // Check if academic year already exists.
            return response()->json([
                'response'  => 'Academic year already exists.'
            ], 401);

        $year = AcademicYear::findOrFail($id);
        $year->update($request->all());

        return response()->json([
            'response'      => $year
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
        $year = AcademicYear::withTrashed()->findOrFail($id);
        if( $year->trashed() ) :
            $year->forceDelete();
        else :
            $year->delete();
        endif;

        return response()->json([
            'response'      => $year
        ], 200);
    }
}
