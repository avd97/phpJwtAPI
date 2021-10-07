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

    // $data = json_decode(file_get_contents("php://input"));

    // $headers = getallheaders();

    $projects = $user_obj->getAllProjects();

    // print_r($projects);

    // if($projects->num_rows() > 0) {

        // echo count($projects);
    if(count($projects) > 0) {
        $project_arr = array();

        // while($row = $projects->fetch()) {
            
        //     $project_arr[] =  
        //         array(
        //             'id' => $row['id'],
        //             'name' => $row['name'],
        //             'description' => $row['description'],
        //             'status' => $row['status'],
        //             'user_id' => $row['user_id'],
        //             'created_at' => $row['created_at']
        //         );
        // }

        // foreach ($projects->result() as $row) {
        //     $data[] = $row;
        // }

        http_response_code(200);
        echo json_encode(array(
            'status' => http_response_code(200),
            'message' => 'Success.',
            'data' => $project_arr
        ));

    } else {

        http_response_code(203);
        echo json_encode(array(
            'status' => http_response_code(203),
            'message' => 'No Data Found.'
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