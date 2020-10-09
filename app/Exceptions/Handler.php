<?php

namespace App\Exceptions;

use Google\AdsApi\AdWords\v201809\cm\ApiException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable                $exception
     * @return \Illuminate\Http\Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (!$request->wantsJson()) {
            return parent::render($request, $exception);
        }

        $title = 'Something happened';
        $description = $exception->getMessage();
        $code = Response::HTTP_UNPROCESSABLE_ENTITY;

        $data = [];

        switch (get_class($exception)) {
            case MessageException::class:
                /** @var MessageException $exception */
                $title = $exception->getTitle();
                $description = $exception->getDescription();
                $code = $exception->getCode();
                break;

            case AuthenticationException::class:
                /** @var AuthenticationException $exception */
                $code = Response::HTTP_UNAUTHORIZED;
                break;

            case AuthorizationException::class:
                /** @var AuthorizationException $exception */
                $code = Response::HTTP_FORBIDDEN;
                break;

            case MethodNotAllowedHttpException::class:
                /** @var MethodNotAllowedHttpException $exception */
                $code = Response::HTTP_METHOD_NOT_ALLOWED;
                break;

            case ModelNotFoundException::class:
            case NotFoundHttpException::class:
            case RouteNotFoundException::class:
                /** @var ModelNotFoundException|NotFoundHttpException|RouteNotFoundException $exception */
                $description = 'Resource not found';
                $code = Response::HTTP_NOT_FOUND;
                break;

            case HttpException::class:
                /** @var HttpException $exception */
                $code = $exception->getStatusCode();
                break;

            case QueryException::class:
                /** @var QueryException $exception */
                $code = Response::HTTP_INTERNAL_SERVER_ERROR;
                break;

            case ValidationException::class:
                /** @var ValidationException $exception */
                $title = 'Check form data';
                $code = $exception->status;

                $data = array_merge($data, [
                    'errors' => $exception->errors(),
                ]);

                break;

            case ApiException::class:
                /** @var ApiException $exception */
                $errors = $exception->getErrors();

                // TODO: Processing errors

                break;
        }

        $data = array_merge($data, [
            'message' => [
                'title'       => $title,
                'description' => $description,
                'type'        => 'danger',
            ],
        ]);

        if (config('app.debug', false) && !$exception instanceof MessageException && !$exception instanceof ValidationException) {
            $data = array_merge($data, [
                'instanceof' => get_class($exception),
                'trace'      => collect($exception->getTrace())->take(10)->toArray(),
            ]);
        }

        return response($data, $code);
    }
}
