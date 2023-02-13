<?php

namespace App\Exceptions;

use DomainException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
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
        // Отправляет ошибки в sentry, telescope и подобные
        $this->reportable(function (Throwable $e) {
            $this->reportable(function (Throwable $e) {
                if (app()->bound('sentry')) {
                    app('sentry')->captureException($e);
                }
            });
        });

        $this->renderable(function (DomainException $e) {
            flash()->alert($e->getMessage());

            return back();
        });

        // Можно создавать 404 ошибки не через views (главное возвращать через response)
//        $this->renderable(function (NotFoundHttpException $e) {
//            return response()
//                ->view('welcome');
//        });
    }
}
