<?php

@ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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

    if(!empty($data->name) && !empty($data->email) && !empty($data->password)) {
        
        $user_obj->name =     $data->name;
        $user_obj->email =    $data->email;
        // $user_obj->password = $data->password;
        $user_obj->password = password_hash($data->password, PASSWORD_DEFAULT);


        // $email_data will hold the array of data of perticular person by email
        $email_data = $user_obj->check_email();

        // raise the error if $email_data will already have an array.
        if(!empty($email_data)) {

            http_response_code(500);
            echo json_encode(array(
                'status' => http_response_code(500),
                'message' => 'Email already exists, try another email ID',
                ));
                
        } else {
            // if $email_data is an empty array, it'll insert record into table.
            if($user_obj->create_user()) {
                http_response_code(200);
                echo json_encode(array(
                    'status' => http_response_code(200),
                    'message' => "User has been created."
                ));
            } else {
    
                http_response_code(500);
                echo json_encode(array(
                    'status' => http_response_code(500),
                    'message' => 'Failed to save user.'
                ));
    
            }
    
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