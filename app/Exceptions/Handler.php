<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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

        $this->renderable(function(ValidationException $e,$request){

            return $this->convertExceptionToResponse($e);

        });

        $this->renderable(function(NotFoundHttpException $e,$request){

            if($this->isHttpException($e)){
                $message = ($e->getMessage());
                $code = $e->getStatusCode();

                switch($code){

                    case 404: 
                        return $this->errorResponse($message,$code);
                        break;
                    case 500 :
                        return $this->errorResponse($message,$code);
                        break;  
                }
            }

          
            

        });


        
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
