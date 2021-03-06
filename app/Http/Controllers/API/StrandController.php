<?php

namespace App\Http\Controllers\API;

use App\Strand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {
        if( 'published' == $status ) :
            $strands = Strand::whereNull('deleted_at')->get();
        else :
            $strands = Strand::onlyTrashed()->get();
        endif;

        return response()->json([
            'response'      => $strands
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
        $duplicates = Strand::where('code', '=', $request->input('code'))
                            ->orWhere('description', '=', $request->input('description'))
                            ->get();

        if( 0 < count($duplicates) )
            return response()->json([
                'response'  => 'Strand already exists.'
            ], 401);

        $strand = Strand::create($request->all());

        return response()->json([
            'response'      => $strand
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
        $strand = Strand::onlyTrashed()->findOrFail($id);
        $strand->restore();

        return response()->json([
            'response'      => $strand
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
        $strand = Strand::findOrFail($id);

        return response()->json([
            'response'      => [
                'id'            => $strand->id,
                'code'          => $strand->code,
                'description'   => $strand->description,
                'track_id'      => $strand->track_id->id
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
        $duplicates = Strand::where('id', '!=', $id)
                            ->where(function($query) use ($request) {
                                $query->where('code', '=', $request->input('code'))
                                        ->orWhere('description', '=', $request->input('description'));
                            })
                            ->get();

        if( 0 < count($duplicates) )
            return response()->json([
                'response'  => 'Strand already exists.'
            ], 401);

        $strand = Strand::findOrFail($id);
        $strand->update($request->all());

        return response()->json([
            'response'      => $strand
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
        $strand = Strand::withTrashed()->findOrFail($id);
        if( $strand->trashed() ) :
            $strand->forceDelete();
        else :
            $strand->delete();
        endif;

        return response()->json([
            'response'      => $strand
        ], 200);
    }
}
