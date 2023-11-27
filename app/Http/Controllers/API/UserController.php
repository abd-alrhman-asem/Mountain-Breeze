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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();
            return $this->successResponse(UserResource::collection($users));
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
            return $this->successResponse(new UserResource($user));
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
            return $this->successResponse(new UserResource($user));
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
            return $this->successResponse(new UserResource($user));
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
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
