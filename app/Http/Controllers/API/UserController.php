<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

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
    public function index(): JsonResponse
    {
        try {
            $users = User::all();
            return $this->successResponse(UserResource::collection($users) );
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            User::create([
                'name'    =>$request->name,
                'email'   =>$request->email,
                'password'=>$request->password,
                'type'    =>$request->type,
            ]);
            return $this->successOperationResponse('user stored successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $user = User::find($id);
            if (!$user)
                return $this->errorResponse('there are no user for this id ');
            return $this->successResponse(new UserResource($user) );
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        try {
            $user = User::find($id);
            if (!$user)
                return $this->errorResponse('there are no user for this id ');
            $user->update([
                'name'    =>$request->name    ??$user->name,
                'email'   =>$request->email   ??$user->email,
                'password'=>$request->password??$user->password,
                'type'    =>$request->type    ??$user->type,
            ]);
            return $this->successOperationResponse('user updated successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $user = User::find($id);
            if (!$user)
                return $this->errorResponse('there are no user for this id ');
            $user->delete();
            return $this->successOperationResponse('user  deleted successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}
