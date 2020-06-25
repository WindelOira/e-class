<?php

namespace App\Http\Controllers\API;

use App\ComputationVariable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComputationVariableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {
        if( 'published' == $status ) :
            $variables = ComputationVariable::whereNull('deleted_at')->get();
        else :
            $variables = ComputationVariable::onlyTrashed()->get();
        endif;
        
        return response()->json([
            'response'      => $variables
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
        $variable = ComputationVariable::create($request->all());

        return response()->json([
            'response'      => $variable
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
        $variable = ComputationVariable::onlyTrashed()->findOrFail($id);
        $variable->restore();

        return response()->json([
            'response'      => $variable
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
        $variable = ComputationVariable::findOrFail($id);

        return response()->json([
            'response'      => $variable
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
        $variable = ComputationVariable::findOrFail($id);
        $variable->type = $request->input('type');
        $variable->name = $request->input('name');
        $variable->percentage = $request->input('percentage');
        $variable->save();

        return response()->json([
            'response'      => $variable
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
        $variable = ComputationVariable::withTrashed()->findOrFail($id);
        if( $variable->trashed() ) :
            $variable->forceDelete();
        else :
            $variable->delete();
        endif;

        return response()->json([
            'response'      => $variable
        ], 200);
    }
}
