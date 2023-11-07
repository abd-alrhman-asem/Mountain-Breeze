<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait APIResponseTrait
{
    public function SuccessResponse($resource = null)
    {
        if ($resource) {
            return response()->json(['data' => $resource], Response::HTTP_OK);
        }

        return response()->json(['data' => [
            'success' => true
        ]], Response::HTTP_OK);
    }

    public function FailResponse($exception)
    {
        return response()->json([
            'data' => [
                'success' => false,
                'message' => $exception->getMessage(),
            ]
        ], $statusCode = Response::HTTP_NOT_FOUND);
    }
}
