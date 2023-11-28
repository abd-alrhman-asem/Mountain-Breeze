<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait APIResponseTrait
{
    public function SuccessResponse($data = [], String $message = null, $status = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }


    public function FailResponse(String $message)
    {
        return response()->json([
            'data' => [
                'success' => false,
                'message' => $message,
            ]
        ], $statusCode = Response::HTTP_NOT_FOUND);
    }
}
