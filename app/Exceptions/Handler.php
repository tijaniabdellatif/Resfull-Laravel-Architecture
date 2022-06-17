<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use BadMethodCallException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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

        /**
         * Handle Validation Errors
         */
        $this->renderable(function(ValidationException $e,$request){

            return $this->convertExceptionToResponse($e);

        });

        /**
         * Handle NotFound Exception
         */
        $this->renderable(function(NotFoundHttpException $e,$request){

            if($this->isHttpException($e)){

                $code = $e->getStatusCode();

                switch($code){

                    case 200:
                        return $this->errorResponse('Error',$code);
                        break;

                    case 404:
                        $message = ($e->getMessage());
                        if($message === ''){
                         return $this->errorResponse('Not found endpoints',$code);
                        }
                        return $this->errorResponse($message,$code);
                        break;

                    default: throw new \Exception('Unknowerror',1);

                }
            }




        });


        $this->renderable(function (AuthenticationException $e,$request){


              return $this->unauthenticated($request,$e);
        });

        $this->renderable(function(AuthorizationException $e){

             return $this->errorResponse($e->getMessage(),403);
        });


        $this->renderable(function(MethodNotAllowedHttpException $e){


            return $this->errorResponse($e->getMessage(),$e->getStatusCode());
        });

        $this->renderable(function(HttpException $e){

            return $this->errorResponse($e->getMessage(),$e->getStatusCode());
        });


        $this->renderable(function(QueryException $e){

             $errorCode = $e->errorInfo[1];
             if($errorCode === 1451){

                 return $this->errorResponse('Can not remove this resource permanently due to its relation with other ressource '
                 ,409);
             }
        });

        return $this->errorResponse('Unexpected Exception, Please Try again',500);

    }



    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($request->expectsJson()){

              return response()->json(['error' => 'Unauthenticated'],401);
        }

        return redirect()->guest('login');
    }



    protected function convertExceptionToResponse(Throwable $e)
    {


            $errors = $e->validator->errors()->getMessages();

            return $this->errorResponse($errors,422);



    }




}
