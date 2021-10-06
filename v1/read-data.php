<?php

    ini_set('display_errors', 1);

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require '../vendor/autoload.php';
    use \Firebase\JWT\JWT;

    include_once '../config/database.php';
    include_once '../class/Users.php';

    /*
      
     {
        "jwt" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJpYXQiOjE2MzM1MzM4MzUsIm5iZiI6MTYzMzUzMzg0NSwiZXhwIjoxNjMzNTMzODk1LCJhdWQiOiJteXVzZXJzIiwiZGF0YSI6eyJpZCI6IjgiLCJuYW1lIjoiQWJoaXNoZWsgRCIsImVtYWlsIjoiYWJoaXNoZWtAZy5pbiJ9fQ.tl_15Vx_B4Q7UUnaxGoWbYY3UmCtWoMMMEhj70EIT5n6sDa363WlR6G2ypNWf8VOEexc6yoKRCSJZ0MmO_h4kg"
    }

     */
    $db = new Database();
    $connection = $db->getConnection();

    $user_obj = new Users($connection);
    

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        // $data = json_decode(file_get_contents("php://input"));

        $all_headers = getallheaders();

        // $data->jwt = getallheaders()['Authorization'];

        // print_r($all_headers);

        $data = $all_headers['Authorization'];

        // if(!empty($data->jwt)) {

        if(!empty($data)) {

            try {

            $secret_key = "owt125";

            // $decoded_data = JWT::decode($data->jwt, $secret_key, array("HS512"));

            $decoded_data = JWT::decode($data, $secret_key, array("HS512"));

            http_response_code(200);
            echo json_encode(array(
                'status' => http_response_code(200),
                'message' => "JWT Token found.",
                'userData' => $decoded_data,
                'user_id' => (int)$decoded_data->data->id
            )); 

            } catch (Exception $ex) {
                
                http_response_code(500);
                echo json_encode(array(
                    'status' => http_response_code(500),
                    'message' => $ex->getMessage(),
                    'userData' => null,
                    
                ));                 
            }

        }
    }

?>