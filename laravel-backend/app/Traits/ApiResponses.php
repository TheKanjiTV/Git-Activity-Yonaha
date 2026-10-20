<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait ApiResponses
{
    protected function ok(string $message, mixed $data = []): JsonResponse
    {
        return $this->success($message, $data, 200);
    }

    protected function success(
        string $message,
        mixed $data = [],
        int $statusCode = 200
    ): JsonResponse {
        if ($data instanceof JsonResource) {
            return $data->additional([
                'message' => $message,
                'status' => $statusCode,
            ])->response()->setStatusCode($statusCode);
        }

        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }

    protected function error(string $message, int $statusCode): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }
}
