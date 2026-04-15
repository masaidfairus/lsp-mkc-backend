<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse($data = [], $message = "OK", $code = 200): JsonResponse
    {
        return response()->json([
            'metadata' => [
                'code' => $code,
                'message' => $message
            ],
            'response' => $data
        ], $code);
    }

    protected function errorResponse($errorCode, $httpStatus = 400, $data = []): JsonResponse
    {
        $message = config("errors.$errorCode") ?? 'Terjadi kesalahan sistem';

        return response()->json([
            'metadata' => [
                'code' => $errorCode,
                'message' => $message
            ],
            'response' => $data
        ], $httpStatus);
    }
}
