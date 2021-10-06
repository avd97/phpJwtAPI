<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';
    include_once '../class/employees.php';

    $database = new Database();
    $db = $database->getConnection();

    $item = new Employee($db);

    $data = json_decode(file_get_contents("php://input"));

    $item->name = $data->name;
    $item->email = $data->email;
    $item->age = $data->age;
    $item->designation = $data->designation;
    $item->created = date('Y-m-d H:i:s');
    
    if($item->createEmployee()){
        $status = http_response_code(200);
        $msg = "success";
        $data = 'Employee registered successfully.';
        // echo ;
    } else{
        $status = http_response_code(400);
        $msg = "failed";
        $data = 'Employee failed to register.';
    }

    $emp_arr = array(
        "status" => $status,
        "message" => $msg,
        "data" => $data
    );
    echo json_encode($emp_arr);

    // $database.close();
?>