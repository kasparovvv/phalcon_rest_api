<?php
declare(strict_types=1);

use Phalcon\Http\Response;
use Phalcon\Http\Request;
use Phalcon\Security;

use Phalcon\Validation;

use Phalcon\Validation\Validator\PresenceOf;

class ApiController extends \Phalcon\Mvc\Controller {

    public function registerAction(){

        $this->view->disable();
        
        $response = new Response();
        $request = new Request();
       
        if ($request->isPost()){
            
            $validation = new Validation();
            $validation->add(
                [
                    "first_name",
                    "last_name",
                    "username",
                    "password",
                ],
                new PresenceOf(
                    [
                        "message" => [
                            "first_name" => "First name  is  is required",
                            "last_name" => "Last name  is  is required",
                            "username" => "Username  is  is required",
                            "password"     => "Password is  is required",
                        ],
                    ]
                )
            );

            $data = $request->getJsonRawBody();
            $messages = $validation->validate($data);
            if (count($messages) > 0) {
                $errors = [];
                foreach ($messages as $message) {
                    $errors[$message->getField()] = $message->getMessage();
                }
                $response->setStatusCode(405, 'Method Not Allowed');
                $response->setJsonContent(["status" => false, "errors" =>$errors]);
                $response->send();
            }

           

            $security = new Security();
            $customer = new Customer();
            
            $customer->first_name = $data->first_name;
            $customer->last_name = $data->last_name;
            $customer->username = $data->username;
            $customer->password = $security->hash($data->password);
            $is_saved = $customer->save();
        
            if(!$is_saved){
                $errors = $customer->getMessages();
                $response->setStatusCode(405, 'Method Not Allowed');
                $response->setJsonContent(["status" => false, "errors" =>$errors]);
                $response->send();
            }

            $response->setStatusCode(200, 'OK');
            $response->setJsonContent(["status" => true, "error" => false, "data" => $customer ]);
            $response->send();
            
        }

       
    }
    
    public function authAction(){
        $this->view->disable();

        $response = new Response();
        $request = new Request();
        
        if ($request->isPost()) {

            $validation = new Validation();

            $validation->add(
                [
                    "username",
                    "password",
                ],
                new PresenceOf(
                    [
                        "message" => [
                            "username" => "Username  is  is required",
                            "password"     => "Password is  is required",
                        ],
                    ]
                )
            );

            $data = $request->getJsonRawBody();
            $messages = $validation->validate($data);
            
            if (count($messages) > 0) {
                $errors = [];
                foreach ($messages as $message) {
                    $errors[$message->getField()] = $message->getMessage();
                }
                $response->setStatusCode(405, 'Method Not Allowed');
                $response->setJsonContent(["status" => false, "errors" =>$errors]);
                $response->send();
            }
           
            // $customer = Customer::findFirst(array( 
            //     'username = :username: and password = :password: and customer_status = :customer_status:', 
            //     'bind' => array( 
            //        'username' => $data->username, 
            //        'password' => $data->password,
            //        'customer_status'=>1
            //     ) 
            // )); 

            $customer = Customer::findFirst(
                [
                    'username = :username: and customer_status = :customer_status:', 
                    'bind' => array( 
                        'username' => $data->username, 
                        'customer_status'=>1
                     ) 
                ]
            );

            
            if($customer){ 
                $security = new Security();
                $check = $security->checkHash($data->password, $customer->password);
                if($check){
                    $jwt = new myJwt();
                    $token = $jwt->token($customer->getId());
                    $response->setStatusCode(200, 'OK');
                    $response->setJsonContent(["status" => true, "error" => false, "token" => $token]);
                    $response->send();
                    exit();
                }
                else{
                    $response->setStatusCode(401, 'Unauthorized');
                    $response->setJsonContent(["status" => false, "errors" =>"username or password is incorrect"]);
                    $response->send();
                    exit();

                }
                
            }
            else{
                $response->setStatusCode(401, 'Unauthorized');
                $response->setJsonContent(["status" => false, "errors" =>"username or password is incorrect"]);
                $response->send();
                exit();

            }

             

            
        }
        
    }


    public function validateAction(){

        $this->view->disable();

        $response = new Response();
        $request = new Request();
        
        if ($request->isPost()) {

            $jwt = new myJwt();
            $data = $request->getJsonRawBody();
            $validation = $jwt->validateToken($data->token);

            $response->setStatusCode(200, 'OK');
            $response->setJsonContent(["status" => true, "error" => false]);
            $response->send();
            exit();

            
        }

    }


   

    

}

