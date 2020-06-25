<?php

namespace App\Http\Controllers\API;

use App\GradingSheetApproval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GradingSheetApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {
        $user = Auth::user();
        if( 'published' == $status ) :
            $approvals = GradingSheetApproval::whereNull('deleted_at')->get();
        else :
            $approvals = GradingSheetApproval::onlyTrashed()->get();
        endif;

        return response()->json([
            'response'      => $approvals
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
        $approval = GradingSheetApproval::create($request->all());

        return response()->json([
            'response'      => $approval
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
        $approval = GradingSheetApproval::onlyTrashed()->findOrFail($id);
        $approval->restore();

        return response()->json([
            'response'      => $approval
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
        $approval = GradingSheetApproval::findOrFail($id);

        return response()->json([
            'response'      => $approval
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
        $approval = GradingSheetApproval::findOrFail($id);
        $approval->update($request->all());

        return response()->json([
            'response'      => $approval
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
        $approval = GradingSheetApproval::withTrashed()->findOrFail($id);
        if( $approval->trashed() ) :
            $approval->forceDelete();
        else :
            $approval->delete();
        endif;

        return response()->json([
            'response'      => $approval
        ], 200);
    }
}
