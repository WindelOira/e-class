<?php

namespace App\Http\Controllers\API;

use App\SubjectTrack;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectTrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {
        if( 'published' == $status ) :
            $tracks = SubjectTrack::whereNull('deleted_at')->get();
        else :
            $tracks = SubjectTrack::onlyTrashed()->get();
        endif;

        return response()->json([
            'response'      => $tracks
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
        $duplicates = SubjectTrack::where('name', '=', $request->input('name'))
                            ->get();

        if( 0 < count($duplicates) )
            return response()->json([
                'response'  => 'Subject track already exists.'
            ], 401);

        $track = SubjectTrack::create($request->all());

        return response()->json([
            'response'      => $track
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
        $track = SubjectTrack::onlyTrashed()->findOrFail($id);
        $track->restore();

        return response()->json([
            'response'      => $track
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
        $track = SubjectTrack::findOrFail($id);

        return response()->json([
            'response'      => $track
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
        $duplicates = SubjectTrack::where('id', '!=', $id)
                            ->where('name', '=', $request->input('name'))
                            ->get();

        if( 0 < count($duplicates) )
            return response()->json([
                'response'  => 'Subject track already exists.'
            ], 401);

        $track = SubjectTrack::findOrFail($id);
        $track->update($request->all());

        return response()->json([
            'response'      => $track
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
        $track = SubjectTrack::withTrashed()->findOrFail($id);
        if( $track->trashed() ) :
            $track->forceDelete();
        else :
            $track->delete();
        endif;

        return response()->json([
            'response'      => $track
        ], 200);
    }
}
