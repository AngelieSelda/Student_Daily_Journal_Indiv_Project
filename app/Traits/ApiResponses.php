<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    protected function ok(string $message, mixed $data = null): JsonResponse
    {
        return $this->success($message, $data, 200);
    }

    protected function success(string $message, mixed $data = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }

    /**
     * Error response. It accepts either:
     * - a plain message (string), or
     * - a list of error objects (array) in the standard format
     *   [['status' => 422, 'message' => '...', 'source' => '...'], ...]
     * The real HTTP status always matches the status of the error.
     */
    protected function error(string|array $errors = [], ?int $statusCode = null): JsonResponse
    {
        // A single message
        if (is_string($errors)) {
            $status = $statusCode ?? 400;

            return response()->json([
                'message' => $errors,
                'status' => $status,
            ], $status);
        }

        // A list of errors: use the status of the first one unless told otherwise
        $status = $statusCode ?? ($errors[0]['status'] ?? 500);

        // Guard against invalid HTTP status codes
        if ($status < 100 || $status > 599) {
            $status = 500;
        }

        return response()->json(['errors' => $errors], $status);
    }

    /**
     * 403 Forbidden: the user is logged in but is not allowed to do this.
     * Uses the standard error format, like every other error.
     */
    protected function notAuthorized(string $message): JsonResponse
    {
        return $this->error([
            [
                'status' => 403,
                'message' => $message,
                'source' => '',
            ]
        ]);
    }
}
