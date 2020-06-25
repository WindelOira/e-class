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
        $input = $request->all();

        $track = Track::create([
            'name'      => $input['name']
        ]);

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
        $input = $request->all();

        $track = Track::findOrFail($id);
        $track->name = $input['name'];
        $track->save();
        
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
