<?php

namespace App\Services\Api;

use Illuminate\Http\JsonResponse;

class ApiResponseService
{

    /**
     * @param  string  $message - a custom message
     * @param  null  $headerCodeError - a custom header code error
     * @param  null  $errorCode - a custom error code
     */
    public function errorResponse(string $message, array $data, $headerCodeError = null): JsonResponse
    {
        $headerCode = $headerCodeError ?? 400;
        $content = [
            'status' => false,
            'message' => $message,
            'error' => $data
        ];

        return response()->json([
            'headerCode' => $headerCode,
            'content' => $content
        ], $headerCode);
    }

    /**
     * @param  string  $message - a custom message
     * @param  array  $data - data to be returned
     * @param  int  $headerCode - a custom header code
     * @return array
     */
    public function successResponse(string $message, array $data, int $headerCode = 200): JsonResponse
    {
        $content = [
            'status' => true,
            'message' => $message,
            'data' => $data
        ];

        return response()->json([
            'headerCode' => $headerCode,
            'content' => $content
        ], $headerCode);
    }
}
