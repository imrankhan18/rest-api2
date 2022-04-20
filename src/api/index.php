<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Application;
use Phalcon\Config;
use Phalcon\Loader;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$config = new Config([]);


require_once "../vendor/autoload.php";
$loader = new Loader();
$loader->registerNamespaces(
    [
        'Api\Models' => './models/',
    ]
);
$loader->register();
// $application = new Application($container);
$container = new FactoryDefault();
$container->set(
    'mongo',
    function () {
        $mongo = new \MongoDB\Client("mongodb://mongo", array("username" => 'root', "password" => "password123"));

        return $mongo;
    },
    true
);
$prod = new Api\Models\Robots();
$app = new Micro($container);


// $app->post(
//     '/api/products',
//     function () use ($app) {
//         $insert = $app->request->getPost();
//         print_r($insert);
//         $data = $app->mongo->rest_api->products->insertOne([
//             'name' => $insert['name'],
//             'price' => $insert['price'],
//             'category' => $insert['category'],
//             'quantity' => $insert['quantity'],
//         ]);
//         echo "<pre>";
//         print_r($data);
//     }
// );

// $app->get(
//     '/api/token/{role}',
//     function ($role) use ($app) {
//         $key = "Admin_Key";
//         $payload = array(
//             "iss" => "http://example.org",
//             "aud" => "http://example.com",
//             "iat" => 1356999524,
//             "nbf" => 1357000000,
//             "role" => $role,
//         );
//         $jwt = JWT::encode($payload, $key, 'HS256');
//         echo $jwt;
//     }
// );

// $app->get(
//     '/api/products/search/{name}',
//     function ($name) use ($app) {

//         $name = urldecode($name);
//         $value = explode(" ", $name);
//         $key = "Admin_Key";
//         $bearer = $app->request->getHeaders();
//         $token = $bearer['Token'];
//         // echo $token; die;
//         $jwt = JWT::decode($token, new Key($key, 'HS256'));
//         if (array_key_exists('Token', $bearer)) {
//             if ($jwt->role == 'admin') {
//                 foreach ($value as $key => $val) {

//                     $data = $app->mongo->rest_api->products->findOne(['name' => $val]);


//                     foreach ($data as $k => $v) {
//                         $getdata = json_encode($v);
//                         echo $getdata;
//                     }
//                 }
//             } else {
//                 echo "Access denied";
//             }
//         } else {
//             echo "Invalid Token";
//         }
//     }
// );
//---------------------------------------------------by handle-------------------------------------
$app->get(
    '/api/search/{name}',
    [
        $prod,
        'search'
    ]
);
$app->post(
    '/api/products/add',
    [
        $prod,
        'add'
    ]
);

$app->get(
    '/api/gettoken/{role}',
    [
        $prod,
        'gettoken'
    ]
);
//---------------------------------------------------------------------------------------------------------------------------------
$app->handle(
    $_SERVER["REQUEST_URI"]
);
