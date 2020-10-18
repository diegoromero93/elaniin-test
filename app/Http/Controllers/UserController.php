<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Illuminate\Auth\SessionGuard;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $users = User::latest();

        if ($request->email) {
            $users->where('email', 'like', '%'.$request->email.'%');
        }

        if ($request->name) {
            $users->where('name', 'like', '%'.$request->name.'%');
        }

        return response()->json($users->paginate(15));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), User::$creation_rules);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);

        if ( ! $user)
        {
            return $this->recordNotFound();
        }

        return response()->json($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ( ! $user)
        {
            return $this->recordNotFound();
        }
        $authUser =  JWTAuth::user();
        if($authUser->id === $user->id){
            return response()->json([
                'message' => 'Cannot autodelete'
            ], 400);
        }

        $user->delete();
        return response()->json([
            'message' => 'User successfully deleted'
        ]);
    }

    private function recordNotFound(){
        return response()->json([
            'message' => 'Record not found',
        ], 404);
    }
}
