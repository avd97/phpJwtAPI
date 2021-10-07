<?php

class Users {

    // define properties for
    public $name;
    public $email;
    public $password;
    public $user_id;
    public $project_name;
    public $description;
    public $status;

    private $conn;

    private $users_table;
    private $project_table;

    public function __construct($db){
        $this->conn = $db;
        $this->users_table = "tbl_student";
        $this->project_table = "tbl_projects";
    }

    public function create_user() {
        
        // $user_query = "INSET INTO ".$this->users_table." SET name = :name, email = :email, password = :password";

        // $this->name=htmlspecialchars(strip_tags($this->name));
        // $this->email=htmlspecialchars(strip_tags($this->email));
        // $this->password=htmlspecialchars(strip_tags($this->password));
        
        // $user_obj->bindParam(":name", $this->name);
        // $user_obj->bindParam(":email", $this->email);
        // $user_obj->bindParam(":password", $this->password);


        $user_query = "INSERT INTO ".$this->users_table." SET name = ?, email = ?, password = ?";
        $user_obj = $this->conn->prepare($user_query);

        $user_obj->bindParam(1, $this->name);
        $user_obj->bindParam(2, $this->email);
        $user_obj->bindParam(3, $this->password);
        //  $this->email, $this->password);
        
        if($user_obj->execute()) {
            return true;
        }

        return false;
    }

    public function check_email() {

        $emailQry = "SELECT * FROM ".$this->users_table." WHERE email = ?";

        $usr_obj = $this->conn->prepare($emailQry);
       
        // $usr_obj = $this->conn->query($emailQry);

        // $user_obj->bind_param('s', $this->email);
        $usr_obj->bindParam(1, $this->email);
        
        // $usr_obj->setFetchMode(PDO::FETCH_ASSOC);
        
        if($usr_obj->execute()) {
            
            //$data = $usr_obj->get_result();
            
            $data= $usr_obj->fetch(PDO::FETCH_ASSOC);
            //$data=array("id"=>101,"name"=>"abc","password"=>"jhgsdjgaj","createdAt"=>"78-9-9");

            return $data;
        }

        // if($row = $usr_obj->fetch()) {
        //     return $row;
        // }

        return array();
    }

    public function check_login() {

        // $emailQry = "SELECT * FROM ".$this->users_table." WHERE email = ? AND password = ?";
        $emailQry = "SELECT * FROM ".$this->users_table." WHERE email = ?";

        $usr_obj = $this->conn->prepare($emailQry);

        $usr_obj->bindParam(1, $this->email);
        // $usr_obj->bindParam(2, $this->password);
        
        if($usr_obj->execute()) {
            
            $data= $usr_obj->fetch(PDO::FETCH_ASSOC);

            return $data;
        }

        return array();
    }


    public function create_project() {

        $project_query = "INSERT INTO ".$this->project_table.
        " SET user_id = ?, name = ?, description = ?, status = ?";
        $project_obj = $this->conn->prepare($project_query);

        $project_obj->bindParam(1, $this->user_id);
        $project_obj->bindParam(2, $this->project_name);
        $project_obj->bindParam(3, $this->description);
        $project_obj->bindParam(4, $this->status);
        
        if($project_obj->execute()) {
            return true;
        }

        return false;

    }

    // used to list of peojrcts
    public function getAllProjects() {

        $projectQry = "SELECT * FROM ".$this->project_table." ORDER BY id DESC";

        $project_obj = $this->conn->prepare($projectQry);

        $project_obj->execute();

        // return $project_obj->fetch(PDO::FETCH_ASSOC);
        // return $project_obj->fetchAll();
        return $project_obj;
    }


    
    public function getUsersAllProjects() {

        $projectQry = "SELECT * FROM ".$this->project_table." WHERE user_id = ? ORDER BY id DESC";

        $project_obj = $this->conn->prepare($projectQry);

        $project_obj->bindParam(1, $this->user_id);

        $project_obj->execute();

        // return $project_obj->fetch(PDO::FETCH_ASSOC);
        // return $project_obj->fetchAll();
        return $project_obj;
    }

}

?>