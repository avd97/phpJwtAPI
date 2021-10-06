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
        RESPONSE BODY
        {
            "email" : "abhishek@g.in",
            "password" : "123456"
        }
    */

    $db = new Database();
    $connection = $db->getConnection();

    $user_obj = new Users($connection);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->email) && !empty($data->password)) {

            $user_obj->email = $data->email;
            // $user_obj->password = $data->password;

            $user_data = $user_obj->check_login();

            if(!empty($user_data)) {

                $uId = $user_data['id'];
                $name = $user_data['name'];
                $email = $user_data['email'];
                $password = $user_data['password'];

                if(password_verify($data->password, $password)) {
                    /*
                        -1st parameter is password which in sent by user(NORMAL PASSWORD STRING),
                        -2nd param is password fetch from table with associated email(HASHED PASSWORD STRING).
                        Then both strings will be compared.
                    */

                    $iss = 'localhost';
                    $iat = time();
                    $nbf = $iat + 10;
                    $exp = $iat + 60;
                    $aud = 'myusers';
                    $user_arr_data = array(
                        "id"=>$uId,
                        "name"=>$name,
                        "email"=>$email,
                    );

                    $secret_key = "owt125";

                    $payload_info = array(
                        'iss' => $iss,
                        'iat'=> $iat,
                        'nbf'=> $nbf,
                        'exp'=> $exp,
                        'aud'=> $aud,
                        'data'=> $user_arr_data
                    );

                    $jwt = JWT::encode($payload_info, $secret_key, 'HS512');

                    http_response_code(200);
                    echo json_encode(array(
                        'status' => http_response_code(200),
                        'message' => "Logged in successfully.",
                        'token' => $jwt
                    ));

                } else {

                    http_response_code(500);
                    echo json_encode(array(
                        'status' => http_response_code(500),
                        'message' => 'Invalid Credentials.'
                    ));
                }

            } else {

                http_response_code(404);
                echo json_encode(array(
                    'status' => http_response_code(404),
                    'message' => 'Invalid Credentials.'
                ));
            }

        } else {
            http_response_code(404);
            echo json_encode(array(
                'status' => http_response_code(404),
                'message' => 'All Data needed.'
            ));
        }
    }

?>