<?php
declare(strict_types=1);

use Phalcon\Http\Response;
use Phalcon\Http\Request;



class CustomerController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }

    public function getCustomerAction()
    {
        
        $this->view->disable();

        $response = new Response();
        $request = new Request();
        
        if ($request->isGet()) {

            $returnData = Customer::findFirst(1); 
            $response->setStatusCode(200, 'OK');
            $response->setJsonContent(["status" => true, "error" => false, "data" => $returnData ]);

        } else {
            $response->setStatusCode(405, 'Method Not Allowed');
            $response->setJsonContent(["status" => false, "error" => "Method Not Allowed"]);
        }

        // Send response to the client
        $response->send();

    }

}

