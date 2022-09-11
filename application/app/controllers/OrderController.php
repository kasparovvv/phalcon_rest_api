<?php
declare(strict_types=1);

use Phalcon\Http\Response;
use Phalcon\Http\Request;
use Phalcon\Security;
use Phalcon\Mvc\Dispatcher;

use Phalcon\Mvc\Model\Manager;



use Phalcon\Validation;

use Phalcon\Validation\Validator\PresenceOf;

//class OrderController extends \Phalcon\Mvc\Controller
class OrderController extends ControllerBase
{
    public function createOrderAction(){
        
        $this->view->disable();

        $response = new Response();
        $request = new Request();
        
        if ($request->isPost()){

            $validation = new Validation();

            $validation->add(
                [
                    "address",
                    "products",
                    "shippingDate"
                ],
                new PresenceOf(
                    [
                        "message" => [
                            "address" => "Address  is  is required",
                            "products"     => "Password is  is required",
                            "shippingDate"     => "shippingDate is  is required",
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
                exit();
            }
           
            $random = new \Phalcon\Security\Random();
            
            $order = new Orders();
            $order->orderCode = $random->uuid();
            $order->address = $data->address;
            $order->shippingDate = date("Y-m-d",strtotime($data->shippingDate));
            $order->customerId = $this->userId;
            $is_saved = $order->save();
            
            if($is_saved){
                foreach($data->products as $product){
                    $order_has_product = new OrderHasProduct();
                    $order_has_product->orderId = $order->getId();
                    $order_has_product->productId = $product;
                    $order_has_product->save(); 
                }
            }

            $response->setStatusCode(200, 'OK');
            $response->setJsonContent(["status" => true, "error" => false, "data" => $order ]);
            $response->send();

        }

    }

    public function getOrderByIdAction($orderId){
        
        $this->view->disable();
        
        $response = new Response();
        $request = new Request();

        if ($request->isGet()) {
            if(!$orderId){
                $errors = "Parameter is missing";
                $response->setStatusCode(405, 'Method Not Allowed');
                $response->setJsonContent(["status" => false, "errors" =>$errors]);
                $response->send();
                exit();
            }
            
            $orders = Orders::findFirst([
                'id = :orderId: AND customerId = :customerId:',
                'bind' => ['orderId' => $orderId,'customerId' => $this->userId],
            ]);

            $data = [];
            if(!is_null($orders)){
                $products = $orders->products->toArray();
                $data = [
                    "order"=>$orders,
                    "products"=>$products
                ];
                $response->setStatusCode(200, 'OK');
                $response->setJsonContent(["status" => true, "error" => false, "data" => $data ]);
            }
            else{
                $response->setStatusCode(404, 'Not Found');
                $response->setJsonContent(["status" => true, "error" => false, "message" => "No order  found with  given orderid" ]);
            }
            
            $response->send();
    
        }
    

    }

    public function getOrdersAction(){
        
        
        $this->view->disable();
        
        $response = new Response();
        $request = new Request();
        
        if ($request->isGet()){
            $data = [];
            // $orders  = Orders::find();
            $orders = Orders::find(
                [
                    'conditions' => 'customerId = :customerId:',
                    'bind'       => [
                        'customerId' => $this->userId
                    ]
                ]
            );

            
            foreach ($orders as $key=>$item) {
                // $data[] = [
                //     "order"=>$item,
                //     "products" =>  $item->getproducts()
                // ];
                $data[$key]["order"]["item"] = $item;
                $data[$key]["order"]["producsts"] = $item->getproducts();
            }

            $response->setStatusCode(200, 'OK');
            $response->setJsonContent(["status" => true, "error" => false, "data" => $data ]);
            $response->send();

        }
    

    }

    public function updateOrderAction(){

        $this->view->disable();

        $response = new Response();
        $request = new Request();
        
        if ($request->isPost()) {

            $validation = new Validation();
            $validation->add(
                [
                    "orderId",
                    
                ],
                new PresenceOf(
                    [
                        "message" => [
                            "orderId" => "orderId  is  required",
                            
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
                exit();
            }

            $data = $request->getJsonRawBody();
            $today = date("Y-m-d");
            $order = Orders::findFirst($data->orderId);
            
            if($today < $order->shippingDate){
                $order->address = $data->address;
                $is_updated = $order->update();

                if($is_updated){
                    $order_has_products = OrderHasProduct::find([
                        'orderId = ?0',
                        'bind' => [$data->orderId],
                    ]);
                    
                    if($data->products){
                        foreach($order_has_products as $ohp){
                            $ohp->ohp_status = 0;
                            $ohp->update();
                        }
                        foreach($data->products as $product){
                            $order_has_products = new OrderHasProduct();
                            $order_has_products->orderId = $data->orderId;
                            $order_has_products->productId = $product;
                            $order_has_products->save(); 
                        }
                    }
                    
                    $response->setStatusCode(200, 'OK');
                    $response->setJsonContent(["status" => true, "error" => false,"message"=>"Updated Sucessfully"]);
                    $response->send();
                    exit();
                }
            }
            else{
                $errors = "You can't make any changes in order.Shipping date is already passed";
                $response->setStatusCode(405, 'Method Not Allowed');
                $response->setJsonContent(["status" => true, "errors" =>$errors]);
                $response->send();
                exit();
            }
            


            $response->setStatusCode(200, 'OK');
            $response->setJsonContent(["status" => true, "error" => false, "data" => $order_has_productst ]);
            $response->send();

             
        }

    }

}

