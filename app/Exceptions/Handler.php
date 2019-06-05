<?php

namespace App\Exceptions;

use App\Exceptions\unauthenticated;
use App\Traits\ApiResponser;
use Exception;
use Http\Client\Exception\HttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Concerns\expectsJson;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception,$request);
        }
        if ($exception instanceof ModelNotFoundException) {
            $modelo = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("Não existe nenhuma instância de {$modelo} com o id especificado",404);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse('Não possui permissão para executar essa acção',403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('Não se encontrou a url especificada',404);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('O método especificado no pedido http não é válido',405);
        }
        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage,$exception->getStatusCode());
        }
        //trata problemas como por exemplo o Delete de dados que estão relacionados na BD
        if ($exception instanceof QueryException) {
            //dd($exception);
            $codigo = $exception->errorInfo[1];
            if($codigo==1451){
                return $this->errorResponse('Não se pode eliminar de forma permanente porque este recurso se encontra relacionado com mais algum outro.',409);
            }
        }
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }
        return $this->errorResponse('Falha inesperada. Tente de novo mais tarde.',500);

    }
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('Não autenticado.', 401);
    }
     /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {

        return $this->invalidJson($request,$e);

    }
}
