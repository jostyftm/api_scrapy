<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{

    use ApiResponser;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // dd($e);
        $response = $this->handleException($request, $e);
        return $response;
    }

    /**
     * Handle a Exception
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param Exception $exception
     * 
     * @return array
     */
    public function handleException(Request $request, Exception $exception)
    {

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse([], $message = 'El metodo es invalido', 405);
        }
        
        if($exception instanceof ValidationException){
            return $this->errorResponse($exception->errors(), 'Error de validación', 422);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse([], 'La url especificada ', 404);
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse([], $exception->getMessage(), $exception->getStatusCode());
        }
        
        if($exception instanceof ModelNotFoundException) {
            return $this->errorResponse([], 'No hay resultado', 404);
        }

        if($exception instanceof QueryException){
            return $this->resolveQueryException($exception);
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);            
        }

        return $this->errorResponse([], 'Unexpected Exception. Try later', 500);
    }

    /**
     * 
     * @param \Illuminate\Database\QueryException $exception
     * @return \Illuminate\Http\Response
     */
    private function resolveQueryException(QueryException $e)
    {
        $errorInfo = $e->errorInfo;

        switch($errorInfo[1]){
            case 1062:
                return $this->errorResponse([], 'Registro duplicado', 500);
                break;
            default:
                return $this->errorResponse([], 'Error al manipular la base de datos', 500);
            break;
        }
    }
}
