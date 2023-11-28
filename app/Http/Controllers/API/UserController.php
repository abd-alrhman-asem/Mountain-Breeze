<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    use APIResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();
            $args['data'] = UserResource::collection($users);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $validate = $request->validated();
            $user= User::create([
                'name'    =>$request->name,
                'email'   =>$request->email,
                'password'=>$request->password,
                'type'    =>$request->type,
            ]);
            $args['message'] = 'user stored successfully ';
            $args['data'] = new UserResource($user);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $args['data'] = new UserResource($user);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $validate = $request->validated();
            $user = User::findOrFail($id);
            $user->update([
                'name'    =>$request->name    ??$user->name,
                'email'   =>$request->email   ??$user->email,
                'password'=>$request->password??$user->password,
                'type'    =>$request->type    ??$user->type,
            ]);
            $args['message'] = 'user updated successfully ';
            $args['data'] = new UserResource($user);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            $args['message'] = 'user  deleted successfully ';
            return $this->successResponse($args , 200);
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
