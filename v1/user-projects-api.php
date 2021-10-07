<?php

@ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

include_once '../config/database.php';
include_once '../class/Users.php';

$db = new Database();
$connection = $db->getConnection();

$user_obj = new Users($connection);

if($_SERVER['REQUEST_METHOD'] === 'GET') {

    $headers = getallheaders();

    $jwt = $headers['Authorization'];

    try{

        $secret_key = "owt125";

        $decoded_data = 
                JWT::decode($jwt, $secret_key, array("HS512"));

        $user_obj->user_id = $decoded_data->data->id;

        $projects = $user_obj->getUsersAllProjects();

        if($projects->rowCount() > 0) {
            $project_arr = array();

            while ($row = $projects->fetch(PDO::FETCH_ASSOC)){  
                extract($row);

                $e = array(
                        'id' => intval($row['id']),
                        'name' => $row['name'],
                        'description' => $row['description'],
                        'status' => $row['status'],
                        'user_id' => intval($row['user_id']),
                        'created_at' => $row['created_at']
                );

                array_push($project_arr, $e);
            }

            http_response_code(200);
            echo json_encode(array(
                'status' => http_response_code(200),
                'message' => 'Success.',
                'size' => $projects->rowCount(),
                'data' => $project_arr
            ));

        } else {

            http_response_code(203);
            echo json_encode(array(
                'status' => http_response_code(203),
                'message' => 'No Data Found.'
            ));

        }
    } catch(Exception $ex) {

        http_response_code(404);
            echo json_encode(array(
                'status' => http_response_code(404),
                'message' => $ex->getMessage()
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