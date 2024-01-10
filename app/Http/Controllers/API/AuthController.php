<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\APIResponseTrait;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use APIResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        try {
            $credentials = request(['email', 'password']);
            if (!$token = auth()->attempt($credentials))
                return $this->unauthorizedResponse('Unauthorized');
            return $this->loggedInSuccessfully($token);
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            return $this->successResponse(auth()->user());
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }

    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->logout();
            return $this->successOperationResponse('Successfully logged out');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

}
