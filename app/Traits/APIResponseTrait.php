<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\JsonResponse as JsonResponseAlias;
use Symfony\Component\HttpFoundation\Response;

trait APIResponseTrait
{

    /**
     * Success Response.
     *
     * @param array $data
     * @param int $statusCode
     * @return JsonResponseAlias
     */
    public function successResponse(mixed $data , int $statusCode = Response::HTTP_OK): JsonResponseAlias
    {
        return response()->json([
            'success' => 'true' ,
            'data' => $data
        ],$statusCode);
    }

    /**
     * Error Response.
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponseAlias
     */
    public function errorResponse(string $message = '', int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponseAlias
    {
        // Check if there is a message passed.
        if (!$message) {
            $message = Response::$statusTexts[$statusCode];
        }
        return response()->json([
            'success' => 'false',
            'message' => $message,
            ]
            ,$statusCode);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponseAlias
     */

    public function loggedInSuccessfully(string $token ): JsonResponseAlias
    {
        return response()->json([
            'success'       => 'true' ,
            'access_token'  =>  $token ,
            'token_type'    => 'bearer',
            'expires_in'    => auth()->factory()->getTTL()
        ]);
    }

    /**
     * General failure response for unknown errors.
     *
     * @param mixed $errorMessage
     * @return JsonResponseAlias
     */
    public function generalFailureResponse(mixed $errorMessage): JsonResponseAlias
    {
        // Use the errorResponse method for consistency.
        return $this->errorResponse("general error : " . $errorMessage);
        // this for in production statue
        //return $this->errorResponse("general error please try again" );
    }

    /**
     * Response with status code 200.
     *
     * @param string $statueMessage
     * @param int $statusCode
     * @return JsonResponseAlias
     */
    public function successOperationResponse(string $statueMessage  = 'operation done successfully ', int $statusCode = Response::HTTP_OK): JsonResponseAlias
    {
        return response()->json( [
            'success' => 'true' ,
            'statue' => $statueMessage
            ]
            ,  $statusCode );
    }

    /**
     * Response with status code 201.
     *
     * @param $modelName
     * @return JsonResponseAlias
     */
    public function createdResponse( $modelName ): JsonResponseAlias
    {
     return $this->successOperationResponse($modelName.' created successfully', Response::HTTP_CREATED);
    }

    /**
     * Response with status code 401.
     *
     * @param string $message
     * @return JsonResponseAlias
     */
    public function unauthorizedResponse(string $message = ''): JsonResponseAlias
    {
        return $this->errorResponse( $message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Response with status code 404.
     *
     * @param string $message
     * @return JsonResponseAlias
     */
    public function notFoundResponse( string $message = ''): JsonResponseAlias
    {
        return $this->errorResponse( $message, Response::HTTP_NOT_FOUND);
    }
    /**
     * Response with status code 422.
     *
     * @param string $message
     * @return JsonResponseAlias
     */
    public function unprocessableResponse( string $message = ''): JsonResponseAlias
    {
        return $this->errorResponse( $message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    //___________________________________________________________________________________________________________

    /**
     * Response with status code 204.
     *
     */
    public function noContentResponse(): JsonResponseAlias
    {
        return $this->errorResponse();
    }
    /**
     * Response with status code 409.
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponseAlias
     */
    public function conflictResponse(mixed $data, string $message = ''): JsonResponseAlias
    {
        return $this->errorResponse( $message, Response::HTTP_CONFLICT);
    }
    /**
     * Response with status code 403.
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponseAlias
     */
    public function forbiddenResponse(mixed $data, string $message = ''): JsonResponseAlias
    {
        return $this->errorResponse( $message, Response::HTTP_FORBIDDEN);
    }
    /**
     * Response with status code 400.
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponseAlias
     */
    public function badRequestResponse(mixed $data, string $message = ''): JsonResponseAlias
    {
        return $this->errorResponse( $message, Response::HTTP_BAD_REQUEST);
    }
}
