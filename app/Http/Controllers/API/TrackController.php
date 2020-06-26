<?php

namespace App\Http\Controllers\API;

use App\Track;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {
        if( 'published' == $status ) :
            $tracks = Track::whereNull('deleted_at')->get();
        else :
            $tracks = Track::onlyTrashed()->get();
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
        $duplicates = Track::where('name', '=', $request->input('name'))
                            ->get();

        if( 0 < count($duplicates) )
            return response()->json([
                'response'  => 'Track already exists.'
            ], 401);

        $track = Track::create($request->all());

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
        $track = Track::onlyTrashed()->findOrFail($id);
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
        $track = Track::findOrFail($id);

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
        $duplicates = Track::where('id', '!=', $id)
                            ->where('name', '=', $request->input('name'))
                            ->get();

        if( 0 < count($duplicates) )
            return response()->json([
                'response'  => 'Track already exists.'
            ], 401);

        $track = Track::findOrFail($id);
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
        $track = Track::withTrashed()->findOrFail($id);
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
