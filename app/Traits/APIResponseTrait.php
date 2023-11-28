<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait APIResponseTrait
{
//        public function SuccessResponse($data = [null], String $message = null, $status = 200)
        public function SuccessResponse( $args, $statuscode ): \Illuminate\Http\JsonResponse
        {

        if ( (isset($args['message'])) && (isset($args['data'])) )
            return  response()->json([
                'success' => true,
                'data' => $args['data'],
                'message' => $args['message'],
           ] );
        elseif(isset($args['message'] ))
            return  response()->json([
                'success' => true,
                'message' => $args['message'],
           ] );
        elseif (isset($args['data']))
            return  response()->json([
            'success' => true,
            'data' => $args['data'],
        ] );
        else
            return  response()->json([
                'success' => true,
            ] );
    }


    public function FailResponse(String $message): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => [
                'success' => false,
                'message' => $message,
            ]
        ], $statusCode = ResponseAlias::HTTP_NOT_FOUND);
    }
}
