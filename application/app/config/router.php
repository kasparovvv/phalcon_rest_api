<?php

// use MyApp\Libraries\myJwt;


$router = $di->getRouter();



// $router->add("/api/v1/get_customer", "Api::getCustomer", ["GET"]);
$router->add("/api/v1/auth", "Api::auth", ["POST"]);
$router->add("/api/v1/register", "Api::register", ["POST"]);
$router->add("/api/v1/validate_token", "Api::validate", ["POST"]);


$router->add("/api/v1/orders/create_order", "Order::createOrder", ["POST"]);
$router->add("/api/v1/orders/order/{id}", "Order::getOrderById", ["GET"]);
$router->add("/api/v1/orders/update", "Order::updateOrder", ["POST"]);
$router->add("/api/v1/orders", "Order::getOrders", ["GET"]);


// $route = $router->add(
//     '/api/v1/orders/create_order',
//     [
//         'controller' => 'Order',
//         'action'     => 'createOrder',
//         'method'=>"POST"
//     ]
// );


$router->handle($_SERVER['REQUEST_URI']);

