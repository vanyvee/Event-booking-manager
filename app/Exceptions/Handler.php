<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;

use Illuminate\Support\Arr;

class Handler extends ExceptionHandler
{
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
    public function report(Throwable $exception){
        parent::report($exception);
    }
    public function render($request, Throwable $exception){
        return parent::render($request, $exception);
    }
    protected function unauthenticated($request,AuthenticationException $exception){
        if($request->expectsJson()){
            return response()->json(['error'=>'Unauthenticated.'], 401);
        }
        $guard=Arr::get($exception->guards(),0);

        
        switch($guard){
            case 'admin':
                $login='login';
             break;
            case 'employee':
                $login='login';
             break;
             case 'student':
                $login='login';
             break;
            default:
                $login='login';
             break;
        }
        return redirect()->guest(route($login));
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
