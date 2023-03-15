<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function notFound($message = 'Not found'): JsonResponse
    {
        return $this->error($message, 404);
    }

    // success

    public function error($message = '', $code = 400): JsonResponse
    {
        return $this->apiResponse(['message' => $message], $code);
    }

    // error

    public function apiResponse($data, $status = 200): JsonResponse
    {
        if (is_string($data)) {
            $data = ['message' => $data];
        }

        return response()->json($data, $status);
    }

    // not found

    public function forbidden($message = 'Forbidden'): JsonResponse
    {
        return $this->error($message, 403);
    }

    // forbidden

    public function unauthorized($message = 'Unauthorized'): JsonResponse
    {
        return $this->error($message, 401);
    }

    // unauthorized

    public function badRequest($message = 'Bad request'): JsonResponse
    {
        return $this->error($message);
    }

    // bad request

    public function created($message = 'Created'): JsonResponse
    {
        return $this->success($message, 201);
    }

    // created

    public function success($data = []): JsonResponse
    {
        return $this->apiResponse($data);
    }

    // accepted

    public function accepted($message = 'Accepted'): JsonResponse
    {
        return $this->success($message, 202);
    }

    // no content
    public function noContent($message = 'No content'): JsonResponse
    {
        return $this->success($message, 204);
    }

    // updated
    public function updated($message = 'Updated'): JsonResponse
    {
        return $this->success($message, 200);
    }

    // deleted
    public function deleted($message = 'Deleted'): JsonResponse
    {
        return $this->success($message, 200);
    }

    // not allowed
    public function notAllowed($message = 'Not allowed'): JsonResponse
    {
        return $this->error($message, 405);
    }

    // conflict
    public function conflict($message = 'Conflict'): JsonResponse
    {
        return $this->error($message, 409);
    }

    // too many requests
    public function tooManyRequests($message = 'Too many requests'): JsonResponse
    {
        return $this->error($message, 429);
    }

    // server error
    public function serverError($message = 'Server error'): JsonResponse
    {
        return $this->error($message, 500);
    }

    // service unavailable
    public function serviceUnavailable($message = 'Service unavailable'): JsonResponse
    {
        return $this->error($message, 503);
    }

    // method not allowed
    public function methodNotAllowed($message = 'Method not allowed'): JsonResponse
    {
        return $this->error($message, 405);
    }

    // not acceptable
    public function notAcceptable($message = 'Not acceptable'): JsonResponse
    {
        return $this->error($message, 406);
    }

    // precondition failed
    public function preconditionFailed($message = 'Precondition failed'): JsonResponse
    {
        return $this->error($message, 412);
    }
}
