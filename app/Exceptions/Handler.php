<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
     * Report or log an exception.
     *
     * @param \Throwable $exception
     * @return void
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable               $exception
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (!$request->wantsJson()) {
            return parent::render($request, $exception);
        }

        if ($exception instanceof MessageException) {
            $title = $exception->getTitle();
            $description = $exception->getDescription();
        } else {
            $title = 'Something happened';
            $description = $exception->getMessage();
        }

        $data = [
            'message' => [
                'title'       => $title,
                'description' => $description,
                'type'        => 'danger',
            ],
        ];

        if (config('app.debug', false) && !$exception instanceof MessageException && !$exception instanceof ValidationException) {
            $data = array_merge($data, [
                'instanceof' => get_class($exception),
                'trace'      => collect($exception->getTrace())->take(10)->toArray(),
            ]);
        }

        if ($exception instanceof MessageException) {
            return response($data, $exception->getCode());
        }

        if ($exception instanceof AuthenticationException) {
            return response($data, Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof AuthorizationException) {
            return response($data, Response::HTTP_FORBIDDEN);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response($data, Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof HttpException) {
            return response($data, $exception->getStatusCode());
        }

        if ($exception instanceof ModelNotFoundException || $exception instanceof RouteNotFoundException) {
            return response($data, Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof QueryException) {
            return response($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($exception instanceof ValidationException) {
            return response(array_merge($data, [
                'errors' => $exception->errors(),
            ]), $exception->status);
        }

        return parent::render($request, $exception);
    }
}
