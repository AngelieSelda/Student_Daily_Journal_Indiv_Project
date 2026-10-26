<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * Reusable helpers that return JSON responses in one consistent format.
 * Any controller (or the exception handler) can pull them in with "use ApiResponses;".
 */
trait ApiResponses
{
    /**
     * Shortcut for a 200 OK response.
     * Most successful requests only need a message and, optionally, some data.
     */
    protected function ok(string $message, mixed $data = null): JsonResponse
    {
        return $this->success($message, $data, 200);
    }

    /**
     * Generic success response.
     * The status code is sent in the body and as the real HTTP status of the response.
     */
    protected function success(string $message, mixed $data = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }

    /**
     * Error response with a single message (for example "Invalid credentials").
     */
    protected function error(string $message, int $statusCode): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }
}