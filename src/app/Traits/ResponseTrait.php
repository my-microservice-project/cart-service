<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    public function successResponse(string $message = 'Success', array|object|null $data = null, int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message ?? null,
            'data' => $data ?? (object) []
        ], $status);
    }

    public function errorResponse(string $message = 'Error', array|object|null $data = null, int $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message ?? null,
            'data' => $data ?? (object) []
        ], $status);
    }
}
