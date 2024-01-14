<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Traits\APIResponseTrait;

class Handler extends ExceptionHandler
{
    use APIResponseTrait;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public  function render($request  , Throwable $e ) {
        if ($e instanceof ModelNotFoundException ) {
            return $this->notFoundResponse( 'model not found ');
        }
        parent::render($request , $e);
    }
}
