<?php

namespace Api\Models;

use Phalcon\Mvc\Controller;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Robots extends Controller
{
    public function welcome()
    {
        echo "<h1>" . "Welcome to Rest Api" . "<br>" . "Explore Api!!!" . "</h1>";
        echo "for search products use /api/search/{name}?Token=''" . "<br>" . "for token /api/gettoken/{role}";
    }
    public function add()
    {
        $insert = $this->request->getPost();
        print_r($insert);
        die;
        $data = $this->mongo->rest_api->products->insertOne([
            'name' => $insert['name'],
            'price' => $insert['price'],
            'category' => $insert['category'],
            'quantity' => $insert['quantity'],
        ]);
        echo "<pre>";
        print_r($data);
    }
    public function search($name = "")
    {
        $name = urldecode($name);
        $value = explode(" ", $name);
        // $key = "Admin_Key";
        // // $bearer = $this->request->getHeaders();
        // $bearer = $this->request->get('Token');
        // $jwt = JWT::decode($bearer, new Key($key, 'HS256'));
        // if ($jwt->role == 'admin') 
        {
            foreach ($value as $key => $val) {

                $data = $this->mongo->rest_api->products->findOne(['name' => ['$regex' => $val]]);
                foreach ($data as $k => $v) {
                    $getdata = json_encode($v);
                    echo $getdata . "<br>";
                }
            }
        }
        //  else 
        {
            //     echo "Access denied";
            // }
        }
    }
    public function gettoken($role)
    {
        $key = "Admin_Key";
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "role" => $role,
        );
        $jwt = JWT::encode($payload, $key, 'HS256');
        echo $jwt;
    }
    public function getlimit($per_page, $page)
    {
        $per_page = (int)$per_page;
        $page = (int)$page;
        $results = ($page - 1) * $per_page;
        $search = $this->mongo->rest_api->products->find([], ['limit' => $per_page, 'skip' => $results])->toArray();

        echo "<pre>";
        foreach ($search as $value) {
            print_r(json_encode($value) . "<br>");
        }
        die;
    }
}
