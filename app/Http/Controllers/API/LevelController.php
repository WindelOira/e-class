<?php

namespace App\Http\Controllers\API;

use App\Level;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {
        if( 'published' == $status ) :
            $levels = Level::whereNull('deleted_at')->get();
        else :
            $levels = Level::onlyTrashed()->get();
        endif;

        return response()->json([
            'response'      => $levels
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
        $level = Level::create($request->all());

        return response()->json([
            'response'  => $level
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
        $level = Level::onlyTrashed()->findOrFail($id);
        $level->restore();

        return response()->json([
            'response'      => $level
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
        $level = Level::findOrFail($id);

        return response()->json([
            'response'      => $level
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

        $level = level::findOrFail($id);
        $level->name = $input['name'];
        $level->save();

        return response()->json([
            'response'      => $level
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
        $level = Level::withTrashed()->findOrFail($id);
        if( $level->trashed() ) :
            $level->forceDelete();
        else :
            $level->delete();
        endif;

        return response()->json([
            'response'      => $level
        ], 200);
    }
}
