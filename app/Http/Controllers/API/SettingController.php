<?php

namespace App\Http\Controllers\API;

use App\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::all();

        return response()->json([
            'response'      => $settings
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = [];
        foreach( $request->all() as $key => $value ) :
            $setting = Setting::where('key', $key)->update([
                'value' => $value
            ]);
        endforeach;

        return response()->json([
            'response'      => Setting::all()
        ], 200);
    }
}
