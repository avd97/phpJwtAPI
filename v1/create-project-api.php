<?php

@ini_set('display_errors', 1);

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
            "name" : "Abhishek D",
            "email" : "abhishek@g.in",
            "password" : "123456"
        }
    */

$db = new Database();
$connection = $db->getConnection();

$user_obj = new Users($connection);

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    $headers = getallheaders();

    if(!empty($data->project_name) && 
        !empty($data->description) && !empty($data->status)) {
        
        try {

            $secret_key = "owt125";

            $decoded_data = 
                    JWT::decode($headers['Authorization'], $secret_key, array("HS512"));

            $user_obj->user_id             =    $decoded_data->data->id;
            $user_obj->project_name        =    $data->project_name;
            $user_obj->description         =    $data->description;
            $user_obj->status              =    $data->status;

            if($user_obj->create_project()) {

                http_response_code(200);
                echo json_encode(array(
                    'status' => http_response_code(200),
                    'message' => "Project has been created."
                ));
            } else {
    
                http_response_code(500);
                echo json_encode(array(
                    'status' => http_response_code(500),
                    'message' => 'Failed to create project.'
                ));
    
            }
        } catch (Exception $ex) {
            http_response_code(404);
            echo json_encode(array(
                'status' => http_response_code(404),
                'message' => $ex->getMessage(),
            ));
        }
                
    } else {
        http_response_code(203);
        echo json_encode(array(
            'status' => http_response_code(203),
            'message' => 'All Data needed.'
        ));
    }

} else {
    http_response_code(503);
    echo json_encode(array(
        'status' => http_response_code(503),
        'message' => 'Access Denied.'
    ));
}

?>