<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // Custom
        $this->renderable(function (MessageException $exception, Request $request) {
            return $this->response($exception->getTitle(), $exception->getDescription(), [], $exception->getCode());
        });

        // 401
        $this->renderable(function (AuthenticationException $exception, Request $request) {
            return $this->response('Unauthenticated', null, [], Response::HTTP_UNAUTHORIZED);
        });

        // 403
        $this->renderable(function (AccessDeniedHttpException $exception, Request $request) {
            return $this->response($exception->getMessage(), null, [], Response::HTTP_FORBIDDEN);
        });

        // 404
        $this->renderable(function (ModelNotFoundException $exception, Request $request) {
            return $this->response('Not found', null, [], Response::HTTP_NOT_FOUND);
        });

        $this->renderable(function (NotFoundHttpException $exception, Request $request) {
            return $this->response('Not found', null, [], Response::HTTP_NOT_FOUND);
        });

        $this->renderable(function (RouteNotFoundException $exception, Request $request) {
            return $this->response('Not found', null, [], Response::HTTP_NOT_FOUND);
        });

        // 422
        $this->renderable(function (ValidationException $exception, Request $request) {
            return $this->response('Validation error', null, $exception->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        // Other error
        $this->renderable(function (Exception $exception, Request $request) {
            return $this->response('Error', $exception->getMessage(), [], $exception->getCode());
        });
    }

    /**
     * @param string|null    $title
     * @param string|null    $description
     * @param array          $errors
     * @param int            $code
     * @param Throwable|null $previous
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response(string $title = null, string $description = null, array $errors = [], $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        if ($code < 400) {
            $code = Response::HTTP_BAD_REQUEST;
        }

        return response()->json(array_filter([
            'type'    => 'error',
            'message' => array_filter([
                'title'       => $title,
                'description' => $description,
            ]),
            'errors'  => $errors,
        ]), $code);
    }
}
