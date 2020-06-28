<?php

namespace App\Http\Controllers\API;

use App\Subject;
use App\Track;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {
        $data = [];
        if( 'published' == $status ) :
            $subjects = Subject::whereNull('deleted_at')->get();
        else :
            $subjects = Subject::onlyTrashed()->get();
        endif;

        foreach( $subjects as $key => $subject ) :
            $data[$key] = $subject;
            $data[$key]['tracks'] = $subject->tracks;
        endforeach;

        return response()->json([
            'response'      => $data
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
        $duplicates = Subject::where('name', '=', $request->input('name'))
                            ->orWhere('description', '=', $request->input('description'))
                            ->get();

        if( 0 < count($duplicates) )
            return response()->json([
                'response'  => 'Subject already exists.'
            ], 401);

        $subject = Subject::create($request->all());

        return response()->json([
            'response'      => $subject
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
        $subject = Subject::onlyTrashed()->findOrFail($id);
        $subject->restore();

        return response()->json([
            'response'      => $subject
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
        $subject = Subject::findOrFail($id);

        return response()->json([
            'response'      => [
                'id'                    => $subject->id,
                'subject_track_id'      => $subject->subject_track_id->id,
                'subject_tracks'        => $subject->subject_track_id,
                'name'                  => $subject->name,
                'description'           => $subject->description,
                'hours'                 => $subject->hours
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
        $duplicates = Subject::where('id', '!=', $id)
                            ->where(function($query) use ($request) {
                                $query->where('name', '=', $request->input('name'))
                                        ->orWhere('description', '=', $request->input('description'));
                            })
                            ->get();

        if( 0 < count($duplicates) )
            return response()->json([
                'response'  => 'Subject already exists.'
            ], 401);

        $subject = Subject::findOrFail($id);
        $subject->update($request->all());

        return response()->json([
            'response'      => $subject
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
        $subject = Subject::withTrashed()->findOrFail($id);
        if( $subject->trashed() ) :
            $subject->forceDelete();
        else :
            $subject->delete();
        endif;

        return response()->json([
            'response'      => $subject
        ], 200);
    }
}
