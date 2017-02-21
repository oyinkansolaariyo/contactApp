<?php
/**
 * Created by PhpStorm.
 * User: itunu.babalola
 * Date: 1/20/17
 * Time: 5:15 PM
 */

namespace Middlewares;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Utils\General_Utils;



class AuthMW
{
    protected  $container;

    public  function __construct($container)
    {
        $this->container = $container;
    }


    public function __invoke(Request $request ,Response $response ,$next){

        if($request->getMethod() == 'POST'){
            if(General_Utils::getInstance()->isLoggedin()){
                $this->container->user = General_Utils::getInstance()->makeSession('user');
                $response = $next($request,$response);
                return $response;
            }
            else{
                $error =['message'=> 'Sorry ,You have to login First'];
                return $response->withStatus(300)->getReasonPhrase('You have To login First to perform this action');
            }
        }
    }


}