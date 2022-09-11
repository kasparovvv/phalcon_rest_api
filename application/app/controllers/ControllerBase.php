<?php
declare(strict_types=1);

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Http\Request;
use Phalcon\Http\Response;

class ControllerBase extends Controller {
   public $userId;
   public function beforeExecuteRoute( Dispatcher $dispatcher) {
      $response = new Response();
      $request = new Request();
      $authorizationHeader = $request->getHeader("Authorization");
      if ($authorizationHeader AND preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
         $jwt = new myJwt();
         $token = $matches[1];
         $validation = $jwt->validateToken($token);

         if($validation){
            $this->userId = $validation;
            return true;
         }
         else{
            $response->setStatusCode(401, 'Unauthorized');
            $response->setJsonContent(["status" => false, "errors" =>"invalid_token","message"=>"The access token is invalid or has expired"]);
            $response->send();
            exit();
         }
      }
      else{
         $response->setStatusCode(401, 'Unauthorized');
         $response->setJsonContent(["status" => false, "errors" =>"missing_token","message" =>"Missing Authentication Token"]);
         $response->send();
         exit();
      }
     
      exit();
  }
}
