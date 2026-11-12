<?php

namespace App\Exceptions;

use App\Traits\ApiResponses;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponses;

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (ModelNotFoundException $e, $request) {
            return $this->error([
                [
                    'status' => 404,
                    'message' => 'The resource cannot be found.',
                    'source' => '',
                ]
            ]);
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            return $this->error([
                [
                    'status' => 404,
                    'message' => 'The resource cannot be found.',
                    'source' => '',
                ]
            ]);
        });

        $this->renderable(function (ValidationException $e, $request) {
            $errors = [];
            foreach ($e->errors() as $key => $messages) {
                foreach ($messages as $message) {
                    $errors[] = [
                        'status' => 422,
                        'message' => $message,
                        'source' => $key,
                    ];
                }
            }

            return $this->error($errors);
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            return $this->error([
                [
                    'status' => 401,
                    'message' => 'Unauthenticated',
                    'source' => '',
                ]
            ]);
        });

        $this->renderable(function (AuthorizationException $e, $request) {
            return $this->error([
                [
                    'status' => 403,
                    'message' => 'You are not authorized to perform this action.',
                    'source' => '',
                ]
            ]);
        });
    }
}
