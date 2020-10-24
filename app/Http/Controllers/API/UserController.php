<?php

namespace App\Http\Controllers\API;

use App\User; 
use App\Meta;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    /**
     * Login.
     * 
     * @return  \Illuminate\Http\Response
     */
    public function login() {
        $attempt = Auth::attempt([
            'employee_id'   => request('employee_id'),
            'password'      => request('password')
        ]);

        if( !$attempt )
            return response()->json([
                'response'      => 'Unauthorised'
            ], 401);
        
        $user = Auth::user();
        $response = [
            'token'     => $user->createToken( env('APP_NAME') )->accessToken,
            'user'      => $user
        ];

        if( 'teacher' == $user->role ) :
            $response['misc'] = [
                'section'       => $user->section
            ];
        endif;
        
        return response()->json([
            'response'  =>  $response
        ], 200);
    }

    /**
     * Register.
     * 
     * @param   \Illuminate\Http\Request
     * @return  \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'employee_id'   => 'required',
            'name'          => 'required',
            'email'         => 'required|email',
            'password'      => 'required',
            'c_password'    => 'required|same:password'
        ]);

        if( $validator->fails() )
            return response()->json([
                'response'      => $validator->errors()
            ], 401);

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $response = [
            'token'     => $user->createToken( env('APP_NAME') )->accessToken,
            'user'      => $user
        ];

        return response()->json([
            'response'      => $response
        ], 200);
    }

    /**
     * Logout.
     * 
     * @param   \Illuminate\Http\Request
     * @return  \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        if( Auth::check() ) :
            $request->user()->token()->revoke();
        endif;

        return response()->json([
            'response'      => 1
        ], 200);
    }

    /**
     * Details.
     * 
     * @return  \Illuminate\Http\Response
     */
    public function details() {
        $user = Auth::user();

        return response()->json([
            'response'      => [
                'id'            => $user->id,
                'employee_id'   => $user->employee_id,
                'email'         => $user->email,
                'name'          => $user->name,
                'role'          => $user->role,
                'metas'         => 0 < count($user->metas) ? Arr::pluck($user->metas, 'value', 'key') : [
                    'fname'         => '',
                    'lname'         => ''
                ]
            ]
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status, $filters = null)
    {
        $filters = $filters ? explode(',', $filters) : false;
        $where = [];

        if( $user = Auth::user() ) :
            $where[] = [ 'id', '!=', $user->id ];
        endif;
        
        if( $filters ) :
            foreach( $filters as $filter ) :
                $filter = explode('=', $filter);

                $where[] = [ $filter[0], '=', $filter[1] ];
            endforeach;
        endif;

        if( 'published' == $status ) :
            $users = User::whereNull('deleted_at')
                        ->where($where)
                        ->get();
        else :
            $users = User::onlyTrashed()
                        ->where($where)
                        ->get();
        endif;

        return response()->json([
            'response'      => $users
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
        $user = User::create([
            'employee_id'   => $request->input('employee_id'),
            'role'          => $request->input('role'),
            'name'          => $request->input('metas.fname') .' '. $request->input('metas.lname'),
            'email'         => $request->input('email'),
            'password'      => bcrypt($request->input('password'))
        ]);

        return response()->json([
            'response'      => $user
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
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return response()->json([
            'response'      => $user
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
        $user = User::findOrFail($id);

        return response()->json([
            'response'      => [
                'id'            => $user->id,
                'employee_id'   => $user->employee_id,
                'email'         => $user->email,
                'name'          => $user->name,
                'role'          => $user->role,
                'metas'         => 0 < count($user->metas) ? Arr::pluck($user->metas, 'value', 'key') : [
                    'fname'         => '',
                    'lname'         => ''
                ]
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
        $user = User::findOrFail($id);
        $data = [
            'email'     => $request->input('email'),
            'name'      => $request->input('metas.fname') .' '. $request->input('metas.lname'),
        ];

        if( $request->input('opassword') ) : 
            if( !Hash::check($request->input('opassword'), $user->password) )
                return response()->json([
                    'response'      =>  'Old password does not matched the record.'
                ], 401);
            
            if( Hash::check($request->input('password'), $user->password) )
                return response()->json([
                    'response'      => 'New password matched the current password.'
                ], 401);

           $data['password'] = bcrypt($request->input('password')); 
        endif;

        $user->update($data);

        if( 0 < count($request->input('metas')) ) :
            if( 0 < count($user->metas) ) :
                foreach( $user->metas as $meta ) :
                    if( array_key_exists($meta->key, $request->input('metas')) ) :
                        $meta->update([
                            'value'     => $request->input('metas')[$meta->key]
                        ]);
                    else :
                        $meta = Meta::create([
                            'key'       => $key,
                            'value'     => $value
                        ]);

                        $meta->users()->syncWithoutDetaching([$id]);
                    endif;
                endforeach;
            else :
                foreach( $request->input('metas') as $key => $value ) :
                    $meta = Meta::create([
                        'key'       => $key,
                        'value'     => $value
                    ]);

                    $meta->users()->syncWithoutDetaching([$id]);
                endforeach;
            endif;
        endif;
        
        return response()->json([
            'response'      => $request->all()
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
        $user = User::withTrashed()->findOrFail($id);
        if( $user->trashed() ) :
            $user->forceDelete();
        else :
            $user->delete();
        endif;

        return response()->json([
            'response'      => $user
        ], 200);
    }
}
